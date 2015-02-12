<?php

class Event extends AppModel {

    public $name = 'Event';
    public $actsAs = array('Containable');
    public $belongsTo = array('League');
    public $hasMany = array('Bet');
 
	function getBets($id, $all = 1) {
        $options['conditions'] = array(
            'Event.id' => $id
        );
        $this->contain('Bet');
        $event = $this->find('first', $options);
        $bets = array();
        if (empty($event)) {
            return array();
        } 
        foreach ($event['Bet'] as $bet) {
            if ($all == 0) {
                if ($this->Bet->hasTickets($bet['id'])) {
                    $bets[] = $this->Bet->getBetParts($bet['id']);
                }
            } else {
                $bets[] = $this->Bet->getBetParts($bet['id']);
            }
        }
        return $bets;
    }

    public function getEventByBetId($betId) { 
        $options['conditions'] = array(
            'Bet.id' => $betId
        );
        $data = $this->find('first', $options);
        return $data;
    } 
    
    function getLeaguesIds($eventsIds, $pending = false) {

        $options['conditions'] = array(
            'Event.id' => $eventsIds
        );
        if (!$pending) {
            $options['conditions']['Event.date <'] = $this->getSqlDate();
        }
        $options['fields'] = array(
            'Event.id',
            'Event.league_id'
        );
        $options['group'] = 'Event.league_id';
        return $this->find('list', $options);
    }

    function findEvents($name) {
        $options['conditions'] = array(
            'Event.name LIKE' => '%' . $name . '%',
            'Event.date >' => $this->getSqlDate(),
            'Event.feed_type' => Configure::read('Settings.feedType')
        );
        $options['order'] = 'Event.date ASC';
        $this->contain('Bet');
        $events = $this->find('all', $options);
        return $events;
    }

    function getEvent($id) {
        $options['conditions'] = array(
            'Event.id' => $id,
            'Event.date >' => $this->getSqlDate(),
            'Event.feed_type' => Configure::read('Settings.feedType')
        );
        $options['order'] = 'Event.date ASC';
        $this->contain('Bet');
        $events = $this->find('all', $options);
        return $events;
    }

   function getUpcomingEvents($leagueId, $betType = null, $start = null, $end = null) {
        if (!isset($start)) {
            $start = $this->getSqlDate();
        }
        $data = $this->League->getItem($leagueId);
        $options['limit'] = 100;
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Event.league_id' => $leagueId
        );
        if (isset($end)) {
            $options['conditions']['Event.date BETWEEN ? AND ?'] = array($start, $end);
        } else {
            $options['conditions']['Event.date >'] = $start;
        }

        $options['order'] = 'Event.date ASC';
        $events = $this->find('all', $options);
        $eventsData = array();
        foreach ($events as $eventKey => $event) {
            $bets = $this->Bet->getBets($event['Event']['id'], $betType);   
            $betsData = array();
            foreach ($bets as $betKey => $bet) 
            {
                $betParts = $this->Bet->BetPart->getBetParts($bet['Bet']['id']);
                $betsData[$bet['Bet']['type']] = $bet['Bet'];
                $betsData[$bet['Bet']['type']]['BetPart'] = $betParts;
                //$bets[$betKey]['BetPart'] = $betParts;
            }
            //$events[$eventKey]['Bet'] = $bets;
            $eventsData[$event['Event']['id']]['Event'] = $event['Event'];
            $eventsData[$event['Event']['id']]['Bet'] = $betsData;
            
        }


        $data['Event'] = $eventsData;

        return $data;

        $options['fields'] = array(
            'Event.*',
            'Bet.*',
            'BetPart.odd'
        );

        //    $this->contain('Bet');
        $options['joins'] = array(
            array(
                'table' => 'bets',
                'alias' => 'Bet',
                'type' => 'INNER',
                'conditions' => 'Bet.event_id = Event.id'
            ),
            array(
                'table' => 'bet_parts',
                'alias' => 'BetPart',
                'type' => 'INNER',
                'conditions' => 'BetPart.bet_id = Bet.id'
            )
        );

        //debug($data);
        $events = array();
        foreach ($data as $row) {
            $events[$row['Event']['id']]['Event'] = $row['Event'];
            $events[$row['Event']['id']]['Bet'][] = $row['Bet'];
        }
        //debug($events);
        $events = $this->__getBetParts($events);

        $data['Event'] = $events;

        return $data;
    }

    function __getBetParts($events) {
        foreach ($events as $eventKey => $event) {
            foreach ($event['Bet'] as $betKey => $bet) {                

                $options['recursive'] = -1;
                $options['conditions'] = array(
                    'BetPart.bet_id' => $bet['id'],
                    'BetPart.odd >' => 1
                );
                
                $betParts = $this->Bet->BetPart->find('all', $options);

                if (!empty($betParts)) {
                    $events[$eventKey]['Bet'][$bet['type']] = $bet;
                    $events[$eventKey]['Bet'][$bet['type']]['BetPart'] = $betParts;
                }
                unset($events[$eventKey]['Bet'][$betKey]);
            }
            if (empty($events[$eventKey]['Bet'])) {
                unset($events[$eventKey]);
            }
        }                
        return $events;
    }
    
    function getUpcomingEventsTill($sportId, $start, $end, $betType = null) {
        $sport = $this->League->Sport->getItem($sportId);        
        $leagues = $this->League->getActiveLeaguesIds($sportId, $start, $end);
        //debug($leagues);die;
        foreach ($leagues as $leagueId) {            
            $sport['League'][] = $this->getUpcomingEvents($leagueId, $betType, $start, $end);
        }
        return array($sport);
    }

    function setResult($id, $result = '') {
        $options['conditions'] = array(
            'Event.id' => $id
        );
        $data = $this->find('first', $options);
        $data['Event']['result'] = $result;
        $this->save($data);
    }

    function getAdd() {
        $fields = array(
            'Event.name',
            'Event.date',
            'Event.active'
        );
        $fields = $this->getBelongings($fields);
        return $fields;
    }

    function getEdit() {
        $fields = array(
            'Event.name',
            'Event.date',
            'Event.active'
        );
        $fields = $this->getBelongings($fields);
        return $fields;
    }

    function deleteEvents($leagueId) {
        $options['conditions'] = array(
            'League.sport_id' => $sportId
        );
        $leagues = $this->find('all', $options);
        foreach ($leagues as $league) {
            $this->Event->deleteEvents($league['league']['id']);
            //delete league
        }
    }

	function allEvents() {
        $options['conditions'] = array(
           'Event.active' => 1
        );
       $events = $this->find('all', $options);
       return $events;
    }

	function allleagueEvents($leagueId=NULL, $type=NULL) {
		
		$data = array();
		$dateCondition = '';

		$startDate = date('Y-m-d');
		if(isset($type) && $type== 'daily') {
			$dateCondition = " and DATE(e.date) = DATE(NOW()) ";
		}
		if(isset($type) && $type== 'weekly') {
			$endDate = date("Y-m-d", strtotime("".$startDate." +7 days"));
			$dateCondition = " and ( e.date between '".$startDate."' and '".$endDate."') ";
		}
		$sql ="SELECT e.*,l.name as league_name from events as e,leagues AS l WHERE e.active =1 AND l.active =1 AND l.id = e.league_id AND e.league_id='".$leagueId."' ".$dateCondition." ORDER BY date DESC";	
		return $data = $this->query($sql);
    }

    function getLastMinuteEvents() {
        $options['conditions'] = array(
            'Event.date >' => $this->getSqlDate(),
            'Event.feed_type' => Configure::read('Settings.feedType')
        );
        $options['order'] = 'Event.date ASC';
        $options['limit'] = '10';
        $options['group'] = 'Event.league_id';
        $this->contain('League');
        return $this->find('all', $options);
    }

    function countLastMinuteEvents($event) {
        $options['conditions'] = array(
            'Event.date' => $event['date'],
            'Event.league_id' => $event['league_id']
        );
        return $this->find('count', $options);
    }

	//Function created by praveen singh on 13-09-2013
	function upcommingEvent(){

		$data = array();
		$startDate = date('Y-m-d');
		$endDate   = date("Y-m-d", strtotime("".$startDate." +15 days"));
		
		$data = $this->query("select e.league_id, l.name as league_name, s.name as sport_name, l.sport_id,e.id as event_id,e.name as event_name,e.date as event_date FROM events as e, leagues as l, sports as s where e.active = 1 and l.active = 1 and s.active = 1 and l.id = e.league_id and s.id = l.sport_id AND ( e.date between '".$startDate."' and '".$endDate."' ) AND (e.result = '' or e.result = 'NULL') order by event_date ASC ");
		return $data;
	}

	//Function created by praveen singh on 13-09-2013
	function mostplayedMatch(){

		$data = array();
		$data = $this->query("select count(*) as totalbets, b.id as bet_id,bp.name as bet_part_name, bp.odd as bet_part_odd, e.league_id, l.name as league_name, s.name as sport_name, l.sport_id,e.id as event_id,e.name as event_name,e.date as event_date FROM bet_parts as bp,bets as b, events as e, leagues as l, sports as s where e.active = 1 and l.active = 1 and s.active = 1 and b.event_id = e.id and l.id = e.league_id and s.id = l.sport_id and b.id = bp.bet_id and e.date >= DATE(NOW()) AND (e.result = '' or e.result = 'NULL') group by e.league_id order by totalbets DESC");
		return $data;
	}
	
	//Function created by praveen singh on 13-09-2013
	function latestresultMatch(){

		$latestdata = array();
		$startDate  = date("Y-m-d H:i:s");
		$latestdata = $this->query("SELECT e.league_id, l.name AS league_name, s.name AS sport_name, l.sport_id, e.id AS event_id, e.name AS event_name, e.date AS event_date, e.result AS event_result FROM events AS e, leagues AS l, sports AS s	WHERE e.active =1 AND l.active =1 AND s.active =1 AND l.id = e.league_id AND s.id = l.sport_id AND e.result != '' AND e.result != 'NULL' AND e.result != 'Canceled' and e.date <= '".$startDate."' ORDER BY e.date DESC");
		return $latestdata;
	}
	
}

?>
