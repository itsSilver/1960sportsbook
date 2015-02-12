<?php


class ResultsController extends AppController {

    public $name = 'Results';
    public $uses = array('Result', 'Sport', 'League', 'Event', 'Bet', 'Ticket', 'TicketPart','JackpotWinning');

    function beforeFilter() {
        parent::beforeFilter();
    }

    function admin_index() {
        $this->paginate = array(
            'fields' => array(
                'Sport.id',
                'Sport.name'
            ),
            'limit' => Configure::read('Settings.itemsPerPage')
        );
        $data = $this->paginate('Sport');
        $this->set('data', $data);
        $this->set('model', 'Sport');
        $this->set('action', 'sport');
        $this->set('tabs', $this->Result->getTabs($this->params));
    }

    function admin_sport($id) {
        $this->paginate = array(
            'fields' => array(
                'League.id',
                'League.name'
            ),
            'conditions' => array(
                'League.sport_id' => $id
            ),
            'limit' => Configure::read('Settings.itemsPerPage')
        );
        $data = $this->paginate('League');
        $this->set('data', $data);
        $this->set('model', 'League');
        $this->set('action', 'league');
        $this->set('tabs', $this->Result->getTabs($this->params));
    }

    function admin_league($id) {
        $this->paginate = array(
            'fields' => array(
                'Event.id',
                'Event.name',
                'Event.result',
                'Event.date'
            ),
            'conditions' => array(
                'Event.league_id' => $id,
                'Event.date <' => $this->__getSqlDate()
            ),
            'limit' => Configure::read('Settings.itemsPerPage')
        );
        $data = $this->paginate('Event');
        $this->set('data', $data);
        $this->set('model', 'Event');
        $this->set('action', 'event');
        $this->set('tabs', $this->Result->getTabs($this->params));
    }

    function admin_view() {
        
    }

    function admin_event($id = NULL, $all = 1) {
        $model = $this->Event->getItem($id);
        $this->set('model', $model);
        $bets = array();
        if (empty($this->request->data)) {
            $bets = $this->Event->getBets($id, $all);
        } else {
            if (empty($this->request->data['Event']['result'])) {
                $bets = $this->Event->getBets($id, $all);
                $this->__setError(__('Please enter event result', true));
            } else {
                $this->Event->setResult($this->request->data['Event']['id'], $this->request->data['Event']['result']);
                foreach ($this->request->data['Result'] as $betPartId => $win) {
                    $this->__setWinLoose($betPartId, $win);
                }
                $parentId = $this->Event->getParentId($id);
                $this->__setMessage(__('Results updated', true));
                $this->redirect(array('action' => 'league', $parentId));
            }
        }

        $this->set('data', $bets);
        $this->set('tabs', $this->Result->getTabs($this->params));
    }

    function admin_pendingSports() {
        $this->admin_allSports(true);
        $this->view = 'admin_allSports';
        //$this->render('admin_allSports');
    }

    public function admin_allAll() {
        
        //parse results first
        if (!empty($this->request->data)) {
            foreach ($this->request->data as $result) {                
                if (!empty($result['Event']['result'])) {                    
                    $this->Event->setResult($result['Event']['id'], $result['Event']['result']);
                    foreach ($result['Result'] as $betPartId => $win) {
                        $this->__setWinLoose($betPartId, $win);
                    }
                }
            }
            $this->__setMessage(__('Results updated'));
        }
        
        $betPartsIds = $this->TicketPart->getPendingBetParts();
        $betsIds = $this->Bet->BetPart->getBetsIds($betPartsIds);
        $eventsIds = $this->Bet->getEventsIds($betsIds);
        $this->paginate = array(
            'conditions' => array(
                'Event.id' => $eventsIds
            )
        );
        $events = $this->paginate('Event');
        $betsIds = array_flip($betsIds);
        foreach ($events as $eventKey => $event) {
            $sport = $this->Sport->getItem($event['League']['sport_id']);
            $events[$eventKey]['Sport'] = $sport['Sport'];
            foreach ($event['Bet'] as $betKey => $bet) {
                unset($events[$eventKey]['Bet'][$betKey]);
                if (isset($betsIds[$bet['id']])) {
                    $events[$eventKey]['Bet'][$betKey]['Bet'] = $bet;
                    $events[$eventKey]['Bet'][$betKey]['BetPart'] = $this->Bet->BetPart->getBetParts($bet['id']);
                }
            }
        }

        

        $this->set('data', $events);
        $this->set('tabs', $this->Result->getTabs($this->params));
    }

    function admin_allSports($pending = false) {
        $betPartsIds = $this->TicketPart->getPendingBetParts();
        $betsIds = $this->Bet->BetPart->getBetsIds($betPartsIds);
        $eventsIds = $this->Bet->getEventsIds($betsIds);
        $leaguesIds = $this->Event->getLeaguesIds($eventsIds, $pending);
        $sportsIds = $this->League->getSportsIds($leaguesIds);
        $this->paginate = array(
            'fields' => array(
                'Sport.id',
                'Sport.name'
            ),
            'conditions' => array(
                'Sport.id' => $sportsIds
            )
        );
        $data = $this->paginate('Sport');
        $this->set('data', $data);
        $this->set('model', 'Sport');
        $this->set('action', 'allLeagues');
        if ($pending)
            $this->set('action', 'pendingLeagues');
        $this->set('tabs', $this->Result->getTabs($this->params));
    }

    function admin_pendingLeagues($sportId) {
        $this->admin_allLeagues($sportId, true);
        $this->view = 'admin_allLeagues';
        //$this->render('admin_allLeagues');
    }

    function admin_allLeagues($sportId, $pending = false) {
        $betPartsIds = $this->TicketPart->getPendingBetParts();
        $betsIds = $this->Bet->BetPart->getBetsIds($betPartsIds);
        $eventsIds = $this->Bet->getEventsIds($betsIds);
        $leaguesIds = $this->Event->getLeaguesIds($eventsIds, $pending);
        $this->paginate = array(
            'fields' => array(
                'League.id',
                'League.name'
            ),
            'conditions' => array(
                'League.id' => $leaguesIds,
                'League.sport_id' => $sportId
            )
        );
        $data = $this->paginate('League');
        $this->set('data', $data);
        $this->set('model', 'League');
        $this->set('action', 'allEvents');
        if ($pending)
            $this->set('action', 'pendingEvents');
        $this->set('tabs', $this->Result->getTabs($this->params));
    }

    function admin_pendingEvents($leagueId) {
        $this->admin_allEvents($leagueId, true);
        $this->view = 'admin_allEvents';
        //$this->render('admin_allEvents');
    }

    function admin_allEvents($leagueId, $pending = false) {
        $betPartsIds = $this->TicketPart->getPendingBetParts();
        $betsIds = $this->Bet->BetPart->getBetsIds($betPartsIds);
        $eventsIds = $this->Bet->getEventsIds($betsIds);
        $this->paginate = array(
            'fields' => array(
                'Event.id',
                'Event.name',
                'Event.result',
                'Event.date'
            ),
            'conditions' => array(
                'Event.id' => $eventsIds,
                'Event.league_id' => $leagueId
            )
        );
        if (!$pending) {
            $this->paginate['conditions']['Event.date <'] = $this->__getSqlDate();
        }
        $data = $this->paginate('Event');
        $this->set('data', $data);
        $this->set('model', 'Event');
        $this->set('action', 'event');
        $this->set('tabs', $this->Result->getTabs($this->params));
    }

    function admin_cancel($id) {
        $bets = $this->Event->getBets($id);
        foreach ($bets as $bet) {
            $this->__cancelBet($bet);
        }
        $this->Event->setResult($id, 'Canceled');
        $parentId = $this->Event->getParentId($id);
        $this->__setMessage(__('Results updated', true));
        $this->redirect(array('action' => 'league', $parentId));
    }

    function __cancelBet($bet) {
        foreach ($bet['BetPart'] as $betPart) {
            $ticketsParts = $this->Ticket->getTicketsPartsByBetPartId($betPart['id']);
            foreach ($ticketsParts as $ticketPart) {
                $this->TicketPart->setStatus($ticketPart['TicketPart']['id'], CANCELED);
            }
        }
    }

    function __setWinLoose($betPartId, $win) {
    	
    	$minodds = Configure::read('Settings.jackpotMinOdds');
        //set win lose to betparts        
        //$betParts = $this->Bet->getBetParts($betId);
        //foreach ($betParts['BetPart'] as $betPart) {

    	$ticketsParts = $this->Ticket->getTicketsPartsByBetPartId($betPartId);
        
        
        if ($win != 1) {
            $status = LOST;
        } else {
            $status = WIN;
            $this->Bet->setPick($betPartId);
        }
        foreach ($ticketsParts as $ticketPart) {

            $this->TicketPart->setStatus($ticketPart['TicketPart']['id'], $status);
            if ($status == WIN  and $ticketPart['TicketPart']['odd'] >= $minodds ){ // Update JackpotWinning
            	$this->JackpotWinning->updateLucky($ticketPart);
            }
            
            //print_r($)
        }
        //}
    }

    /**
     * FIXME:DEAD?
     * @param unknown_type $betPartId
     * @param unknown_type $status
     */
    function __updateTicketPartsStatus($betPartId, $status) {
        App::import('Model', 'TicketPart');
        $TicketPart = new TicketPart();
        $TicketPart->updateAll(array('TicketPart.status' => $status), array('TicketPart.bet_part_id' => $betPartId));
        //get all ticket parts who won/loose
        //$TicketPart->contain('Ticket');
        $tickets = $TicketPart->find('all', array('conditions' => array('TicketPart.bet_part_id' => $betPartId)));
        if (!empty($tickets))
            $this->__updateTickets($tickets, $status);
    }

    function __updateTickets($tickets, $status) {
        App::import('Model', 'Ticket');
        $Ticket = new Ticket();

        $ticketPartsIds = array();
        foreach ($tickets as $ticket)
            $ticketPartsIds[] = $ticket['Ticket']['id'];

        if ($status == -1) //all lost
            $Ticket->updateAll(array('Ticket.status' => '-1'), array('Ticket.id' => $ticketPartsIds));
        else if ($status == 1) {
            //check  before seting to win and awarding user
            //select all ticket where lost and pending = 0
            foreach ($tickets as $ticket) {
                $count = $Ticket->TicketPart->find('count', array('conditions' => array('TicketPart.ticket_id' => $ticket['Ticket']['id'], 'TicketPart.status <>' => 1)));
                if ($count == 0) {
                    $Ticket->contain();
                    $Ticket->id = $ticket['Ticket']['id'];
                    $Ticket->read();
                    $Ticket->set(array('status' => '1'));

                    $Ticket->save($Ticket->data);

                    //award user for his bright mind
                    $this->__updateUser($ticket);
                }
            }
        }
    }

    function __updateUser($ticket) {
        App::import('Model', 'User');
        $User = new User();
        $User->contain();
        $User->id = $ticket['Ticket']['user_id'];
        $User->read();
        
        $balance = $User->data['User']['balance'] + $ticket['Ticket']['return'];

        
        //FIXME: comone someone plz craete model for me
        //$this->Sport->query("INSERT INTO jackpot SET userid = '".$User->data['User']['id']."', amount = '".($ticket['Ticket']['return']*(float)Configure::read('Settings.jackpotPercent'))."', datetime = NOW()");
        
        
        $User->set(array('balance' => $balance));
        $User->save($User->data, false);
    }

}

?>