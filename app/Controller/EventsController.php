<?php

class EventsController extends AppController {

    public $name = 'Events';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('search','admin_list','get_eventupcomming','view', 'get_mostplayedmatch', 'get_latestresult','get_latestwins','admin_view'));
    }

    function admin_results($eventId) {
        if (isset($eventId)) {
            $events = $this->Event->find('all');
        } else {
            $events = $this->Event->find('all');
        }
        $this->set('events', $events);
    }

	function admin_list() {
		if (!empty($this->request->data)) {
			$this->Session->delete('eventdata_posted');
			$this->Session->write('eventdata_posted',$this->request->data);
		}		
        if($this->Session->read('dashboard_type') && $this->Session->read('dashboard_type')=='admin_lottery'){
		   $this->Session->write('dashboard_type','admin');
		   $this->redirect(array('action' => 'admin_list'));
		   exit;
		}
        $model = $this->__getModel();
        if ($this->Session->write('eventdata_posted')) {
			$eventdata_posted = $this->Session->write('eventdata_posted');
            $conditions = $this->{$model}->getSearchConditions($eventdata_posted);
            $this->admin_index($conditions);
            $this->view = 'admin_index';
            //$this->render('admin_index');
            return;
        }
        $fields = $this->{$model}->getSearch();
        $this->set('fields', $fields);
    }

 
    function admin_view($id = null) {

		//checking session for publick method
		parent::checkSession();

		$event_id = (isset($this->request->data['Event']['id']))?trim($this->request->data['Event']['id']):$id;
        if($this->Session->read('dashboard_type') && $this->Session->read('dashboard_type')=='admin_lottery'){
		   $this->Session->write('dashboard_type','admin');
		   $this->redirect(array('action' => 'admin_view',$event_id));
		   exit;
		}
		$event = $this->Event->getItem($event_id);        
		if(!empty($event)){     
			$data = $this->Event->getBets($event_id);
            $this->set('event', $event);
            $this->set('data', $data);
        } else {
			$this->__setError(__('can\'t  find event with id: ', true) . $event_id);            
        }
        $this->set('tabs', $this->Event->getTabs($this->request->params));
    }	

    function admin_add($id = NULL) {
        parent::admin_add($id);
        $fields = $this->Event->getAdd();
        if ($id == NULL) {
            $leagues = $this->Event->League->getList();
            $fields['Event.league_id'] = array(
                'type' => 'select',
                'options' => $leagues
            );
        }
        $this->set('fields', $fields);
    }

	function view($id = null) {
        if (empty($this->request->data['Event']['id'])) {
            $event = $this->Event->getItem($id);
            $data = $this->Event->getBets($id);
            $this->set('event', $event);
            $this->set('data', $data);
        } else {
            //its search
            $bet = $this->Event->Bet->getItem($this->request->data['Event']['id'], 0);
            if (!empty($bet)) {
                $event['Event'] = $bet['Event'];
                $data = $this->Event->getBets($event['Event']['id']);
                $this->set('event', $event);
                $this->set('data', $data);
            }
        }
        if (!isset($this->request->params['pass'][0])) {
            if (isset($event['Event']['id'])) {
                $this->request->params['pass'][0] = $event['Event']['id'];
            } else {
                $this->request->params['pass'][0] = 0;
            }
        }
        $this->set('tabs', $this->Event->getTabs($this->request->params));
    }

    function search() {

        if (!isset($this->request->data)) {
            $this->redirect('/');
        }

        $events = array();
        if (isset($this->request->data['Event']['id'])) {
            $id = true;            
            $bet = $this->Event->Bet->getItem($this->request->data['Event']['id'], 0);
            if (isset($bet['Event'])) {
                $events = $events = $this->Event->getEvent($bet['Event']['id']);
            }
        }
        if ((isset($this->request->data['Event']['name']) && ($this->request->data['Event']['name'] != __('Event name', true)) && (empty($events)))) {
            $events = $this->Event->findEvents($this->request->data['Event']['name']);
            $id = false;
        }
        $this->set('id', $id);
        if ((empty($events)) && ($id)) {
            $this->__setError('Incorect event ID or event with this ID has already finished');
        } else {
            $this->__setError('No events found');
        }

        foreach ($events as $eventKey => $event) {
            foreach ($event['Bet'] as $betKey => $bet) {
                $this->Event->Bet->BetPart->contain();

                $betParts = $this->Event->Bet->BetPart->find('all', array('conditions' => array('BetPart.bet_id' => $bet['id'])));

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


        $this->set(compact('events'));
    }

    //Used as element action and added by praveen singh on 11-09-2013
	function get_eventupcomming() {

		if (empty($this->request->params['requested'])) {
            throw new ForbiddenException();
        }

		$eventData = $data = $dataInfosArray = array();		
	    $events = $this->Event->upcommingEvent();

		if(!empty($events)){
		
			foreach ($events as $eventKey => $event) {

				$data[$event['l']['sport_id']][$event['e']['event_id']]['totalbets']    = $event['0']['totalbets'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['bet_id']       = $event['b']['bet_id'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['league_id']    = $event['e']['league_id'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['league_name']  = $event['l']['league_name'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['sport_name']   = $event['s']['sport_name'];  
				$data[$event['l']['sport_id']][$event['e']['event_id']]['event_id']     = $event['e']['event_id']; 
				$data[$event['l']['sport_id']][$event['e']['event_id']]['event_name']   = $event['e']['event_name'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['event_date']   = $event['e']['event_date'];
				$betArray = $this->Event->query("select id from bets where event_id='".$event['e']['event_id']."'");
				if(!empty($betArray)){		
					foreach ($betArray as $betKey => $bets) {
						$betPartsArray = $this->Event->query("select * from bet_parts where bet_id='".$bets['bets']['id']."' order by odd ");			
						foreach ($betPartsArray as $betPartsKey => $betParts) {						
							$data[$event['l']['sport_id']][$event['e']['event_id']]['bet_part_odd_up'][$betParts['bet_parts']['id']]  = $betParts['bet_parts']['odd'];
						}
					}
				} else {
					$data[$event['l']['sport_id']][$event['e']['event_id']]['bet_part_odd_up'][]  = 0;
				}
			}
		}	

		if(!empty($data)){			
			foreach($data as $sportkey =>$dataAll){
				foreach($dataAll as $eventkey => $datas) {
					$start_date  = strtotime(date("Y-m-d H:i:s"));					
					$event_date  = strtotime(date('Y-m-d H:i:s',strtotime($datas['event_date'])));			
					$remaining   = ceil(($event_date - $start_date) / 60);
					if(isset($remaining) && $remaining > 5) {
						$dataInfosArray[$sportkey][$eventkey] = $datas;
					}
				}	
			}
		}

		if(!empty($dataInfosArray)){	

			$dataEventsOut = array_slice($dataInfosArray, 0 ,4,true);	
			foreach($dataEventsOut as $dataEventsOutkey =>$dataEventsOutkeyAll){
				$datamEventsOutAll[$dataEventsOutkey] = array_slice($dataEventsOutkeyAll, 0,5);		
			}
			foreach($datamEventsOutAll as $dataEventsOutAllkey =>$dataEventsmOutallVal){	
				$eventData['navigation'][$dataEventsOutAllkey] = $dataEventsmOutallVal[0]['sport_name'];	
				$eventData['events'][$dataEventsOutAllkey] = $dataEventsmOutallVal;
			}
			$this->set('eventData',$eventData);
		}		
    }

	//Used as element action and added by praveen singh on 11-09-2013
	function get_mostplayedmatch() {

		if (empty($this->request->params['requested'])) {
            throw new ForbiddenException();
        }
		
		$dataInfosArray = $mostplayedData = $data = $dataInfos = array();		
	    $events = $this->Event->mostplayedMatch();	

		if(!empty($events)){
		
			foreach ($events as $eventKey => $event) {

				$data[$event['l']['sport_id']][$event['e']['event_id']]['totalbets']    = $event['0']['totalbets'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['bet_id']       = $event['b']['bet_id'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['league_id']    = $event['e']['league_id'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['league_name']  = $event['l']['league_name'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['sport_name']   = $event['s']['sport_name'];  
				$data[$event['l']['sport_id']][$event['e']['event_id']]['event_id']     = $event['e']['event_id']; 
				$data[$event['l']['sport_id']][$event['e']['event_id']]['event_name']   = $event['e']['event_name']; 
				$data[$event['l']['sport_id']][$event['e']['event_id']]['event_date']   = $event['e']['event_date'];
				$data[$event['l']['sport_id']][$event['e']['event_id']]['event_date']   = $event['e']['event_date'];
				$betArray = $this->Event->query("select id from bets where event_id='".$event['e']['event_id']."'");
				if(!empty($betArray)){		
					foreach ($betArray as $betKey => $bets) {
						$betPartsArray = $this->Event->query("select * from bet_parts where bet_id='".$bets['bets']['id']."' order by odd ");			
						foreach ($betPartsArray as $betPartsKey => $betParts) {						
							$data[$event['l']['sport_id']][$event['e']['event_id']]['bet_part_odd_most'][$betParts['bet_parts']['id']]  = $betParts['bet_parts']['odd'];
						}
					}
				} else {
					$data[$event['l']['sport_id']][$event['e']['event_id']]['bet_part_odd_most'][]  = 0;
				}
			}
		}
		
		if(!empty($data)){					
		    $dataEventsOut = array_slice($data, 0 ,4,true);	
			foreach($dataEventsOut as $dataEventsOutkey =>$dataEventsOutkeyAll){
				$dataEventsOutAll[$dataEventsOutkey] = array_slice($dataEventsOutkeyAll, 0 ,5);		
			}
			foreach($dataEventsOutAll as $dataEventsOutAllkey =>$dataEventsOutallVal){						
				$mostplayedData['navigation'][$dataEventsOutAllkey] = $dataEventsOutallVal[0]['sport_name'];	
				$mostplayedData['mostplayedbet'][$dataEventsOutAllkey] = $dataEventsOutallVal;
			}
			$this->set('mostplayedData',$mostplayedData);
		}
    }

	//Used as element action and added by praveen singh on 11-09-2013
	function get_latestresult() {
		
		if (empty($this->request->params['requested'])) {
            throw new ForbiddenException();
        }
		
		$getlatestResultData = $dataInfoslArrayMain = $datalInfosArray = $latestresult = $datal = $dataInfosl = array();	
	    $latestresult   = $this->Event->latestresultMatch();

		if(!empty($latestresult)){		
			foreach ($latestresult as $eventKey => $latestR) {

				$dataInfoslArrayMain[$latestR['l']['sport_id']][$latestR['e']['event_id']]['sport_id']    = $latestR['l']['sport_id'];
				$dataInfoslArrayMain[$latestR['l']['sport_id']][$latestR['e']['event_id']]['sport_name']   = $latestR['s']['sport_name'];				
				$dataInfoslArrayMain[$latestR['l']['sport_id']][$latestR['e']['event_id']]['league_id']    = $latestR['e']['league_id'];
				$dataInfoslArrayMain[$latestR['l']['sport_id']][$latestR['e']['event_id']]['league_name']  = $latestR['l']['league_name'];				  
				$dataInfoslArrayMain[$latestR['l']['sport_id']][$latestR['e']['event_id']]['event_id']     = $latestR['e']['event_id']; 
				$dataInfoslArrayMain[$latestR['l']['sport_id']][$latestR['e']['event_id']]['event_name']   = $latestR['e']['event_name']; 
				$dataInfoslArrayMain[$latestR['l']['sport_id']][$latestR['e']['event_id']]['event_date']   = $latestR['e']['event_date'];
				$dataInfoslArrayMain[$latestR['l']['sport_id']][$latestR['e']['event_id']]['event_result']   = $latestR['e']['event_result'];		
			}
		}
		
		if(!empty($dataInfoslArrayMain)){					
		    $dataEventsOut = array_slice($dataInfoslArrayMain, 0 ,4,true);	
			foreach($dataEventsOut as $dataEventsOutkey =>$dataEventsOutkeyAll){
				$dataEventsOutAll[$dataEventsOutkey] = array_slice($dataEventsOutkeyAll, 0 ,5);		
			}
			foreach($dataEventsOutAll as $dataEventsOutAllkey =>$dataEventsOutallVal){						
				$getlatestResultData['navigation_lr'][$dataEventsOutAllkey] = $dataEventsOutallVal[0]['sport_name'];	
				$getlatestResultData['latestresult'][$dataEventsOutAllkey] = $dataEventsOutallVal;
			}
			$this->set('getlatestResultData',$getlatestResultData);
		}
	}

	//Used as element action and added by praveen singh on 11-09-2013
	function get_latestwins() {
		$getlatestResult=1;
		$this->set('getlatestResult',$getlatestResult);
	}

}
?>