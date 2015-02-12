<?php

class TicketsController extends AppController {

    public $name = 'Tickets';
    public $uses = array('Ticket', 'Bet', 'Event');
    public $components = array('BetApi');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('addBet', 'delete', 'getBets', 'place', 'preview', 'removeBet', 'removeBets', 'setStake', 'setType', 'getPrintTicket', 'admin_getPrintTicket','agent_place','agent_preview'));
        //$this->Auth->allow(array('*'));
    }

    public function printTicket($id) {
        $ticket = $this->Ticket->getItem($id);
        if ($this->Session->read('Auth.User.group_id') != 2 && $this->Session->read('Auth.User.group_id') != 8) { //not admin or ajent
            if (Configure::read('Settings.printing') != 1) {
                $this->redirect($this->referer());
            } else {                
                if (($this->Auth->user('group_id') != 2) && ($ticket['Ticket']['printed'] == 1)) {
                    //dont print ticket again
                    $this->__setError(__('Ticket already printed. Ticket ID: %d', $id));
                    $this->redirect($this->referer());
                }
                if ($this->Auth->user('id') != $ticket['Ticket']['user_id']) {
                    $this->__setError(__('Can not find ticket. Ticket ID: %d', $id));
                    $this->redirect($this->referer());
                }
            }
        }
        //update ticket printed status
        $ticket['Ticket']['printed'] = 1;
        $this->Ticket->save($ticket);
        //check if exists

        App::import('Vendor', 'dompdf', array('file' => 'dompdf' . DS . 'dompdf_config.inc.php'));
        $dompdf = new DOMPDF();
        //TODO get paper size
        $dompdf->set_paper(array(0, 0, 240.00, 900.00));

        $dompdf->load_html_file(Router::url(array('controller' => 'tickets', 'action' => 'getPrintTicket', $id), true));
        $dompdf->render();
        $dompdf->stream('ticket-' . $id . '.pdf', array('Attachment' => 0));
        die;
    }

    public function admin_cancel($id) {
        $this->Ticket->cancel($id);
        $this->redirect($this->referer());
    }

    public function admin_printTicket($id) {
        $this->printTicket($id);
    }

    public function admin_getPrintTicket($id) {
        $this->getPrintTicket($id);
    }

    public function getPrintTicket($id) {

        $this->Ticket->contain('TicketPart');
        $options['conditions'] = array('Ticket.id' => $id);
        $ticket = $this->Ticket->find('first', $options);
        if (isset($ticket['Ticket'])) {
            foreach ($ticket['TicketPart'] as &$ticketPart) {
                $options['conditions'] = array('BetPart.id' => $ticketPart['bet_part_id']);
                $this->Bet->BetPart->contain('Bet');
                $bet = $this->Bet->BetPart->find('first', $options);
                $event = $this->Event->getItem($bet['Bet']['event_id']);
                $ticketPart['BetPart'] = $bet['BetPart'];
                $ticketPart['Bet'] = $bet['Bet'];
                $ticketPart['Event'] = $event['Event'];
            }
            $this->set('ticket', $ticket);
        }

        //$currencies = $this->Session->read('Currencies');
        //$currency = $currencies[Configure::read('Settings.defaultCurrency')];
        $currency = Configure::read('Settings.currency');
        $this->set('currency', $currency);


       $this->layout = 'printing';
       $this->render('printing');
//        $this->layout = 'printing';
//        $this->layoutPath = 'Tickets';
//        $this->render('printing');
    }

    public function addBet($betPartId) {
        $bets = $this->__loadBets();
        $betsCount = count($bets);
        $newBet = $this->Bet->BetPart->find('first', array('conditions' => array('BetPart.id' => $betPartId)));

        //load limits leagues sport
        $event = $this->Bet->Event->getItem($newBet['Bet']['event_id']);

		//checking betting time
		$start_date  = strtotime(date("Y-m-d H:i:s"));					
		$event_date  = strtotime(date('Y-m-d H:i:s',strtotime($event['Event']['date'])));			
		$remaining   = ceil(($event_date - $start_date) / 60);
		if(isset($remaining) && $remaining < 0) {
			$this->__setError(__('can\'t add bets.Event had started.'));
		}

        $this->Bet->Event->League->contain('Sport');
        $league = $this->Bet->Event->League->find('first', array('conditions' => array('League.id' => $event['Event']['league_id'])));	

        $newBet['Bet']['date'] = $event['Event']['date'];
        $newBet['Bet']['Sport'] = array(
            'name' => $league['Sport']['name'],
            'min_bet' => $league['Sport']['min_bet'],
            'max_bet' => $league['Sport']['max_bet']
        );
        $newBet['Bet']['League'] = array(
            'min_bet' => $league['League']['min_bet'],
            'max_bet' => $league['League']['max_bet']
        );
        $newBet['Bet']['stake'] = Configure::Read('Settings.minBet');
        $newBet['Bet']['winning'] = $newBet['Bet']['stake'] * $newBet['BetPart']['odd'];

        //can we add bet?     
        $ok = true;
        if ($betsCount == Configure::read('Settings.maxBetsCount')) {
            $ok = false;
            $this->__setError(__('can\'t add more bets'));
        } else {
            foreach ($bets as $bet) {
                // jeigu single bet, yra galima to paties evento prisidaryt belenkiek bil.
                if ($bet['Bet']['event_id'] == $newBet['Bet']['event_id'] && (($this->Session->read('Ticket.type') != "1") || (!Configure::read('Settings.allowMultiSingleBets')))) {
                    $ok = false;
                    $this->__setError(__('can\'t add bet'));
                }
            }
        }

        //if we can add it
        if ($ok) {
            $bets[] = $newBet;
            $this->Session->write('Ticket.bets', $bets);
        }

        //get bets and show them to user
        $this->getBets();
    }

    function __loadBets() {
        $bets = array();
        if ($this->Session->check('Ticket.bets')) {
            $bets = $this->Session->read('Ticket.bets');
        }
        return $bets;
    }

    function __getStake($type = 1) {
        $bets = $this->__loadBets();
        $stake = Configure::Read('Settings.minBet');
        if ($type == 1) {
            //single bet
            $stake = 0;
            $bets = $this->__loadBets();
            foreach ($bets as $bet) {
                $stake += $bet['Bet']['stake'];
            }
        } else if ($type == 2) {
            //multiple
            if ($this->Session->check('Ticket.stake')) {
                $stake = $this->Session->read('Ticket.stake');
            } else {
                $bets = $this->Session->write('Ticket.stake', $stake);
            }
        } else if ($type == 3) {
            //JACKPOT
            $stake = Configure::read('Settings.jackpotPicks' . count($bets));
        }
        return $stake;
    }

    function __getOdds($type = 1) {
        $totalOdds = 1;
        $bets = $this->__loadBets();
        foreach ($bets as $bet) {
            $totalOdds *= $bet['BetPart']['odd'];
        }
        return $totalOdds;
    }

    function __getWinning($type = 1, $odd = 0, $stake = 0) {
        $winning = 0;
        if ($type == 1) {
            $bets = $this->__loadBets();
            foreach ($bets as $bet) {
                $winning += $bet['BetPart']['odd'] * $bet['Bet']['stake'];
            }
        } else if ($type == 2) {
            $winning = $odd * $stake;
        }
        //check if winning < maxWin
        if ($winning > Configure::read('Settings.maxWin')) {
            $winning = Configure::read('Settings.maxWin');
        }
        return $winning;
    }

    public function getBets($sleep = 0, $layout = 'ajax', $render = 'betslip') {
        //usleep($sleep);

        $bets = $this->__loadBets();

        //check for jackpot
        $jackpot     = $this->__canJackpot($bets);

        $betsCount   = count($bets);
        $type        = $this->__getType($jackpot);
        $totalStake  = $this->__getStake($type);

        $totalOdds    = $this->__getOdds($type);
        $totalWinning = $this->__getWinning($type, $totalOdds, $totalStake);
        //$currency = Configure::read('Settings.currency');
        $currency = $this->__getCurrency();

        $this->set(compact('currency', 'type', 'bets', 'betsCount', 'totalOdds', 'totalStake', 'totalWinning', 'jackpot'));

        $tickets = array();
        if ($type == 1) {
            foreach ($bets as $bet) {
                $ticket = array(
                    'Bets' => array(0 => $bet),
                    'Ticket' => array(
                        'odd' => $bet['BetPart']['odd'],
                        'stake' => $bet['Bet']['stake'],
                        'winning' => $bet['Bet']['winning']
                    )
                );
                $tickets[] = $ticket;
            }
        } else {
            $tickets[]['Bets'] = $bets;
            $tickets[0]['Ticket']['odd'] = $totalOdds;
            $tickets[0]['Ticket']['stake'] = $totalStake;
            $tickets[0]['Ticket']['winning'] = $this->Ticket->getMaxWin($totalWinning);
        }
        $this->set('tickets', $tickets);
        $this->layout = $layout;
        $this->render($render);
    }

    private function __getCurrency() {
        return Configure::read('Settings.currency');
    }

    function __canJackpot($bets) {
        if (Configure::read('Settings.jackpot') != 1) {
            return false;
        }
        $betCount = count($bets);
        if (($betCount < 7) || ($betCount > 10)) {
            return false;
        }
        list($weekStart, $weekEnd, $lastWeekStart) = $this->BetApi->getJackpotWeek();
        foreach ($bets as $bet) {
            if (($bet['Bet']['Sport']['name'] != 'Football') || ($bet['BetPart']['name'] != 'X') || (strtotime($bet['Bet']['date']) < $weekStart) || (strtotime($bet['Bet']['date']) >= $weekEnd)) {
                return false;
            }
        }
        return true;
    }

    function __getType($jackpot = false) {
        $bets = $this->__loadBets();

        if (count($bets) == 1 && !$this->Session->check('Ticket.type')) {
            $this->Session->write('Ticket.type', 1);
            return 1;
        }

        //we have type set
        if ($this->Session->check('Ticket.type')) {
            // 1 -> 2 change to multi
            if (count($bets) == 1) {
                $this->Session->write('Ticket.type', 1);
                return 1;
            }
            if (count($bets) == 2) {
                // nebutinai switchiname, kadangi i single leis pereiti
                // tik tada kai bus nemaziau 3 betu, o mes to nenorime.
                //$this->Session->write('Ticket.type', 2);
                //return 2;
            }
            //we don\'t allow multiple single bets, change to multi
            if ((Configure::read('Settings.allowMultiSingleBets') == 0) && ($this->Session->read('Ticket.type') == 1)) {
                $this->Session->write('Ticket.type', 2);
                return 2;
            }
            //handle jackpot
            if (($this->Session->read('Ticket.type') == 3) && (!$jackpot)) {
                $this->Session->write('Ticket.type', 2);
                return 2;
            }
            //return type (single/multi/jackpot)
            return $this->Session->read('Ticket.type');
        }

        //return multibet
        //$this->Session->write('Ticket.type', 2);
        return $this->Session->read('Ticket.type');
    }

    public function removeBet($betPartId) {
        if ($this->Session->check('Ticket.bets')) {
            $bets = $this->Session->read('Ticket.bets');
            foreach ($bets as $key => $bet) {
                if ($bet['BetPart']['id'] == $betPartId) {
                    unset($bets[$key]);
                    break;
                }
            }
            $this->Session->write('Ticket.bets', $bets);
        }
        $this->getBets();
    }

    public function removeBets() {
        $bets = $this->__loadBets();
        foreach ($bets as $key => $bet) {
            unset($bets[$key]);
        }
        $this->Session->write('Ticket.bets', $bets);
        $this->getBets();
    }

    public function delete() {
        $bets = $this->__loadBets();
        foreach ($bets as $key => $bet) {
            unset($bets[$key]);
        }
        $this->Session->write('Ticket.bets', $bets);
        $this->redirect(array('controller' => 'pages', 'action' => 'main'));
    }

    public function setStake($stake, $betId = 0) {

        $stake = str_replace(',', '.', $stake);
        $stake = (float) $stake;

        if ($betId == 0) {
            if ($stake < $this->Session->read('Settings.minBet')) {
                $this->__setError(__('Min betting amount is %d', true));
            }
            $bets = $this->Session->write('Ticket.stake', (string) $stake);
        } else {
            $bets = $this->__loadBets();
            foreach ($bets as $key => $bet) {
                if ($bet['BetPart']['id'] == $betId) {
                    $bets[$key]['Bet']['stake'] = $stake;
                    $winning = $stake * $bets[$key]['BetPart']['odd'];
                    if ($winning > Configure::read('Settings.maxWin')) {
                        $winning = Configure::read('Settings.maxWin');
                    }
                    $bets[$key]['Bet']['winning'] = $winning;
                }
            }
            $this->Session->write('Ticket.bets', $bets);
        }
        $this->getBets();
    }

    public function setType($type) {
        $bets = $this->__loadBets();
        if (($type == '2')) {
            $bets = $this->__loadBets();
            $ok = true;
            foreach ($bets as $key1 => $bet) {
                // jeigu single bet, yra galima to paties evento prisidaryt belenkiek bil.
                foreach ($bets as $key2 => $cbet) {
                    // jeigu single bet, yra galima to paties evento prisidaryt belenkiek bil.        			

                    if (($bet['Bet']['event_id'] == $cbet['Bet']['event_id']) && ($key1 != $key2)) {
                        $ok = false;
                    }
                }
            }
            if ($ok == false) {
                $this->__setError(__('You can not form this type of ticket, switching back to single'));
                $type = '1';
            }
            //$this->__setError(__('You can\'t  select multi bet with one bet'));
        }
        $this->Session->write('Ticket.type', $type);
        $this->getBets();
    }

    public function checkStake() {
        $bets = $this->__loadBets();
        if ($this->Session->check('Ticket.stake'))
            $stake = $this->Session->read('Ticket.stake');
        else {
            //$this->__setError(__('Select betting stake'));
            //return false;
            $stake = 0;
            foreach ($bets as $bet) {
                if ($this->__getType() == 1) {
                    $stake += $bet['Bet']['stake'];
                }
            }
        }
        /*
          if (strval(intval($stake)) != $stake) {
          $this->__setError(__('Invalid betting stake'));
          return false;
          }
         * 
         */
        if ($stake > $this->Session->read('Auth.User.balance')) {
            $this->__setError(__('You dont have enough money', true));
            return false;
        }
        if ($this->Session->read('Ticket.type') == 'Jackpot') {

            if (!$this->__canJackpot($bets)) {
                $this->__setError(__('Your ticket is not suitable for Jackpot play', Configure::read('Settings.minBet')));
                return false;
            }
        } else {
            if (!$this->__checkLimits()) {
                return false;
            }
        }
        return true;
    }

    function __checkLimits() {
        //don\'t check limits for jackpot
        if ($this->Session->read('Ticket.type') == 3) {
            return true;
        }
        $bets = $this->__loadBets();
        $stake = $this->Session->read('Ticket.stake');
        foreach ($bets as $bet) {
            if ($this->__getType() == 1) {
                $stake = $bet['Bet']['stake'];
            }

            if (($bet['Bet']['League']['min_bet'] != 0) && ($bet['Bet']['League']['min_bet'] > $stake)) {
                $this->__setError(__('Min bet on %s is %d', $bet['Bet']['name'], $bet['Bet']['League']['min_bet']));
                return false;
            } else if (($bet['Bet']['League']['max_bet'] != 0) && ($bet['Bet']['League']['max_bet'] < $stake)) {
                $this->__setError(__('Max bet on %s is %d', $bet['Bet']['name'], $bet['Bet']['League']['max_bet']));
                return false;
            } else if (($bet['Bet']['Sport']['min_bet'] != 0) && ($bet['Bet']['Sport']['min_bet'] > $stake)) {
                $this->__setError(__('Min bet on %s is %d', $bet['Bet']['name'], $bet['Bet']['Sport']['min_bet']));
                return false;
            } else if (($bet['Bet']['Sport']['max_bet'] != 0) && ($bet['Bet']['Sport']['max_bet'] < $stake)) {
                $this->__setError(__('Max bet on %s is %d', $bet['Bet']['name'], $bet['Bet']['Sport']['max_bet']));
                return false;
            } else if ($stake < Configure::read('Settings.minBet')) {
                $this->__setError(__('Min bet amount is %d', Configure::read('Settings.minBet')));
                return false;
            } else if ($stake > Configure::read('Settings.maxBet')) {
                $this->__setError(__('max betting amount is %d', Configure::read('Settings.maxBet')));
                return false;
            }
        }
        return true;
    }

    function checkDates() {
        $this->loadModel('Event');
        $bets = $this->__loadBets();
        foreach ($bets as $bet) {
            $utc_str = gmdate("M d Y H:i:s", time());
            $utc = strtotime($utc_str);
            if (strtotime($bet['Bet']['date']) < $utc) {
                return false;
            }
        }
        return true;
    }

    function __check() {
        if (!$this->Session->check('Ticket.bets')) {
            return false;
        }
        if (!$this->Session->check('Auth.User.id')) {
            $this->__setError(__('You need to login', true));
            return false;
        }
        $bets = $this->__loadBets();
        if (count($bets) < Configure::read('Settings.minBetsCount')) {
            $this->__setError(__('Min picks per ticket is %d', Configure::read('Settings.minBetsCount')));
            return false;
        }
        if (!$this->checkStake()) {
            return false;
        }
        if (!$this->checkDates()) {
            $this->__setError(__('One or more events already started, please remove them'));
            return false;
        }
        return true;
    }

	public function agent_preview() {	
		if(!empty($this->request->data)){			  
			$tictuseruserId = $this->request->data['ticket']['user_id'];	
			$this->Session->write('tictuseruserId', $tictuseruserId);
			$ticketUser = $this->User->dataUserGroup($tictuseruserId);
			if(empty($ticketUser)) {
				$this->Session->delete('tictuseruserId');
				$this->__setError(__('Please enter the registered user ID.', true));
				$this->redirect($this->referer());
			}
		}
		if ($this->__check()) {			
            $this->getBets(0, 'default', 'preview');
        } else {
            $this->redirect($this->referer());
        }	
    }

	public function agent_place() {	
		if($this->Session->read('tictuseruserId')) {
			$tictuseruserId = $this->Session->read('tictuseruserId');			
			//get bets
			$bets = array();
			if ($this->__check()) {
				$bets    = $this->__loadBets();
				$jackpot = $this->__canJackpot($bets);
				$type    = $this->__getType($jackpot);
				$stake   = $this->__getStake($type);
				$odd     = $this->__getOdds($type);

				if($type == 1) {
					$this->placeSingles($bets, $tictuseruserId);
				} else {
					$this->placeTicket($bets, $type, $odd, $stake, $tictuseruserId);
				}

				$this->removeBets();
				//remove monye from user
				$this->loadModel('User');
				$balance = $this->Session->read('Auth.User.balance');
				$this->User->addFunds($this->Session->read('Auth.User.id'), -1 * $stake);
				$this->Session->write('Auth.User.balance', $balance - $stake);		

				$this->User->query("INSERT INTO jackpot SET userid = '".$tictuseruserId."', amount = '" . ($stake * (float) Configure::read('Settings.jackpotPercent')) . "', datetime = NOW()");

				if (Configure::read('Settings.ticketPreview') == 1) {
					$this->redirect(array('controller' => 'tickets', 'action' => 'review'));
					exit;
				}
				$this->redirect(array('controller' => 'tickets', 'action' => 'review'));
				exit;
			}
			$this->redirect($this->referer());
		}
        $this->redirect($this->referer());
    }

    public function place() {
		if($this->Session->read('tictuseruserId')){
			$this->Session->delete('tictuseruserId');
		}
        //get bets
        $bets = array();
        if ($this->__check()) {
            $userId = $this->Session->read('Auth.User.id');
            $bets = $this->__loadBets();
            $jackpot = $this->__canJackpot($bets);
            $type = $this->__getType($jackpot);
            $stake = $this->__getStake($type);
            $odd = $this->__getOdds($type);
            if ($type == 1) {
                $this->placeSingles($bets, $userId);
            } else {
                $this->placeTicket($bets, $type, $odd, $stake, $userId);
            }

            $this->removeBets();
            //remove monye from user
            $this->loadModel('User');

            $balance = $this->Session->read('Auth.User.balance');
            $this->User->addFunds($this->Session->read('Auth.User.id'), -1 * $stake);
            $this->Session->write('Auth.User.balance', $balance - $stake);

            $this->User->query("INSERT INTO jackpot SET userid = '" . $this->Session->read('Auth.User.id') . "', amount = '" . ($stake * (float) Configure::read('Settings.jackpotPercent')) . "', datetime = NOW()");


            if (Configure::read('Settings.ticketPreview') == 1) {
                $this->redirect(array('controller' => 'tickets', 'action' => 'review'));
            }
            $this->redirect(array('controller' => 'tickets', 'action' => 'review'));
        }
        //if preview is on
        if (Configure::read('Settings.ticketPreview') == 1) {
            $this->redirect(array('controller' => 'tickets', 'action' => 'preview'));
        }
        $this->redirect($this->referer());
        //$this->getBets();
        //$this->layout = 'ajax';
    }

    function placeSingles($bets, $userId) {
        $type = 1;
        foreach ($bets as $bet) {
            $odd = $bet['BetPart']['odd'];
            $stake = $bet['Bet']['stake'];
            $this->placeTicket(array($bet), $type, $odd, $stake, $userId);
        }
    }

    function placeTicket($bets, $type, $odd, $stake, $userId) {
        $data['TicketPart'] = array();
        foreach ($bets as $bet) {
            $bet['BetPart']['bet_part_id'] = $bet['BetPart']['id'];
            unset($bet['BetPart']['id']);
            $data['TicketPart'][] = $bet['BetPart'];
        }

        $ticket['user_id'] = $userId;
        $ticket['odd'] = $odd;
        $ticket['amount'] = $stake;
        if ($type == 3)
            $ticket['amount'] = Configure::read('Settings.jackpotPicks' . count($bets));
        $ticket['type'] = $type;
        $ticket['return'] = $this->Ticket->getMaxWin($ticket['amount'] * $ticket['odd']);
        $ticket['date'] = $this->__getSqlDate();

        $data['Ticket'] = $ticket;
        $this->Ticket->create();
        $this->Ticket->saveAll($data);

        $ticketsIds = $this->Session->read('TicketsIds');
        $ticketsIds[] = $this->Ticket->id;
        $this->Session->write('TicketsIds', $ticketsIds);
    }

    function getTicket() {
        
    }

    //show tickets to user
    public function index() {
        $userId = $this->Session->read('Auth.User.id');
        $this->Ticket->contain(array('TicketPart'));
        $this->paginate['conditions'] = array(
            'Ticket.user_id' => $userId,
            'Ticket.status' => 0
        );
        $this->paginate['order'] = 'Ticket.date DESC';
        $this->paginate['limit'] = 20;
        $tickets = $this->paginate('Ticket');
        $this->set(compact('tickets'));
    }

    public function history() {
        $userId = $this->Session->read('Auth.User.id');
        $this->Ticket->contain(array('TicketPart'));
        $this->paginate['conditions'] = array(
            'Ticket.user_id' => $userId,
            'Ticket.status !=' => 0
        );
        $this->paginate['order'] = 'Ticket.date DESC';
        $this->paginate['limit'] = 20;
        $tickets = $this->paginate('Ticket');
        $this->set(compact('tickets'));
    }

    //TODO review this
    public function view() {
        if (isset($this->params['named']['ticketId'])) {
            $this->loadModel('BetPart');
            $this->loadModel('Event');

            $ticketId = $this->params['named']['ticketId'];
            $userId = $this->Session->read('Auth.User.id');

            $this->Ticket->contain('TicketPart');
            $options['conditions'] = array('Ticket.id' => $ticketId, 'Ticket.user_id' => $userId);
            $ticket = $this->Ticket->find('first', $options);
            if (isset($ticket['Ticket'])) {
                foreach ($ticket['TicketPart'] as &$ticketPart) {
                    $options['conditions'] = array('BetPart.id' => $ticketPart['bet_part_id']);
                    $this->BetPart->contain('Bet');
                    $bet = $this->BetPart->find('first', $options);
                    $options['conditions'] = array('BetPart.id' => $bet['Bet']['pick']);
                    $correctPick = $this->BetPart->find('first', $options);
                    $this->Event->contain();
                    $event = $this->Event->getItem($bet['Bet']['event_id']);
                    $ticketPart['BetPart'] = $bet['BetPart'];
                    $ticketPart['Bet'] = $bet['Bet'];
                    $ticketPart['Event'] = $event['Event'];
                    $ticketPart['Bet']['outcome'] = '';
                    if (!empty($correctPick))
                        $ticketPart['Bet']['outcome'] = $correctPick['BetPart']['name'];
                }
                $this->set('ticket', $ticket);
            }
        } else {
            
        }
    }

    public function review() {
        $ticketsIds = $this->Session->read('TicketsIds');
        if (empty($ticketsIds)) {
            $this->redirect(array('controller' => 'tickets', 'action' => 'index'));
        }
        //$this->Session->write('TicketsIds', NULL);

        $this->loadModel('BetPart');
        $this->loadModel('Event');

		if($this->Session->read('tictuseruserId')){
			$userId = $this->Session->read('tictuseruserId');
		} else{
			$userId = $this->Session->read('Auth.User.id');
		}

		$tickets = array();
        foreach ($ticketsIds as $ticketId) {
            $this->Ticket->contain('TicketPart');
            $options['conditions'] = array('Ticket.id' => $ticketId, 'Ticket.user_id' => $userId);
            $ticket = $this->Ticket->find('first', $options);
            if (isset($ticket['Ticket'])) {
                foreach ($ticket['TicketPart'] as &$ticketPart) {
                    $options['conditions'] = array('BetPart.id' => $ticketPart['bet_part_id']);
                    $this->BetPart->contain('Bet');
                    $bet = $this->BetPart->find('first', $options);
                    $options['conditions'] = array('BetPart.id' => $bet['Bet']['pick']);
                    $correctPick = $this->BetPart->find('first', $options);
                    $this->Event->contain();
                    $event = $this->Event->getItem($bet['Bet']['event_id']);
                    $ticketPart['BetPart'] = $bet['BetPart'];
                    $ticketPart['Bet'] = $bet['Bet'];
                    $ticketPart['Event'] = $event['Event'];
                    $ticketPart['Bet']['outcome'] = '';
                    if (!empty($correctPick))
                        $ticketPart['Bet']['outcome'] = $correctPick['BetPart']['name'];
                }
                $tickets[] = $ticket;
            }
        }

        //remove placed tickets
        $this->Session->write('TicketsIds', null);

        $this->set('tickets', $tickets);
        $currencies = $this->Session->read('Currencies');
        $currency = $currencies[Configure::read('Settings.defaultCurrency')];
        $this->set('currency', $currency);
    }

    public function preview() {
		if($this->Session->read('tictuseruserId')){
			$this->Session->delete('tictuseruserId');
		}
        if ($this->__check()) {
            $this->getBets(0, 'default', 'preview');
        } else {
            $this->redirect($this->referer());
        }
    }

    public function admin_user($userId = NULL) {
        $args = array();
        if (isset($userId)) {
            $args['conditions'] = array(
                'Ticket.user_id' => $userId
            );
        }
        $this->admin_index($args);
        $this->view = 'admin_index';
        //$this->render('admin_index');
    }

    public function admin_view($id) {

		if($this->Session->read('dashboard_type') && $this->Session->read('dashboard_type')=='admin_lottery'){
		   $this->Session->write('dashboard_type','admin');
		   $this->redirect(array('action' => 'admin_view',$id));
		   exit;
		}

		$this->loadModel('BetPart');
        $this->loadModel('Event');

        $ticketId = $id;

        $ticket = $this->Ticket->getAllTicketInformation($id);
        if (isset($ticket['Ticket'])) {
            foreach ($ticket['TicketPart'] as &$ticketPart) {
                $options['conditions'] = array('BetPart.id' => $ticketPart['bet_part_id']);
                $this->BetPart->contain('Bet');
                $bet = $this->BetPart->find('first', $options);
                $options['conditions'] = array('BetPart.id' => $bet['Bet']['pick']);
                $correctPick = $this->BetPart->find('first', $options);
                $this->Event->contain();
                $event = $this->Event->getItem($bet['Bet']['event_id']);
                $ticketPart['BetPart'] = $bet['BetPart'];
                $ticketPart['Bet'] = $bet['Bet'];
                $ticketPart['Event'] = $event['Event'];
                $ticketPart['Bet']['outcome'] = '';
                if (!empty($correctPick))
                    $ticketPart['Bet']['outcome'] = $correctPick['BetPart']['name'];
            }
            $this->set('ticket', $ticket);
        } else {
            $this->__setError(__('can\'t  find ticket with id: ', true) . $id);
        }

        $this->set('tabs', $this->Ticket->getTabs($this->params));
    }

    function admin_getBets() {
        $this->getBets();
    }

}

?>
