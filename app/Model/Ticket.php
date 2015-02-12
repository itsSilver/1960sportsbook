<?php

class Ticket extends AppModel {

    public $name = 'Ticket';
    public $belongsTo = array('User');
    public $hasMany = array('TicketPart');
    public $actsAs = array('Containable');

    // Added on 5/14/2013
    /*public function getTopBets() {
      $topBets = $this->TicketPart->BetPart->getTopBetParts();
      return $topBets;
    }
     */

    function getSearch() {
        $options['fields'] = array();
        foreach ($this->_schema as $key => $value) {
            if ($key == 'user_id') {
                $options['fields']['Ticket.user_id'] = array(
                    'type' => 'text'
                );
            } else {
                if (($key != 'id') && ($key != 'order'))
                    $options['fields'][] = $this->name . '.' . $key;
            }
        }
        return $options['fields'];
    }
    
    function getSearchConditions($data) {
        $conditions = array();
        foreach ($data[$this->name] as $key => $value) {
            if ($key == 'user_id') {
                $user = $this->User->findByUsername($value);
                if (!empty($user)) {
                    $value = $user['User']['id'];
                }
            }
            if (!empty($value)) {
                $conditions[$this->name . '.' . $key . ' LIKE'] = '%' . $value . '%';
            }
        }
        return $conditions;
    }

    function getTabs($params) {
        $tabs = parent::getTabs($params);
        unset($tabs['ticketsadmin_add']);
        unset($tabs['ticketsadmin_edit']);
        return $tabs;
    }

    function getTicketsPartsByBetPartId($betPartId) {
        $options['conditions'] = array(
            'TicketPart.bet_part_id' => $betPartId
        );
        return $this->TicketPart->find('all', $options);
    }

    function getFullTicket($id) {
        $options['conditions'] = array(
            'Ticket.id' => $id
        );
        $this->contain('TicketPart');
        return $this->find('first', $options);
    }

    function getAllTicketInformation($id) {
        $options['conditions'] = array(
            'Ticket.id' => $id
        );
        $ticket = $this->contain('TicketPart', 'User');

        return $this->find('first', $options);
    }

    public function cancel($id) {
        $ticket = $this->getItem($id);
        $oldStatus = $ticket['Ticket']['status'];
        $amount =  $ticket['Ticket']['amount'];
        $return =  $ticket['Ticket']['return'];
        $ticket['Ticket']['status'] = CANCELED;        //canceled?
        $this->save($ticket);

        if ($oldStatus == WIN) {
          $this->User->addFunds($ticket['Ticket']['user_id'], $amount - $return);
        } else if ($oldStatus == LOST) {
          $this->User->addFunds($ticket['Ticket']['user_id'], $amount);          
        } else if ($oldStatus == PENDING) {
          $this->User->addFunds($ticket['Ticket']['user_id'], $amount);  
        }
        
        $this->TicketPart->updateAll(
          array('TicketPart.status' =>  CANCELED ), //canceled
          array('TicketPart.ticket_id' => $id)
        );
    }

    function update($id) {
        $ticket = $this->getFullTicket($id);

//3 = jackpot
        if ($ticket['Ticket']['type'] == 3) {
            $this->updateJackpotTicket($ticket);
        } else {
            $this->updateCommonTicket($ticket);
        }
    }

    function updateJackpotTicket($ticket) {
        $count = count($ticket['TicketPart']);
        $correct = 0;
        $incorrect = 0;
        foreach ($ticket['TicketPart'] as $ticketPart) {
            if ($ticketPart['status'] == WIN)
                $correct++;
            if ($ticketPart['status'] == LOST)
                $incorrect++;
        }
        $newStatus = PENDING;
        if ($correct >= 7) { //we won something
            $newStatus = WIN;
        }
        if ($count - $incorrect < 7) { //we lost
            $newStatus = LOST;
        }
        $ticket['Ticket']['status'] = $newStatus;
        $this->save($ticket);
    }

    function updateCommonTicket($ticket) {

        CakeLog::write('line', ' updating ticket ' . $ticket['Ticket']['id']);
        $newOdd = 1;
        $lost = false;
        $pending = false;
        if (empty($ticket)) {
            return;
        }
        foreach ($ticket['TicketPart'] as $ticketPart) {
            if ($ticketPart['status'] != CANCELED)
                $newOdd *= $ticketPart['odd'];
            if ($ticketPart['status'] == LOST)
                $lost = true;
            if ($ticketPart['status'] == PENDING)
                $pending = true;
        }
//update        
        if ($lost)
            $newStatus = LOST;
        else if ($pending)
            $newStatus = PENDING;
        else if ($newOdd == 1)
            $newStatus = CANCELED;
        else
            $newStatus = WIN;
        $status = $ticket['Ticket']['status'];
        $return = $ticket['Ticket']['return'];
        $amount = $ticket['Ticket']['amount'];
        $newReturn = $this->getMaxWin($amount * $newOdd);

//check if winning < maxWin
        if ($newReturn > Configure::read('Settings.maxWin')) {
            $newReturn = Configure::read('Settings.maxWin');
        }

        $ticket['Ticket']['odd'] = $newOdd;
        $ticket['Ticket']['return'] = $newReturn;
        $ticket['Ticket']['status'] = $newStatus;
        $this->save($ticket);

//update user
//user won
//TODO fix WIN -> CANCELED issues  
// I commented here on this block on 11/27/2012 and added updatecancelTicket function
        if (($status == PENDING) && ($newStatus == WIN)) {
            $this->User->addFunds($ticket['Ticket']['user_id'], $ticket['Ticket']['return']);
        } else if (($status == PENDING) && ($newStatus == CANCELED)) {
            $this->User->addFunds($ticket['Ticket']['user_id'], $amount);
        } else if (($status == LOST) && ($newStatus == WIN)) {
            $this->User->addFunds($ticket['Ticket']['user_id'], $ticket['Ticket']['return']);
        } else if (($status == LOST) && ($newStatus == CANCELED)) {
            $this->User->addFunds($ticket['Ticket']['user_id'], $amount);
        } else if (($status == WIN) && ($newStatus == LOST)) {
            $this->User->addFunds($ticket['Ticket']['user_id'], -$ticket['Ticket']['return']);
        } else if (($status == WIN) && ($newStatus == CANCELED)) {
            $this->User->addFunds($ticket['Ticket']['user_id'], -$return + $newReturn);
        } else if (($status == CANCELED) && ($newStatus == LOST)) {
            $this->User->addFunds($ticket['Ticket']['user_id'], -$amount);
            // $this->updateCancelTicket($ticket);
        } else if (($status == CANCELED) && ($newStatus == WIN)) {
            $this->User->addFunds($ticket['Ticket']['user_id'], $newReturn - $amount);
            // $this->updateCancelTicket($ticket);
        }
    }

    public function getMaxWin($winning) {
        if ($winning > Configure::read('Settings.maxWin')) {
            $winning = Configure::read('Settings.maxWin');
        }
        return $winning;
    }

// Wrote on 11/27/2012
    /* public function updateCancelTicket($ticket) {
      $ticket['Ticket']['odd'] = 1;
      $ticket['Ticket']['return'] = $ticket['Ticket']['amount'];
      $ticket['Ticket']['status'] = CANCELED;
      $this->save($ticket);
      }
     */

//Risks function
    function getBigOddTickets($bigOdd) {
        $options['conditions'] = array(
            'Ticket.odd >=' => $bigOdd,
            'Ticket.status' => 0
        );
        $options['order'] = 'Ticket.odd DESC';
        $this->contain('User');
        $tickets = $this->find('all', $options);
        return $tickets;
    }

    function getBigStakeTickets($bigStake) {
        $options['conditions'] = array(
            'Ticket.amount >=' => $bigStake,
            'Ticket.status' => 0
        );
        $options['order'] = 'Ticket.amount DESC';
        $this->contain('User');
        $tickets = $this->find('all', $options);
        return $tickets;
    }

    function getBigWinningTickets($bigWinning) {
        $options['conditions'] = array(
            'Ticket.return >=' => $bigWinning,
            'Ticket.status' => 0
        );
        $options['order'] = 'Ticket.return DESC';
        $this->contain('User');
        $tickets = $this->find('all', $options);
        return $tickets;
    }

    function getEventsCount($id) {
        $options['conditions'] = array(
            'TicketPart.ticket_id' => $id
        );
        $count = $this->TicketPart->find('count', $options);
        return $count;
    }

    function getPendingJackpotTickets($weekStart, $weekEnd) {
        $options['conditions'] = array(
            'Ticket.type' => 3, //3 for jp
            'Ticket.status' => 0, //0 for pending
            'Ticket.date BETWEEN ? AND ?' => array($weekStart, $weekEnd)
        );
        return $this->find('first', $options);
    }

    function updateJackpotWinners($weekStart, $weekEnd) {
        $options['conditions'] = array(
            'Ticket.type' => 3, //3 for jp
            'Ticket.status' => 1, /// 1 gor win
            'Ticket.date BETWEEN ? AND ?' => array($weekStart, $weekEnd)
        );
        $this->contain();
        $winners = $this->find('all', $options);
        if (!empty($winners)) {
            $count = count($winners);
            $winning = Configure::read('Settings.jackpotSize') / $count; //split pot
            foreach ($winners as $winner) {
                $this->User->addFunds($winner['Ticket']['user_id'], $winning);
            }
            return true; ///we have a winner
        }
        return false; //dont have a winner
    }

    function getReport($from, $to, $userId = null, $limit = NULL) {
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Ticket.date BETWEEN ? AND ?' => array($from, $to)
        );
        if ($userId != NULL) {
            $options['conditions']['Ticket.user_id'] = $userId;
        }
        if ($limit != NULL) {
            $options['limit'] = $limit;
        }
        $data = $this->find('all', $options);

        foreach ($data as $key => $value) {
            $events_count = $this->getEventsCount($value['Ticket']['id']);
            $data[$key]['Ticket']['events_count'] = $events_count;
        }

        $data['header'] = array(
            'Ticket ID',
            'User ID',
            'Date of placing',
            'Ticket type',
            'Number of events',
            'Stake',
            'Odd',
            'Wining amount',
            'Ticket status'
        );
        return $data;
    }

    public function getReportByGroupId($from, $to, $groupId) {
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Ticket.date BETWEEN ? AND ?' => array($from, $to),
            'Ticket.status <>' => CANCELED, //canceled
            'User.group_id' => $groupId
        );
        $options['joins'] = array(
            array(
                'table' => 'users',
                'alias' => 'User',
                'type' => 'INNER',
                'conditions' => 'Ticket.user_id = User.id'
            )
        );
        $data['header'] = array(__('Group ID'), __('Tickets created'), __('Total'), __('Total payout'), __('Profit'));
        $data['data'][] = array_merge(array($groupId), $this->_getProfitReport($from, $to, $options));
        return $data;
    }

    public function _getProfitReport($from, $to, $options = array()) {

        $tickets = $this->find('all', $options);

        $data['ticketsCount'] = count($tickets);
        $data['total'] = 0;
        $data['won'] = 0;
        $data['pending'] = 0;
        foreach ($tickets as $ticket) {
            if ($ticket['Ticket']['status'] != CANCELED) { //nott canceled
                $data['total'] += $ticket['Ticket']['amount'];
            }
            if ($ticket['Ticket']['status'] == 0) {
                $data['pending'] += $ticket['Ticket']['amount'];
            } else if ($ticket['Ticket']['status'] == 1) {
                $data['won'] += $ticket['Ticket']['return'];
            } else if ($ticket['Ticket']['status'] == -1) {
                
            }
        }
        $data['profit'] = $data['total'] - $data['pending'] - $data['won'];
        unset($data['pending']);

        return $data;
    }

}

?>
