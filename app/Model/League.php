<?php

class League extends AppModel {

    public $name = 'League';
    public $actsAs = array('Containable');
    public $belongsTo = array('Sport');
    public $hasMany = array('Event');

    function getIndex() {
        $options['fields'] = array(
            'League.id',
            'League.name',
            'League.active',
            'League.sport_id'
        );
        return $options;
    }
    

    function getSportsIds($leaguesIds) {
        $options['conditions'] = array(
            'League.id' => $leaguesIds
        );
        $options['fields'] = array(
            'League.id',
            'League.sport_id'
        );
        $options['group'] = 'League.sport_id';
        return $this->find('list', $options);
    }

    function deleteLeagues($sportId) {
        $options['conditions'] = array(
            'League.sport_id' => $sportId
        );
        $leagues = $this->find('all', $options);
        foreach ($leagues as $league) {
            $this->Event->deleteEvents($league['league']['id']);
            //delete league
        }
    }

    function getActions() {
        $actions = array();
        $actions[] = array('name' => __('View', true), 'action' => 'view', 'controller' => NULL);
        $actions[] = array('name' => __('Edit', true), 'action' => 'edit', 'controller' => NULL);
        $actions[] = array('name' => __('Add Event', true), 'action' => 'add', 'controller' => 'events');
        return $actions;
    }

    function isActive($league) {
        if ($league['active'] != 1)
            return false;
        $options['conditions'] = array(
            'Event.league_id' => $league['id'],
            'Event.active' => 1,
            'Event.date >' => $this->getSqlDate()
        );
        $this->Event->contain('League');
        $event = $this->Event->find('first', $options);

        if (empty($event))
            return false;
        return true;
    }

    public function getActiveLeaguesIds($sportId, $start, $end) {
        $options['recursive'] = -1;
        $options['fields'] = array(
            'League.id'
        );
        $options['conditions'] = array(
            'League.sport_id' => $sportId,
            'Event.date BETWEEN ? AND ?' => array($start, $end)
        );
        $options['joins'] = array(
            array(
                'table' => 'events',
                'alias' => 'Event',
                'type' => 'inner',
                'conditions' => 'League.id = Event.league_id'
            )
        );
        $options['group'] = 'League.id';
        $options['limit'] = 30;
        $data = $this->find('list', $options);                
        return $data;
    }

    function getActiveLeagues($sportId) {
        $options['conditions'] = array(
            'League.sport_id' => $sportId
        );
        $options['order'] = 'League.name ASC';
        $options['recursive'] = -1;
        return $this->find('all', $options);
    }

    function updateRisk($leagues) {
        $data = array();
        foreach ($leagues['League'] as $key => $value) {
            $value['id'] = $key;
            $data[]['League'] = $value;
        }
        return $this->saveAll($data);
    }

    function getLastMinuteBets() {
        $events = $this->Event->getLastMinuteEvents();
        foreach ($events as $key => $value) {
            $sport = $this->Sport->getItem($value['League']['sport_id']);
            $events[$key]['Sport'] = $sport['Sport'];
            $events[$key]['Event']['count'] = $this->Event->countLastMinuteEvents($value['Event']);
        }
        return $events;
    }

    function getIdIdList() {
        $options['fields'] = array(
            'League.id',
            'League.id'
        );
        return $this->find('list', $options);
    }

    function getList() {
        $options['fields'] = array(
            'League.id',
            'League.name'
        );
        return $this->find('list', $options);
    }

	//Function created by praveen singh on 13-09-2013
	function getalleventnameleagueCount($league_id,$type=0){

		$data = array();		
		$startDate = date('Y-m-d');
		if(isset($type) && $type == '1') {
			$sql ="SELECT count(*) as eventcount from `events` WHERE `active` =1 and `league_id` ='".$league_id."' AND `date` = '".$startDate."' ";			
			$data = $this->query($sql);
		} else if(isset($type) && $type== '2') {
			$endDate = date("Y-m-d", strtotime("".$startDate." +7 days"));
			$sql ="SELECT count(*) as eventcount from `events` WHERE `active` = 1 and `league_id` ='".$league_id."' AND ( `date` between '".$startDate."' and '".$endDate."' )";			
			$data = $this->query($sql);
		}		
		return $data;
	}

	//Function created by praveen singh on 13-09-2013
	function getalleventnameLeague($sport_id,$league_id){
		$data = array();
		$data = $this->query("SELECT count(*) as eventcount,e.name as event_name,e.date AS event_date,l.name as league_name,e.league_id as league_id,s.name as sport_name,l.sport_id as sport_id from events as e ,leagues AS l, sports AS s WHERE e.active =1 AND l.active =1 AND s.active =1 AND l.id = e.league_id AND s.id = l.sport_id AND s.id= '".$sport_id."' and e.league_id='".$league_id."' GROUP BY e.league_id ORDER BY event_date");
		return $data;
	}

}

?>
