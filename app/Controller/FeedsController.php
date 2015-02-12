<?php

define('CANCELED', -2);
define('LOST', -1);
define('PENDING', 0);
define('WIN', 1);

class FeedsController extends AppController {

    const NORDICBET = 'nordicbet';
    const LINE = 'line';

    public $name = 'Feeds';
    public $uses = array('Feed', 'Sport', 'League', 'Event', 'Bet', 'BetPart');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_manualUpdate', 'admin_updateAll'));
    }

    function admin_updateAll($download = true) {
        if ($download) {
            $this->downloadAll();
        }
        $this->parseAll();

        $this->__setMessage(__('All feeds updated', true));
        CakeLog::write('feed', 'updateAll()');
        $this->redirect(array('action' => 'index'));
    }

    function downloadAll() {
        $feeds = $this->Feed->getActiveFeeds();
        foreach ($feeds as $key => $feed) {
            $this->download($feed['Feed']['id']);
        }
        $this->__setMessage(__('All feeds doanloaded', true));
    }

    function parseAll() {
        $feeds = $this->Feed->getActiveFeeds();
        foreach ($feeds as $key => $feed) {
            //parse only dowanloaded files
            $this->update($feed['Feed']['id'], false);
        }
        $this->__setMessage(__('All feeds parsed', true));
    }

    function admin_manualUpdate() {
        $feeds = $this->Feed->getActiveFeeds();
        $log['total'] = 0;
        $log[] = 'start update...';
        $this->Session->write('log', $log);
        $this->Session->write('feeds', $feeds);
        $this->redirect(array('action' => 'updating'));
    }

    function admin_updating() {
        $feeds = $this->Session->read('feeds');
        $log = $this->Session->read('log');
        if (empty($feeds)) {
            $log[] = 'done';
            $this->Session->write('log', $log);
            $this->Session->write('feeds', null);
            $this->__setMessage(__('All feeds updated', true));

            $this->flash('continue...', array('action' => 'index'), 1);
            //$this->redirect(array('action' => 'index'));
        }
        foreach ($feeds as $key => $feed) {
            $startTime = microtime(true);

            $this->update($feed['Feed']['id']);
            unset($feeds[$key]);
            $this->Session->write('feeds', $feeds);

            $endTime = microtime(true);
            $time = $endTime - $startTime;

            $log[] = 'updated ' . $feed['Feed']['name'] . ' in ' . $time;
            $log['total'] += $time;
            $this->Session->write('log', $log);

            $this->flash('continue...', array('action' => 'updating'), 1);
            //$this->redirect(array('action' => 'updating'));
            break;
        }
    }

    function admin_update($id = NULL, $download = true, $skip = 0) {
        $this->update($id, $download, $skip);
        $this->__setMessage(__('Feed updated', true));
        $this->redirect(array('action' => 'index'));
    }

    function download($id = NULL) {

        $feed = $this->Feed->getFeed($id);
        if (empty($feed)) {
            $this->__setError(__('Cant find feed', true));
            return false;
        }

        $url = $this->__getFeedUrl($feed['Feed']['url']);
        $fileName = APP . 'webroot' . DS . 'xml' . DS . Inflector::slug($feed['Feed']['name']);


        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        file_put_contents($fileName, $data);

        return $data;
    }

    function update($id = NULL, $download = 1, $skip = 0) {

        $feed = $this->Feed->getFeed($id);

        if (empty($feed)) {
            $this->__setError(__('Cant find feed', true));
            $this->redirect(array('controller' => 'feeds', 'action' => 'index'));
        }

        //set timezone
        $timezone = $feed['Feed']['timezone'];
        date_default_timezone_set($timezone);

        $url = APP . 'webroot' . DS . 'xml' . DS . Inflector::slug($feed['Feed']['name']);

        if (file_exists($url)) {
            $xml = simplexml_load_file($url);
        } else {
            if ($download) {
                $xml = $this->download($id);
                $this->redirect(array('controller' => 'feeds', 'action' => 'update', $id));
                return;
            } else {
                return;
            }
        }

        if (preg_match('/nordicbet/', $feed['Feed']['url'])) {
            $this->__update_nordicbet($xml);
        } else {
            $this->__update_line($xml);
        }

        unlink($url);
        $this->Feed->updated($id, $this->__getSqlDate());
    }

    private function __update_line($xml) {

        $startTime = microtime(true);
        //skip first games
        if (isset($this->request->params['pass'][2])) {
            $skip = $this->request->params['pass'][2];
        }
        $gameNr = 0;

        foreach ($xml->game as $gameNode) {
            if (strlen((string) $gameNode->line->score->winner) > 0) {
                $this->__setResult($gameNode);
                continue;
            }
            $gameNr++;
            if ($gameNr < $skip) {
                continue;
            }

            $team1 = (string) $gameNode->team1->name;
            $team2 = (string) $gameNode->team2->name;

            $eventName = $team1 . ' - ' . $team2;
            $eventId = (string) $gameNode->id;

            $sportName = (string) $gameNode->sporttype;
            $leagueName = (string) $gameNode->sportsubtype;

            if ($sportName == 'Other') {
                $sportName = $leagueName;
                $leagueName = 'All';
            }

            $sportId = $this->insertSport($sportName, LINE);
            $leagueId = $this->insertLeague($leagueName, $sportId, LINE);

            $bettingEndTime = (string) $gameNode->gamedate;
            $date = strtotime($bettingEndTime);
            //is it gmt?
            $eventDate = gmdate("Y-m-d H:i:s", $date);

            if (!$this->insertEvent($eventId, $eventName, $eventDate, $leagueId, LINE)) {
                
            }

            $line = $gameNode->line;
            if ((int) $line->money->team1 != 0) {
                $betId = 10000000 + $eventId;
                $betName = $eventName;
                if ((int) $line->money->draw > 0) {
                    $betType = 'Versus (with Draw)';
                } else {
                    $betType = 'Versus';
                }
                $this->insertBet($betId, $betName, $eventId, $betType);

                $this->insertBetPart($eventId, $team1, $betId, $line->money->team1);
                $this->insertBetPart($eventId + 10000000, $team2, $betId, $line->money->team2);
                if ((int) $line->money->draw > 0) {
                    $this->insertBetPart($eventId + 20000000, 'Draw', $betId, $line->money->draw);
                }
            }
            if ((int) $line->spread->team1 != 0) {
                $betId = 20000000 + $eventId;
                $betName = $eventName . ' (spread: ' . $line->spread->points . ')';
                $betType = 'Spread';
                $this->insertBet($betId, $betName, $eventId, $betType);

                $this->insertBetPart($eventId + 30000000, $team1, $betId, $line->spread->team1);
                $this->insertBetPart($eventId + 40000000, $team2, $betId, $line->spread->team2);
            }

            if ((int) $line->total->over != 0) {
                $betId = 30000000 + $eventId;
                $betName = $eventName . ' (total: ' . $line->total->points . ')';
                $betType = 'Total';
                $this->insertBet($betId, $betName, $eventId, $betType);

                $this->insertBetPart($eventId + 50000000, 'Over', $betId, $line->total->over);
                $this->insertBetPart($eventId + 60000000, 'Under', $betId, $line->total->under);
            }

            if ((int) $line->teamtotal->team1->over != 0) {
                $betId = 40000000 + $eventId;
                $betName = $team1 . ' (team total: ' . $line->teamtotal->team1->points . ')';
                $betType = 'Team Total';
                $this->insertBet($betId, $betName, $eventId, $betType);

                $this->insertBetPart($eventId + 70000000, $team1, $betId, $line->teamtotal->team1->over);
                $this->insertBetPart($eventId + 80000000, $team2, $betId, $line->teamtotal->team1->under);
            }

            if ((int) $line->teamtotal->team2->over != 0) {
                $betId = 50000000 + $eventId;
                $betName = $team2 . ' (team total: ' . $line->teamtotal->team2->points . ')';
                $betType = 'Team Total';
                $this->insertBet($betId, $betName, $eventId, $betType);

                $this->insertBetPart($eventId + 90000000, $team1, $betId, $line->teamtotal->team2->over);
                $this->insertBetPart($eventId + 100000000, $team2, $betId, $line->teamtotal->team2->under);
            }

            $endTime = microtime(true);
            $time = $endTime - $startTime;
            if ($time > 20000) {
                $this->redirect(array('action' => 'update', $id, $download, $gameNr + 1));
            }
        }
    }

    private function __update_nordicbet($xml) {
        $startTime = microtime(true);
        //skip first games
        if (isset($this->request->params['pass'][2])) {
            $skip = $this->request->params['pass'][2];
        }
        $gameNr = 0;

        foreach ($xml->Game as $gameNode) {

            $gameNr++;
            if ($gameNr < $skip) {
                continue;
            }
            $live = (string) $gameNode->LiveBet;
            if ($live == 'True') {
                continue;
            }

            $eventName = (string) $gameNode->attributes()->name;
            $eventId = (string) $gameNode->attributes()->id;

            $sportName = (string) $gameNode->Sport;
            $sportId = $this->insertSport($sportName, NORDICBET);

            $leagueName = (string) $gameNode->Region . ' - ' . (string) $gameNode->Season;
            $leagueId = $this->insertLeague($leagueName, $sportId, NORDICBET);

            if ($eventName == "Winner") {
                $eventName = (string) $gameNode->Season;
            }

            $bettingEndTime = (string) $gameNode->BettingEndTime;
            $date = strtotime($bettingEndTime);
            $eventDate = gmdate("Y-m-d H:i:s", $date);

            if (!$this->insertEvent($eventId, $eventName, $eventDate, $leagueId, NORDICBET)) {
                
            }


            foreach ($gameNode->OutcomeSet as $outcomeSetNode) {

                $betId = (string) $outcomeSetNode->attributes()->id;
                $betName = (string) $outcomeSetNode->attributes()->name;
                $betType = (string) $outcomeSetNode->attributes()->type;

                if (empty($betType)) {
                    $betType = 'Outright';
                } else if ($betType == 'Result') {
                    if (count($outcomeSetNode->Outcome) == 2) {
                        $betType = "Versus";
                    } else {
                        $betType = "Versus (with Draw)";
                    }
                } else if ($betType == 'Result') {
                    
                }

                //$bet = array('id' => $betId, 'name' => $betName, 'event_id' => $eventId, 'type' => $betType);
                $this->insertBet($betId, $betName, $eventId, $betType);

                foreach ($outcomeSetNode->Outcome as $outcomeNode) {

                    $betPartId = (string) $outcomeNode->attributes()->id;
                    $betPartOdd = (string) $outcomeNode->attributes()->odds;
                    $betPartName = (string) $outcomeNode->attributes()->name;


                    //participant?
                    if (isset($outcomeNode->Participant)) {
                        if (($betType == 'Under/Over Match') || ($betType == 'Under/Over Team')) {
                            //$betPartName = (string) $outcomeNode->Participant;
                        } else {
                            $betPartName = (string) $outcomeNode->Participant;
                        }
                    }

                    //$betPart = array('id' => $betPartId, 'name' => $betPartName, 'bet_id' => $betId, 'odd' => $betPartOdd);

                    $this->insertBetPart($betPartId, $betPartName, $betId, $betPartOdd);
                }
            }

            $endTime = microtime(true);
            $time = $endTime - $startTime;
            if ($time > 20000) {
                $this->redirect(array('action' => 'update', $id, $download, $gameNr + 1));
            }
        }
    }

    public function insertBetPart($betPartId, $betPartName, $betId, $betPartOdd) {
        $options = array(
            'recursive' => -1,
            'conditions' => array(
                'BetPart.name' => $betPartName,
                'BetPart.bet_id' => $betId
            )
        );
        $betPart = $this->BetPart->find('first', $options);

        $data = array();
        if (!empty($betPart)) {
            $data['BetPart']['id'] = $betPart['BetPart']['id'];
        } else {
            $data['BetPart']['id'] = $betPartId;
        }
        $data['BetPart']['name'] = $betPartName;
        $data['BetPart']['bet_id'] = $betId;
        $data['BetPart']['odd'] = $betPartOdd;
        $this->BetPart->create();
        $this->BetPart->id = $data['BetPart']['id'];
        return $this->BetPart->save($data);

        return $betPartId;
    }

    public function insertBet($betId, $betName, $eventId, $betType) {

        //$bet = $this->BetPart->findById($betId);
        //if (empty($bet)) {
        $data = array();
        $data['Bet']['id'] = $betId;
        $data['Bet']['name'] = $betName;
        $data['Bet']['event_id'] = $eventId;
        $data['Bet']['type'] = $betType;
        $this->Bet->create();
        return $this->Bet->save($data);
        //}
        return $betId;
    }

    public function insertEvent($eventId, $eventName, $eventDate, $leagueId, $feedType = null) {

        $options = array(
            'recursive' => -1,
            'conditions' => array(
                'Event.id' => $eventId,
                'Event.name' => $eventName,
                'Event.league_id' => $leagueId,
                'Event.feed_type' => $feedType
            )
        );
        //$event = $this->Event->find('first', $options);
        //if (empty($event)) {
        $data = array();
        $data['Event']['id'] = $eventId;
        $data['Event']['name'] = $eventName;
        $data['Event']['league_id'] = $leagueId;
        $data['Event']['date'] = $eventDate;
        $data['Event']['active'] = 1;
        $data['Event']['feed_type'] = $feedType;
        $this->Event->create();
        return $this->Event->save($data);
        //}
        return $eventId;
    }

    public function insertSport($sportName, $feedType = null) {
        $options = array(
            'recursive' => -1,
            'conditions' => array(
                'Sport.name' => $sportName,
                'Sport.feed_type' => $feedType
            )
        );
        $sport = $this->Sport->find('first', $options);
        if ((empty($sport))) {
            $order = $this->Sport->findLastOrder() + 1;
            $sport = array('Sport' => array('name' => $sportName, 'active' => 1, 'order' => $order, 'feed_type' => $feedType));
            $this->Sport->create();
            $this->Sport->save($sport, false);
            return $this->Sport->id;
        }
        return $sport['Sport']['id'];
    }

    public function insertLeague($leagueName, $sportId, $feedType = null) {
        $options = array(
            'recursive' => -1,
            'conditions' => array(
                'League.name' => $leagueName,
                'League.sport_id' => $sportId,
                'League.feed_type' => $feedType
            )
        );
        $league = $this->League->find('first', $options);
        if (empty($league)) {
            $order = $this->League->findLastOrder() + 1;
            $league = array('League' => array('name' => $leagueName, 'sport_id' => $sportId, 'active' => 1, 'order' => $order, 'feed_type' => $feedType));
            $this->League->create();
            $this->League->save($league, false);
            return $this->League->id;
        }
        return $league['League']['id'];
    }

    private function __getFeedUrl($url) {
        if (preg_match('/xml\.nordicbet/', $feed['Feed']['url'])) {
            return $url;
        } else if (preg_match('/api\.line/', $feed['Feed']['url'])) {
            $url = $url . '&sports=' . implode(',', $this->lineArray);
        }
        return $url;
    }

    private function __setResult($game) {
        $eventId = (string) $game->id;
        $winner = (string) $game->line->score->winner;
        $result = (string) $game->line->score->team1 . ' - ' . (string) $game->line->score->team2;
        CakeLog::write('line', $eventId);
        $this->loadModel('Event');
        $this->Event->setResult($eventId, $result);
        if ($winner == (string) $game->team1->name) {
            $this->__setWinLoose($eventId, 1);
            $this->__setWinLoose($eventId + 10000000, 0);
            $this->__setWinLoose($eventId + 20000000, 0);
        } else if ($winner == (string) $game->team2->name) {
            $this->__setWinLoose($eventId, 0);
            $this->__setWinLoose($eventId + 10000000, 1);
            $this->__setWinLoose($eventId + 20000000, 0);
        } else {
            $this->__setWinLoose($eventId, 0);
            $this->__setWinLoose($eventId + 10000000, 0);
            $this->__setWinLoose($eventId + 20000000, 1);
        }

        $points = (float) $game->line->spread->points;
        if ((float) $game->line->score->team1 + $points > (float) $game->line->score->team2) {
            $this->__setWinLoose($eventId + 30000000, 1);
            $this->__setWinLoose($eventId + 40000000, 0);
        } else {
            $this->__setWinLoose($eventId + 30000000, 0);
            $this->__setWinLoose($eventId + 40000000, 1);
        }

        $total = (float) $game->line->total->points;
        if ((float) $game->line->score->team1 + (float) $game->line->score->team2 > $total) {
            $this->__setWinLoose($eventId + 50000000, 1);
            $this->__setWinLoose($eventId + 60000000, 0);
        } else {
            $this->__setWinLoose($eventId + 50000000, 0);
            $this->__setWinLoose($eventId + 60000000, 1);
        }

        $total = (float) $game->line->teamtotal->team1->points;
        if ((float) $game->line->score->team1 > $total) {
            $this->__setWinLoose($eventId + 70000000, 1);
            $this->__setWinLoose($eventId + 80000000, 0);
        } else {
            $this->__setWinLoose($eventId + 70000000, 0);
            $this->__setWinLoose($eventId + 80000000, 1);
        }

        $total = (float) $game->line->teamtotal->team2->points;
        if ((float) $game->line->score->team2 > $total) {
            $this->__setWinLoose($eventId + 90000000, 1);
            $this->__setWinLoose($eventId + 100000000, 0);
        } else {
            $this->__setWinLoose($eventId + 90000000, 0);
            $this->__setWinLoose($eventId + 100000000, 1);
        }
    }

    function __setWinLoose($betPartId, $win) {
        $this->loadModel('Bet');
        if ($win != 1) {
            $status = LOST;
            CakeLog::write('line', '-- lost -- ' . $betPartId);
        } else {
            $status = WIN;
            $this->Bet->setPick($betPartId);
            CakeLog::write('line', '-- win -- ' . $betPartId);
        }

        $this->loadModel('Ticket');
        $ticketsParts = $this->Ticket->getTicketsPartsByBetPartId($betPartId);
        if (empty($ticketsParts)) {
            return;
        }
        CakeLog::write('line', '------- tickets found in ' . $betPartId);

        $minodds = Configure::read('Settings.jackpotMinOdds');
        $this->loadModel('TicketPart');
        $this->loadModel('JackpotWinning');


        foreach ($ticketsParts as $ticketPart) {
            CakeLog::write('line', '------- setting ticket part status in ' . $ticketPart['TicketPart']['id']);
            $this->TicketPart->setStatus($ticketPart['TicketPart']['id'], $status);

            if ($status == WIN and $ticketPart['TicketPart']['odd'] >= $minodds) { // Update JackpotWinning
                $this->JackpotWinning->updateLucky($ticketPart);
            }
        }
    }

    private $lineArray = array(
        "Baseball-Alt Runlines",
        "Baseball-International",
        "Baseball-Little League",
        "Baseball-Mexican",
        "Baseball-MexicanL",
        "Baseball-MLB",
        "Baseball-NCAA",
        "Baseball-Props",
        "Baseball-SeriesPrices",
        "Baseball-Softball",
        "Basketball-College Women",
        "Basketball-International",
        "Basketball-Intrnational",
        "Basketball-NBA",
        "Basketball-NBA_Preasea",
        "Basketball-NCAA",
        "Basketball-Olympics",
        "Basketball-Props",
        "Basketball-SeriesPrices",
        "Basketball-WNBA",
        "Fighting-Bellator",
        "Fighting-Boxing",
        "Fighting-Dream",
        "Fighting-MMA",
        "Fighting-Olympic Boxing",
        "Fighting-Props",
        "Fighting-Strikeforce",
        "Fighting-UFC",
        "Football-Arena",
        "Football-Canadian",
        "Football-High School",
        "Football-International",
        "Football-NCAA",
        "Football-NFL",
        "Football-NFLPRESEASON",
        "Football-United_FL",
        "Golf-Asian",
        "Golf-Australasian",
        "Golf-European",
        "Golf-International",
        "Golf-Ladies",
        "Golf-LPGA",
        "Golf-Majors",
        "Golf-Nationwide",
        "Golf-PGA",
        "Golf-PGA WGC",
        "Golf-Seniors",
        "Golf-SouthAfrican",
        "Golf-Web.com",
        "Hockey-Euro",
        "Hockey-International",
        "Hockey-NCAA",
        "Hockey-NHL",
        "Hockey-Props",
        "Motor-Formula1",
        "Motor-IndyCar",
        "Motor-Nationwide",
        "Motor-SprintCup",
        "Motor-Trucks",
        "Other-Aussie Rules",
        "Other-Auto_Racing",
        "Other-Boxing",
        "Other-Competitive Eating",
        "Other-Cricket",
        "Other-Cycling",
        "Other-Darts",
        "Other-DOW Jones",
        "Other-Golf",
        "Other-Handball",
        "Other-Horses",
        "Other-Lacrosse",
        "Other-Lotto",
        "Other-Martial Arts",
        "Other-Olympic Archery",
        "Other-Olympic Athletics",
        "Other-Olympic Badminton",
        "Other-Olympic Beach Volleyball",
        "Other-Olympic Canoeing",
        "Other-Olympic Cycling",
        "Other-Olympic Diving",
        "Other-Olympic Fencing",
        "Other-Olympic Gymnastics",
        "Other-Olympic Handball",
        "Other-Olympic Hockey",
        "Other-Olympic Judo",
        "Other-Olympic Road",
        "Other-Olympic Rowing",
        "Other-Olympic Swimming",
        "Other-Olympic Synchronised Swimming",
        "Other-Olympic Table Tennis",
        "Other-Olympic Taekwondo",
        "Other-Olympic Volleyball",
        "Other-Olympic Water Polo",
        "Other-Olympic Wrestling",
        "Other-Olympics",
        "Other-Oscars",
        "Other-Poker",
        "Other-Politics",
        "Other-Rugby League",
        "Other-Rugby Union",
        "Other-Snooker",
        "Other-Television",
        "Other-Tennis",
        "Other-Volleyball",
        "Other-Water Polo",
        "Other-Women_Tennis",
        "Other-Wrestling",
        "Soccer-Argentina",
        "Soccer-Argentina Pr",
        "Soccer-Asia",
        "Soccer-Australia",
        "Soccer-Austria",
        "Soccer-Belgium",
        "Soccer-Bolivia",
        "Soccer-Brazil",
        "Soccer-BrazilSerieA",
        "Soccer-Bulgaria",
        "Soccer-CAF Africa",
        "Soccer-Canada",
        "Soccer-Chile",
        "Soccer-China",
        "Soccer-Colombia",
        "Soccer-Copa del Rey",
        "Soccer-Costa Rica",
        "Soccer-Croatia",
        "Soccer-Cyprus",
        "Soccer-Czech Rep",
        "Soccer-Denmark",
        "Soccer-Ecuador",
        "Soccer-England",
        "Soccer-England FA",
        "Soccer-England Prem",
        "Soccer-Euro Cup",
        "Soccer-European Cup",
        "Soccer-FIFA Club WC",
        "Soccer-Finland",
        "Soccer-France",
        "Soccer-French Ligue",
        "Soccer-Germany",
        "Soccer-Greece",
        "Soccer-Halftime",
        "Soccer-Holland",
        "Soccer-Hungary",
        "Soccer-Iceland",
        "Soccer-International",
        "Soccer-Intrnational",
        "Soccer-Israel",
        "Soccer-Italy",
        "Soccer-Italy Serie A",
        "Soccer-Italy SerieA",
        "Soccer-Japan",
        "Soccer-Korea",
        "Soccer-Mexico",
        "Soccer-Mexico 1st D",
        "Soccer-Miscellaneus",
        "Soccer-New Zealand",
        "Soccer-Nor Ireland",
        "Soccer-North America",
        "Soccer-Norway",
        "Soccer-Paraguay",
        "Soccer-Peru",
        "Soccer-Poland",
        "Soccer-Portugal",
        "Soccer-Rep Ireland",
        "Soccer-Romania",
        "Soccer-Russia",
        "Soccer-Scotland",
        "Soccer-Serbia",
        "Soccer-Singapore",
        "Soccer-Slovakia",
        "Soccer-Slovenia",
        "Soccer-South Africa",
        "Soccer-South America",
        "Soccer-Spain",
        "Soccer-Spain Liga",
        "Soccer-Superliga",
        "Soccer-Sweden",
        "Soccer-Switzerland",
        "Soccer-Turkey",
        "Soccer-UAE",
        "Soccer-UEFA Champ L",
        "Soccer-UEFA CUP",
        "Soccer-Ukraine",
        "Soccer-Uruguay",
        "Soccer-USA MLS",
        "Soccer-USA USL",
        "Soccer-Venezuela",
        "Soccer-Wales",
        "Soccer-WEuropean Cup",
        "Soccer-Women Intl",
        "Soccer-World Cup",
        "Soccer-WSouth America",
        "Tennis-ATP",
        "Tennis-ATP Doubles",
        "Tennis-Challenger",
        "Tennis-Davis Cup",
        "Tennis-Exhibition",
        "Tennis-Fed Cup",
        "Tennis-ITF",
        "Tennis-Mixed Doubles",
        "Tennis-Olympic Men",
        "Tennis-Olympic Mens Doubles",
        "Tennis-Olympic Mixed Doubles",
        "Tennis-Olympic Women",
        "Tennis-Olympic Womens Doubles",
        "Tennis-Props",
        "Tennis-Qualifying",
        "Tennis-WTA",
        "Tennis-WTA Doubles"
    );

}

?>