<?php
/******************************************
* @Created on Sept 18, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class AgentsController extends AppController {
	
	public $name = 'Agents';
	public $uses = array('Agent','User','Deposit','Sport','League','Event');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_list', 'admin_request','admin_action','admin_print_leagues','get_leagues_ajax','get_leagues_event_ajax','admin_league_schedulepdf'));
    }

	public function admin_list(){

		$userId     = $this->Auth->user('id');
		$userDetail = $this->Auth->user(null);
		$this->groupid = $this->Session->read('Auth.User.group_id');
		
		if(isset($this->groupid) && $this->groupid == '8') {
			$this->paginate['conditions'] = array(
				'Agent.sender_id' => $userId
			);
		} else {
			$this->paginate['conditions'] = array(
				'Agent.recevier_id' => $userId
			);
		}	

        $this->paginate['limit'] = Configure::read('Settings.itemsPerPage');;
        $this->paginate['order'] = 'Agent.date DESC';
        $data = $this->paginate('Agent');

		foreach ($data as $key => $rowData){

			$senderDetail   = $this->User->getItem($rowData['Agent']['sender_id']);
			$recevierDetail = $this->User->getItem($rowData['Agent']['recevier_id']);

			if(isset($senderDetail) && isset($senderDetail)){
				$data[$key]['Agent']['sender_name']   = $senderDetail['User']['username'];
				$data[$key]['Agent']['receiver_name'] = $recevierDetail['User']['username'];
			}
		}
		
		$this->set('userdetail', $userDetail);		
        $this->set('data', $data);
		$this->render('admin_list');		
	}

	public function admin_request(){		

		$userId = $this->Auth->user('id');	
		$userDetail = $this->Auth->user(null);

		if (!empty($this->request->data)) {

			$amount			= $this->request->data['Agent']['amount'];
			$sender_id		= $this->request->data['Agent']['sender_id'];
			$recevier_id	= $this->request->data['Agent']['recevier_id'];
			$status			= $this->request->data['Agent']['status'];			

			if($amount== ''){

				$this->__setError(__('Please enter credit amount', true));

			} else if(!is_numeric($amount)){

				$this->__setError(__('Please enter valid credit amount', true));

			} else {

				$datasave['Agent']['amount']		= $amount;
				$datasave['Agent']['sender_id']		= $sender_id;
				$datasave['Agent']['recevier_id']	= $recevier_id;
				$datasave['Agent']['status']		= $status;
				$datasave['Agent']['date']			= date('Y-m-d H:i:s');

				if ($this->Agent->save($datasave)) {

					$requistId = uniqid($sender_id . '-');
					$requistType = 'Credit request by agent ('.$userDetail['username'].') of agent_ID ('.$userDetail['id'].')';
					
					$this->Deposit->saveDeposit($sender_id, $amount, $requistType, $requistId, $requistType, 'Pending');
					
					$this->__setMessage(__('Credit request has been sent to the Admin', true));

				} else {
					$this->__setError(__('Due to some error request credits could not be sent', true));
				}

				$this->redirect(array(
					'controller' => 'agents',
					'action' => 'list'
				));
			}

		}
		
		$this->set('userDetail', $userDetail);
		$this->render('admin_request');
	}

	public function admin_action($id, $action){

		$userId     = $this->Auth->user('id');	
		$userDetail = $this->Auth->user(null);

		$performed = 0;

		if (is_null($id) && is_null($action)) {
            throw new NotFoundException(__l('Invalid request'));
        }

		$creditDetail = $this->Agent->find('first', array('conditions' => array('Agent.id' => $id)));

		if(!empty($creditDetail)) {		

			$amount			= $creditDetail['Agent']['amount'];
			$sender_id		= $creditDetail['Agent']['sender_id'];
			$recevier_id	= $creditDetail['Agent']['recevier_id'];
			$date			= date('Y-m-d H:i:s');
			
			switch($action){

				case 'accept':	
					
					//Updating agents Table
					$this->Agent->query("update agents set status = '1' where id = '".$id."' ");
						
					//Updating Balance of Agent User
					$this->User->query("update users set balance = '".$amount."' where id = '".$sender_id."' ");

					//Updating deposits table
					$requistId = uniqid($recevier_id . '-');
					$requistType = 'Credit request accepted by Administrator ('.$userDetail['username'].') of admin_ID ('.$userDetail['id'].')';						
					$this->Deposit->saveDeposit($recevier_id, $amount, $requistType, $requistId, $requistType, 'accepted');		
					
					$this->__setMessage(__('Credits request has been accepted', true));						
					break;
				case 'reject':

					//Updating agents Table
					$this->Agent->query("update agents set status = '2' where id = '".$id."' ");

					//Updating deposits table
					$requistId = uniqid($recevier_id . '-');
					$requistType = 'Credit request rejected by Administrator ('.$userDetail['username'].') of admin_ID ('.$userDetail['id'].')';						
					$this->Deposit->saveDeposit($recevier_id, $amount, $requistType, $requistId, $requistType, 'rejected');	
						
					$this->__setMessage(__('Credits request has been rejected', true));				
					break;
				case 'delete':

					$performed = $this->Agent->delete($id);
					$this->__setMessage(__('Entry deleted successfully', true));
					break;
			}
			
			$this->redirect(array(
				'controller' => 'agents',
				'action' => 'list'
			));			

		} else {

			$this->__setMessage(__('Credit request is no more', true));
			$this->redirect(array(
				'controller' => 'agents',
				'action' => 'list'
			));
		}
	}

	public function get_leagues_ajax(){

		//selecting all league
		$leagueOption = array();
		$sport_id     = $_POST['sport_id'];
		
		$leagueData   = $this->League->getActiveLeagues($sport_id);		
		if(!empty($leagueData)){		
			foreach ($leagueData as $leagueKey => $league) {
				$leagueOption[$league['League']['id']]=$league['League']['name'];
			}
			$this->set('leagueOption',$leagueOption);		
		}		
	}

	public function get_leagues_event_ajax(){

		$eventDataAll        = $downloadData = array();
		$downloadCountAll    = $downloadweaklyCount = $downloadDailyCount = 0;

		$sportid           = $this->request->data['Agent']['sport_id'];
		$leagueid          = $this->request->data['league_id'];
		$eventData         = $this->League->getalleventnameLeague($sportid,$leagueid);

		if(!empty($eventData)){		
			foreach ($eventData as $eventKey => $event) {				
				$downloadData[$event['e']['league_id']]['league_name']  = $event['l']['league_name'];
				$downloadData[$event['e']['league_id']]['sport_name']   = $event['s']['sport_name'];
				$downloadData[$event['e']['league_id']]['eventcount']   = $event['0']['eventcount'];		
				$downloadData[$event['e']['league_id']]['league_id']    = $event['e']['league_id'];	
				$downloadData[$event['e']['league_id']]['sport_id']     = $event['l']['sport_id'];
				$downloadCountAll										= $event['0']['eventcount'];		
			}
			$this->set('downloadData',$downloadData);
			$this->set('downloadCountAll',$downloadCountAll);
		}

		//daily event
		$eventDataDaily    = $this->League->getalleventnameleagueCount($leagueid,1);
		if(!empty($eventDataDaily)){		
			foreach ($eventDataDaily as $eventKeyd => $eventd) {				
				$downloadDailyCount   = $eventd['0']['eventcount'];		
			}
			$this->set('downloadDailyCount',$downloadDailyCount);
		}

		//Weakly event
		$eventDataWeakly   = $this->League->getalleventnameleagueCount($leagueid,2);
		if(!empty($eventDataWeakly)){		
			foreach ($eventDataWeakly as $eventwKey => $eventw) {				
				$downloadweaklyCount   = $eventw['0']['eventcount'];		
			}
			$this->set('downloadweaklyCount',$downloadweaklyCount);
		}

	}

	public function admin_print_leagues(){		

		$sportOption = array('0' => 'Select Sport name');
		//selecting all Sports
		$sports = $this->Sport->getSports();
		if(!empty($sports)){		
			foreach ($sports as $sportsKey => $sport) {
				$sportOption[$sport['Sport']['id']]=$sport['Sport']['name'];
			}
			$this->set('sportOption',$sportOption);		
		}
	}

	function admin_league_schedulepdf($leagueid = null,$type = 'all')
    {
        if (!$leagueid)
        {
            $this->Session->setFlash('Sorry, there was no property ID submitted.');
			$this->redirect(array('controller' => 'agents','action' => 'print_leagues'));
        }

		ob_start();		
		include($_SERVER['DOCUMENT_ROOT'].'/app/webroot/View/Agents/downloadData.php');		
		$content = ob_get_clean();
		
		// convert in PDF
		require_once($_SERVER['DOCUMENT_ROOT'].'/app/Vendor/htmltopdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P', 'A4', 'en');
			//$html2pdf->setModeDebug();
			$html2pdf->setDefaultFont('Arial');
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			//$html2pdf->Output(''.$file_name.'.pdf');
			$html2pdf->Output(''.$file_name.'.pdf','d');

		} catch(HTML2PDF_exception $e) {

			echo $e;
			exit;
		}
		exit;
    } 

}
?>