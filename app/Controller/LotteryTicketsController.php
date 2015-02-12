<?php
/******************************************
* @Created on Dec 04, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class LotteryTicketsController extends AppController {

    public $name = 'LotteryTickets';
	public $uses = array('LotteryTicket','Lottery','LotteryType','User');

	function beforeFilter() {
        parent::beforeFilter();
		$this->Auth->allow(array('admin_view','admin_tickets','tickets','admin_action','admin_drawlist','admin_lotterydraw','drawlist','search','ticketlists','ticket_print','lotteryheader','ticket','view','admin_ticketrequest','tickets_request','admin_ticket','admin_ticket_print','admin_lotteryheader','admin_tktreqlist','payout','admin_winnner','admin_winview','ticketrequest','tktreqlist','random_ticket','admin_random_ticket','request_ticket','admin_ticketapprove','admin_generateticket','admin_search','admin_autolotterydraw'));
    }

	//Global function *****************************************************
	function __action_delete($contentid){
		if($this->LotteryTicket->delete($contentid)) {
			return true;
		} else {
			return false;
		}
	}
	//Global function *****************************************************

	//ADMIN SECTION FUNCTION ***********************************************

	function admin_ticketapprove($ticket_id=null, $action=null){

		//checking session for publick method
		parent::checkSession();

		if(isset($action) && $action !=''){
			$coloumStr = "";
			if($action == 'approve'){
			   $DMLTtype  = 'UPDATE ';
			   $coloumStr = " SET `status` = '0' ";
			   $message   = 'Ticket has been approved succussfully.';
			} else if($action == 'disapprove'){
			   $DMLTtype  = 'UPDATE ';
			   $coloumStr = " SET `status` = '6' ";
			   $message   = 'Ticket has been disapproved succussfully.';
			} else if($action == 'cancel'){
			   $DMLTtype  = 'UPDATE ';
			   $coloumStr = " SET `status` = '3' ";
			   $message   = 'Ticket has been cancel succussfully.';
			} else if($action == 'delete'){
			   $DMLTtype  = 'DELETE FROM ';
			   $coloumStr = "";
			   $message   = 'Ticket has been deleted succussfully.';
			}
			$returnActionUpdated = true;
			if($action != 'delete' && $action != 'disapprove' && $action != 'cancel'){
				$options['conditions'] = array('LotteryTicket.id' => $ticket_id);
				$lotteryDetail         = $this->LotteryTicket->find('first', $options);
				$lottery_fee		   = $lotteryDetail['Lottery']['lottery_fee'];			
				//Checking Balance and Taking Lottery Fee by deducting User Balance.
				$user_id      = $this->Session->read('Auth.User.id');
				$user_balance = $this->Session->read('Auth.User.balance');
				if(isset($user_balance) && $user_balance < 0){
					$this->__setError(__('You donot have sufficient balance in your account.', true));
					$this->redirect(array('action' => 'admin_random_ticket',''.$lottery_id.'',$ticketid));
					exit;
				}
				if(!isset($lottery_fee)){
					$this->__setError(__('You donot have sufficient balance in your account.', true));
					$this->redirect(array('action' => 'admin_random_ticket',''.$lottery_id.'',$ticketid));
					exit;
				}	
				//ACTUAL LOTTERY FEE			
				$actual_lottery_fee   = number_format(($user_balance - $lottery_fee),2);
				$returnActionUpdated  = $this->LotteryTicket->saveGlobalData($table_name='users',$coloum_field='balance', $coloum_value=$actual_lottery_fee,$updated_on_field='id',$updated_on_value=$user_id,$otherfields='');			
			}
			$returnActionApprove   = $this->LotteryTicket->approve_action($DMLTtype,$tablename='lottery_tickets',$coloumStr,$updated_field='id',$updated_value=$ticket_id);
			if($returnActionUpdated && $returnActionApprove) {				
				$this->__setMessage(__($message, true));
				$this->redirect(array('action' => 'admin_ticketapprove'));
				exit;
			} else {
				$this->__setError(__('Internal Erorr occurs.Please try again.', true));
			    $this->redirect(array('action' => 'admin_ticketapprove'));
				exit;
			}
		}
		$user_id = $this->Session->read('Auth.User.id');
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.id DESC';
		$this->paginate['conditions'] = array(
			'LotteryTicket.agent_id <>' => '',
			'LotteryTicket.status' => array(5,6),
			'LotteryTicket.draw_date >=' => date("Y-m-d H:i:s")
        );
		$data = $this->paginate('LotteryTicket');
		if(!empty($data)) {
			foreach($data as $key => $dataone){

				$LotteryTypeDetail = $this->LotteryType->getLotteryType($dataone['Lottery']['lottery_type']);
				if(isset($LotteryTypeDetail[0]['LotteryType']['lottery_type'])){
				   $data[$key]['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				}
				$draw_date  = $dataone['LotteryTicket']['draw_date'];
				$lottery_id = $dataone['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1,
					'LotteryTicket.win_ticket <>' => ''
				);
				$winData  = $this->LotteryTicket->find('first', $options);
				if(!empty($winData['LotteryTicket']['win_ticket'])){
					$data[$key]['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
					$data[$key]['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
					$data[$key]['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				} else { 
					$data[$key]['winData']['win_ticket'] = '';
					$data[$key]['winData']['win_date']   = '';
					$data[$key]['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				}
			}
		}
		$this->set('data', $data);
		$this->set('totalitemsPage',count($data));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function admin_generateticket(){

		//checking session for publick method
		parent::checkSession();

		//Currency Setting
		$this->set('currency', Configure::read('Settings.currency'));

		//CODE FOR AJAX REQUEST
		if(isset($this->request->data['lottery_type_id'])){
			$lottery_type_id  = $this->request->data['lottery_type_id'];
			$option['conditions'] = array(
				'Lottery.lottery_type' => $lottery_type_id,
				'Lottery.is_active' => 1
			);		
			$lotteryDetail  = $this->Lottery->find('all', $option);			
			if(!empty($lotteryDetail)){	
				$lotteryOption = array('0' => 'Select Lottery Game');
				foreach ($lotteryDetail as $lotteryKey => $lotterys) {
					$lotteryOption[$lotterys['Lottery']['id']] = $lotterys['Lottery']['name'];
				}
				$this->set('lotteryOption',$lotteryOption);			
			}
			$this->set('lottery_type_id',$lottery_type_id);
		}
		//CODE FOR DISPLAYING ALL LOTTERY TYPE GAME IN A SELECTBOX.
		$lotterytypeOption = array('0' => 'Select Lottery Type');
		$activeLotteryTypes = $this->LotteryType->activeLotteryType();
		if(!empty($activeLotteryTypes)){		
			foreach ($activeLotteryTypes as $lotteryKey => $lotteryTypes) {
				$lotterytypeOption[$lotteryTypes['LotteryType']['id']]=$lotteryTypes['LotteryType']['lottery_type'];
			}
			$this->set('lotterytypeOption',$lotterytypeOption);
		}
	}

	function admin_random_ticket($lottery_id=NULL, $ticket_id=NULL){

		//checking session for publick method
		parent::checkSession();

		$postDataInfo = array();
		//on submitting delete botton of the form
		if(!empty($this->request->data['LotteryTicket']['deleteTicket'])){
			$lottery_id = (isset($this->request->data['LotteryTicket']['lottery_id']))?trim($this->request->data['LotteryTicket']['lottery_id']):'';
			$ticketid = (isset($this->request->data['LotteryTicket']['ticket_id']))?trim($this->request->data['LotteryTicket']['ticket_id']):'';
			$returnAction = $this->__action_delete($ticketid);
			if($returnAction) {
				$this->__setMessage(__('Ticket has been deleted successfully', true));
				$this->redirect(array('action' => 'admin_ticketrequest',''.$lottery_id.'','arequest'));
				exit;
			} else {
				$this->__setError(__('Internal Erorr occurs.Please try again.', true));
			    $this->redirect(array('action' => 'admin_random_ticket',''.$lottery_id.'',$ticketid));
				exit;
			}
		}
		
		//on submitting sumit botton of the form
		if(!empty($this->request->data['LotteryTicket']['submitTicket'])){
			$lottery_id = (isset($this->request->data['LotteryTicket']['lottery_id']))?trim($this->request->data['LotteryTicket']['lottery_id']):'';
			$user_id = (isset($this->request->data['LotteryTicket']['user_id']))?trim($this->request->data['LotteryTicket']['user_id']):'';
			$num_lott_ball = (isset($this->request->data['LotteryTicket']['num_lott_ball']))?trim($this->request->data['LotteryTicket']['num_lott_ball']):'';
			$lottery_fee = (isset($this->request->data['LotteryTicket']['lottery_fee']))?trim($this->request->data['LotteryTicket']['lottery_fee']):'';
			$is_stuff = (isset($this->request->data['LotteryTicket']['is_stuff']))?trim($this->request->data['LotteryTicket']['is_stuff']):'';

			if($lottery_fee=='' && $is_stuff=='' && $num_lott_ball == 0 && $lottery_id==''){
				$this->__setError(__('Internal Erorr occurs.Please try again.', true));
			    $this->redirect(array('action' => 'admin_random_ticket',''.$lottery_id.''));
				exit;
			}
			$ticketUser = $this->User->userData($user_id);
			if(empty($ticketUser)) {
				$this->__setError(__('Please enter the registered user ID.', true));
			    $this->redirect(array('action' => 'admin_random_ticket',''.$lottery_id.''));
				exit;
			}
			//CHECKING BALANCE AND TAKING LOTTERY FEE BY DEDUCTING USER BALANCE.			
			$user_balance  = $this->Session->read('Auth.User.balance');			
			if(isset($user_balance) && $user_balance < 0){
				$this->__setError(__('You donot have sufficient balance in your account.', true));
			    $this->redirect(array('action' => 'admin_random_ticket',''.$lottery_id.''));
				exit;
			}
			//AGENT BALANCE DUDUCTION CALCULATION
			$agent_id           = $this->Session->read('Auth.User.id');
			$actual_lottery_fee = number_format(($user_balance - $lottery_fee),2);

			//Generating Random Ticket
			$range_start		  = 1;
			$range_end			  = 49;
			$random_string_length = $num_lott_ball;
			$randomLotteryNumber  = parent::__randomNumGenerator($range_start,$range_end,$random_string_length);

			if(isset($randomLotteryNumber) && $randomLotteryNumber=='') {
				$this->__setError(__('Internal Erorr occurs.Please try again.', true));
			    $this->redirect(array('action' => 'admin_random_ticket',''.$lottery_id.''));
				exit;
			}
			if(isset($is_stuff) && $is_stuff==1) {
				   $stuff_ball      = end(explode(',',$randomLotteryNumber));
			       $ticketnumberArr = explode(',',$randomLotteryNumber);
			       if(isset($ticketnumberArr))
			       unset($ticketnumberArr[count($ticketnumberArr)-1]);
			       $ticket_id  = implode(',',$ticketnumberArr);
			} else {
				$ticket_id  = $randomLotteryNumber; 
				$stuff_ball = '';
			}
			$datasave['LotteryTicket']['status']      = 0;
			$datasave['LotteryTicket']['user_id']	  = $user_id;
			$datasave['LotteryTicket']['lottery_id']  = $lottery_id;
			$datasave['LotteryTicket']['lottery_fee'] = $lottery_fee;
			$datasave['LotteryTicket']['ticket_id']   = $ticket_id;
			$datasave['LotteryTicket']['stuff_ball']  = $stuff_ball;
			$datasave['LotteryTicket']['added_on']	  = date('Y-m-d H:i:s');
			if($this->LotteryTicket->save($datasave)) {
				$this->LotteryTicket->saveGlobalData($table_name='users',$coloum_field='balance', $coloum_value=$actual_lottery_fee,$updated_on_field='id',$updated_on_value=$agent_id,$otherfields='');
				$insertedID = $this->LotteryTicket->id;
				$this->Session->write('uniqidTicketId', $insertedID);
				$options['conditions'] = array('LotteryTicket.id' => $insertedID);
				$lotteryDetail         = $this->LotteryTicket->find('first', $options);
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lotteryDetail['Lottery']['lottery_type']);
				if(!empty($lotteryDetail) && !empty($LotteryTypeDetail)){
					$lotteryDetail['LotteryTicket']['ticket_id']   = $ticket_id;
					$lotteryDetail['LotteryTicket']['stuff_ball']  = $stuff_ball;
					$lotteryDetail['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
					$postDataInfo['LotteryTicket']  = $lotteryDetail['LotteryTicket'];
					$postDataInfo['Lottery']		= $lotteryDetail['Lottery'];
					$postDataInfo['User']			= $lotteryDetail['User'];
					$this->Session->write('ticketData', $postDataInfo);			
				}
			    $this->redirect(array('action' => 'admin_ticket_print',''.$lottery_id.'','ticket'));
				exit;
			} else {
				$this->Session->delete('uniqidTicketId');	
				$this->Session->delete('ticketData');
				$this->__setError(__('Internal error occur.Try again.', true));
			    $this->redirect(array('action' => 'admin_random_ticket',''.$lottery_id.''));
				exit;
			}
		}
		//on submitting sumit botton of the form
		if(!isset($this->request->data['LotteryTicket']['lottery_id']) && is_null($lottery_id)){
			$this->__setError(__('Internal error occur.Try again.', true));
			$this->redirect(array('action' => 'admin_generateticket'));
			exit;
		}
		$lottery_id = (isset($this->request->data['LotteryTicket']['lottery_id']))?trim($this->request->data['LotteryTicket']['lottery_id']):$lottery_id;
		$defaultTitleArry = explode(':',Configure::read('Settings.defaultTitle'));
		if(isset($defaultTitleArry[0])){
		  $this->set('SITE_URL', $defaultTitleArry[0]);
		}
		$data = array();
		$options['conditions'] = array('Lottery.id' => $lottery_id);
		$data				   = $this->Lottery->find('first', $options);
		$LotteryTypeDetail     = $this->LotteryType->getLotteryType($data['Lottery']['lottery_type']);
		$data['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
		$this->set('data', $data);
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function admin_winnner($lottery_id=NULL, $ticket_id=NULL){

		//checking session for publick method
		parent::checkSession();

		$totalamountPrizeLevel = $totalamountArray = $LotteryTicketCountArray = $LotteryTicketCount = $numlottballArray = $prizeLevel = $WinnerTicketCompare = $data = array();

		$prize_level_array = array('First','Second','Third','Fourth','Fifth','Sixth','Seventh','Eighth','Nineth','Tenth');  

		if (is_null($lottery_id) || is_null($ticket_id)) {
            $this->redirect(array('controller' => '/'));
        }
		$options['conditions'] = array('LotteryTicket.id' => $ticket_id);
		$data   = $this->LotteryTicket->find('first', $options);
		if(!empty($data)) {
			$LotteryTypeDetail     = $this->LotteryType->getLotteryType($data['Lottery']['lottery_type']);
			if(!empty($LotteryTypeDetail)) {
				foreach($LotteryTypeDetail as $key => $LotteryType){					
					$data['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
				}	
			}
			
			//getiing ticket owner
			$user_id    = $data['LotteryTicket']['user_id'];
			$userDetail = $this->User->userData($user_id);
			$data['User'] = $userDetail[0]['User'];

			$draw_date  = $data['LotteryTicket']['draw_date'];
			$lottery_id = $data['LotteryTicket']['lottery_id'];
			$options['conditions'] = array(
				'LotteryTicket.lottery_id' => $lottery_id,
				'LotteryTicket.draw_date' => $draw_date,
				'LotteryTicket.status' => 1
			);
		    $winData         = $this->LotteryTicket->find('first', $options);
			$data['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
			$data['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
			$data['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];

			if(isset($winData['LotteryTicket']['win_ticket'])){
				$WinnerTicketNumber = array();
				$win_ticket          = $winData['LotteryTicket']['win_ticket'];
				$stuff_ball          = $winData['LotteryTicket']['stuff_ball'];
				$prize_level		 = $winData['Lottery']['prize_level'];
				
				//************** PRIZE MONEY CALCULATION ******************
				$prize_perct	     = $data['Lottery']['prize_perct'];
				$level_perct	     = $data['Lottery']['level_perct'];
				$prize_perct_array   = explode(',',$level_perct);
				//getting all ticket of particular draw of paricular lottery
				$draw_date  = $data['LotteryTicket']['draw_date'];
				$lottery_id = $data['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date
				);
				$allSaledTicket = $this->LotteryTicket->find('all', $options);
				if(!empty($allSaledTicket)){
					foreach($allSaledTicket as $sales){
						$totalamountArray[] = $sales['Lottery']['lottery_fee'];
					}
					$totalamount = number_format(((array_sum($totalamountArray) * $prize_perct)/100),2);
			    }
				if(!empty($prize_perct_array) && $totalamount!=0) {
					foreach($prize_perct_array as $key => $prize_perct_one){
						$totalamountPrizeLevel[$prize_level_array[$key]] = number_format((($totalamount * $prize_perct_one)/100),2).' out of '.$totalamount.' $';
					}
				}
				//************** /PRIZE MONEY CALCULATION ******************
				
				if($stuff_ball=='') {
					$WinnerTicketNumber   = explode(',',$win_ticket);;
					$numlottball       = $data['Lottery']['num_lott_ball'];
				} else if(isset($win_ticket) && $win_ticket!='') {	
					$numlottball       = $data['Lottery']['num_lott_ball']-1;
					$winticketnumberArr = explode(',',$win_ticket);
					if(!empty($winticketnumberArr))
					unset($winticketnumberArr[count($winticketnumberArr)-1]);
					$WinnerTicketNumber   = $winticketnumberArr;	
				}				
				if($numlottball>0){
					for($num=1;$num<=$numlottball;$num++){
						$numlottballArray[]= $num;
					}
				}
				if(!empty($WinnerTicketNumber) && !empty($numlottballArray)) {
					$numlottballArrayOut = array_reverse($numlottballArray);								
					foreach($numlottballArrayOut as $numonekey => $numlottballone){
						$WinnerTicketCompare[$numlottballone] = implode(',',array_slice($WinnerTicketNumber,0,$numlottballone));
					}				
				}
				if(!empty($WinnerTicketCompare) && !empty($allSaledTicket)) {
					foreach($WinnerTicketCompare as $winnerlevelkey => $WinnerTicketCompareone){
						foreach($allSaledTicket as $saleduserkey => $saleduserone){
							$findoptions['conditions'] = array(
								'LotteryTicket.id' => $saleduserone['LotteryTicket']['id'],
								'LotteryTicket.ticket_id LIKE' => "%".$WinnerTicketCompareone."%",
								'LotteryTicket.draw_date' => $saleduserone['LotteryTicket']['draw_date']
							);							
							$findLotteryTicketCount = $this->LotteryTicket->find('first', $findoptions);
							if(isset($findLotteryTicketCount['LotteryTicket']['id'])){
								$LotteryTicketCountArray[$winnerlevelkey][] = $findLotteryTicketCount['LotteryTicket']['id'];
							}
						}
					}					
				}
				$uncommonTicketIdone = $uncommonTicketIdTwo = $uncommonTicketIdThree = $uncommonTicketIdFour = $uncommonTicketIdFive = '';
				if(!empty($LotteryTicketCountArray)) {
					foreach($LotteryTicketCountArray as $LotteryTicketCountkey => $LotteryTicketCountone){
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='0')){
							$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count($LotteryTicketCountone);
							$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',$LotteryTicketCountone);
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='1')){
							$ticketIdArrayLeveloneStr = $LotteryTicketCountoneStr ='';
							$ticketIdArrayLeveloneStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCountoneStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdone = array_diff($ticketIdArrayLeveloneStr,$LotteryTicketCountoneStr);
							if($uncommonTicketIdone!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdone));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdone));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='2')){
							$ticketIdArrayLeveltwoStr = $LotteryTicketCounttwoStr ='';
							$ticketIdArrayLeveltwoStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCounttwoStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdTwo = array_diff($ticketIdArrayLeveltwoStr,$LotteryTicketCounttwoStr);
							if($uncommonTicketIdTwo!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdTwo));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdTwo));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;						
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='3')){
							$ticketIdArrayLevelthreeStr = $LotteryTicketCountthreeStr ='';
							$ticketIdArrayLevelthreeStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCountthreeStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdThree = array_diff($ticketIdArrayLevelthreeStr,$LotteryTicketCountthreeStr);
							if($uncommonTicketIdThree!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdThree));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdThree));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;						
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='4')){
							$ticketIdArrayLevelFourStr = $LotteryTicketCountFourStr ='';
							$ticketIdArrayLevelFourStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCountFourStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdFour = array_diff($ticketIdArrayLevelFourStr,$LotteryTicketCountFourStr);
							if($uncommonTicketIdFour!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdFour));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdFour));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;						
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='5')){
							$ticketIdArrayLevelFiveStr = $LotteryTicketCountFiveStr ='';
							$ticketIdArrayLevelFiveStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCountFiveStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdFive = array_diff($ticketIdArrayLevelFiveStr,$LotteryTicketCountFiveStr);
							if($uncommonTicketIdFive!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdFive));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdFive));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}					
						}
					}
				} 
				if(!empty($LotteryTicketCount) && !empty($totalamountPrizeLevel) && $prize_level>0) {
					//creating prize level
					$LotteryTicketCountOut = array_slice($LotteryTicketCount,0,$prize_level,true);		
					foreach($LotteryTicketCountOut as $countkey => $counts){						
						$level_decider = $numlottball-$countkey;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['ticket_count'] = $counts['ticket_count'];
						$finalWinnerLevel[$prize_level_array[$level_decider]]['ticket_id'] = $counts['ticket_id'];
						$finalWinnerLevel[$prize_level_array[$level_decider]]['level_number'] = $countkey.' out of '.$numlottball;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['prize_amount'] = $totalamountPrizeLevel[$prize_level_array[$level_decider]];
					}					
				} else if(!empty($WinnerTicketCompare) && !empty($totalamountPrizeLevel) && $prize_level>0){
					$WinnerTicketCompareOut = array_slice($WinnerTicketCompare,0,$prize_level,true);	
					foreach($WinnerTicketCompareOut as $winnerlevelkey => $WinnerTicketCompareone){
						$level_decider = $numlottball-$winnerlevelkey;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['ticket_count'] = 0;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['ticket_id']    = '';
						$finalWinnerLevel[$prize_level_array[$level_decider]]['level_number'] = $winnerlevelkey.' out of '.$numlottball;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['prize_amount'] = $totalamountPrizeLevel[$prize_level_array[$level_decider]];
					}
				}
				if(!empty($finalWinnerLevel)) {
					$data['prize_count'] = $finalWinnerLevel;
				}
			}
		}		
		$this->set('data', $data);
		$this->set('currency', Configure::read('Settings.currency'));
	    //echo '<pre>';print_r($data);echo '</pre>';die;		
	}

	public function admin_winview(){
		
		$keydata = $this->params['pass'][0];
		$detailStr	= $this->request->data['detail'];
		$detailArray = explode(',',$detailStr);
		if(!empty($detailArray)){
			foreach($detailArray as $ticketidkey => $ticketids){								
				$options['conditions'] = array(
						'LotteryTicket.id' => $ticketids
				);
				$ticketDetail = $this->LotteryTicket->find('first', $options);
				if(isset($ticketDetail['LotteryTicket']['user_id'])){					
					$userDetail            = $this->User->userData($ticketDetail['LotteryTicket']['user_id']);
					$data[$ticketDetail['LotteryTicket']['user_id']] = $userDetail[0]['User'];
				}				
			}			
			$this->set('data', $data);
			$this->set('keydata', $keydata);
			$this->set('currency', Configure::read('Settings.currency'));
		}
	}

	function admin_ticketrequest($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		if(isset($action) && $action =='delete'){
			$returnAction = $this->__action_delete($id);
			if($returnAction) {
				$this->__setMessage(__('Ticket has been deleted successfully', true));
				$this->redirect(array('action' => 'admin_ticketrequest',''.$id.'','arequest'));
				exit;
			} else {
				$this->__setError(__('Internal Erorr occurs.Please try again.', true));
			    $this->redirect(array('action' => 'admin_ticketrequest',''.$id.'','arequest'));
				exit;
			}
		}
		$user_id = $this->Session->read('Auth.User.id');
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.id DESC';
		$this->paginate['conditions'] = array(
            'LotteryTicket.agent_id' => $user_id,
		    'LotteryTicket.status' => 5
        );
        $data = $this->paginate('LotteryTicket');	
		$this->set('data', $data);
		$this->set('totalitemsPage',count($data));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	//FUNCTION FOR validating SYSTEM DRAW
	function admin_tickets($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.id DESC';
		$this->paginate['conditions'] = array(
            'LotteryTicket.status NOT' => array(5,6)
        );
        $data = $this->paginate('LotteryTicket');	
		if(!empty($data)) {
			foreach($data as $key => $dataone){
				$LotteryTypeDetail = $this->LotteryType->getLotteryType($dataone['Lottery']['lottery_type']);
				if(isset($LotteryTypeDetail[0]['LotteryType']['lottery_type'])){
				   $data[$key]['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				}

				$draw_date  = $dataone['LotteryTicket']['draw_date'];
				$lottery_id = $dataone['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1,
					'LotteryTicket.win_ticket <>' => ''
				);
				$winData  = $this->LotteryTicket->find('first', $options);
				if(!empty($winData['LotteryTicket']['win_ticket'])){
					$data[$key]['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
					$data[$key]['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
					$data[$key]['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				} else { 
					$data[$key]['winData']['win_ticket'] = '';
					$data[$key]['winData']['win_date']   = '';
					$data[$key]['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				}
			}
		}
		$this->set('data', $data);
		$this->set('totalitemsPage',count($data));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function admin_view($id=NULL, $action=NULL) {

		//checking session for publick method
		parent::checkSession();

		$data = array();
		if (is_null($id) && is_null($action)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$options['conditions'] = array('LotteryTicket.id' => $id);
		$data   = $this->LotteryTicket->find('first', $options);
		if(!empty($data)) {
			$LotteryTypeDetail     = $this->LotteryType->getLotteryType($data['Lottery']['lottery_type']);
			if(!empty($LotteryTypeDetail)) {
				foreach($LotteryTypeDetail as $key => $LotteryType){					
					$data['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
				}	
			}
			$draw_date  = $data['LotteryTicket']['draw_date'];
			$lottery_id = $data['LotteryTicket']['lottery_id'];
			$options['conditions'] = array(
				'LotteryTicket.lottery_id' => $lottery_id,
				'LotteryTicket.draw_date' => $draw_date,
				'LotteryTicket.status' => 1
			);
		    $winData         = $this->LotteryTicket->find('first', $options);
			$data['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
			$data['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
			$data['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
		}
		$this->set('data', $data);
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function admin_action($id=NULL, $action=NULL) {
		
		//checking session for publick method
		parent::checkSession();

		if (is_null($id) && is_null($action)) {
            throw new NotFoundException(__l('Invalid request'));
        }		
		//if ticket canceled
		if(isset($action) && $action=='cancel'){
			$status    = 3;
			$ticket_id = $id;
			$statusUpdated = $this->LotteryTicket->saveGlobalData($table_name='lottery_tickets',$coloum_field='status', $coloum_value=$status,$updated_on_field='id',$updated_on_value=$ticket_id,$otherfields='');
			if($statusUpdated){
				$this->__setMessage(__('Ticket has been canceled.', true));
			    $this->redirect(array('action' => 'admin_view',''.$id.'','view'));
				exit;
			} else {
				$this->__setError(__('Internal error occur.Try again.', true));
			    $this->redirect(array('action' => 'admin_view',''.$id.'','view'));
				exit;
			}
		}
		
		//if ticket print
		if(isset($action) && $action=='print'){
			$downloadDetail      = array();
			if ($this->Session->read('printedData')) {					
					$this->Session->delete('printedData');
			}
			$options['conditions'] = array('LotteryTicket.id' => $id);
			$downloadDetail   = $this->LotteryTicket->find('first', $options);
			if(!empty($downloadDetail)) {
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($downloadDetail['Lottery']['lottery_type']);
				if(!empty($LotteryTypeDetail)) {
					foreach($LotteryTypeDetail as $key => $LotteryType){					
						$downloadDetail['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
					}	
				}
				$draw_date  = $downloadDetail['LotteryTicket']['draw_date'];
				$lottery_id = $downloadDetail['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1
				);
				$winData         = $this->LotteryTicket->find('first', $options);
				$downloadDetail['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
				$downloadDetail['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
				$downloadDetail['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				$downloadDetail['Lottery']['currency']   = Configure::read('Settings.currency');
			}			
			$this->Session->write('printedData', $downloadDetail);	
			$filename ='LotteryTickets/print_lottery_ticket.php';
			$returndoc	= parent::__printPDF($filename);
			exit;
		}		
	}

	function admin_drawlist($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		$winnerData = array();
		$user_id = $this->Session->read('Auth.User.id');
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.draw_date DESC';		
		$this->paginate['group'] = array('LotteryTicket.draw_date');		
		$this->paginate['conditions'] = array(
           'LotteryTicket.win_ticket <>' => '',
			'LotteryTicket.status' => 1
        );
        $winnerData = $this->paginate('LotteryTicket');		
		if(!empty($winnerData)){
			$winnerDataCount = count($winnerData);
			foreach($winnerData as $key => $lotterys){				
				$options['conditions'] = array(
					'LotteryTicket.draw_date' =>  $lotterys['LotteryTicket']['draw_date'],
					'LotteryTicket.status' => 1,
					'LotteryTicket.win_ticket <>' => '' 
				);
				$options['order'] = array('LotteryTicket.id');
				$lotteryDetailAllOneArray  = $this->LotteryTicket->find('all', $options);
				foreach($lotteryDetailAllOneArray as $key => $lotteryone){
					$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lotteryone['Lottery']['lottery_type']);
					$winnerData[$lotteryone['LotteryTicket']['draw_date']][$lotteryone['LotteryTicket']['lottery_id']] = $lotteryone;
					$winnerData[$lotteryone['LotteryTicket']['draw_date']][$lotteryone['LotteryTicket']['lottery_id']]['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				}				
			}
		}				
		$this->set('winnerData', $winnerData);		
		$this->set('user_id', $user_id);	
		$this->set('totalitemsPage',count($winnerData));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($winnerData);echo '</pre>';die;
	}

	function admin_autolotterydraw(){	
		$this->autoRender = false;
		$winnerData	= $this->__lotterydrawMethod();
		if(!empty($winnerData)){
		    echo count($winnerData).' Lottery Game Ticket has been drawn today.';
		} else {
			echo 'No Lottery Game Ticket has been drawn today.';
		}
	}
	
	function admin_lotterydraw($id=NULL, $action=NULL){
		$winnerData	= $this->__lotterydrawMethod();
		$this->set('winnerData', $winnerData);
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($winnerData);echo '</pre>';die;
	}	

	function __lotterydrawMethod(){
		$todayTicketDrawnresult = $todayTicketDrawn = $dataInfoslArrayData = $dataInfoslArray = array();
		$options['conditions'] = array(
			'LotteryTicket.status' => 0,
			'LotteryTicket.draw_date <>' => ''
		);
		$options['order'] = array('LotteryTicket.draw_date');
		$lotteryDetail  = $this->LotteryTicket->find('all', $options);
		if(!empty($lotteryDetail)){		
			foreach ($lotteryDetail as $Key => $lottery) {
				if($lottery['LotteryTicket']['stuff_ball']!='') {
					$lottery['LotteryTicket']['lottery_ticket']  = $lottery['LotteryTicket']['ticket_id'].','.$lottery['LotteryTicket']['stuff_ball'];
				} else {
					$lottery['LotteryTicket']['lottery_ticket']  = $lottery['LotteryTicket']['ticket_id'];
				}
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lottery['Lottery']['lottery_type']);
				$lottery['Lottery']['lottery_type'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				$dataInfoslArray[$lottery['LotteryTicket']['lottery_id']][]  = $lottery;				
			}
		}		
		if(!empty($dataInfoslArray)){	
			foreach($dataInfoslArray as $lotteryKey => $dataInfosAll){
				foreach($dataInfosAll as $Key => $dataInfos){					
					if($dataInfos['LotteryTicket']['draw_date']!=''){
						$lottery_ticket_time_arr  = explode('/', $dataInfos['LotteryTicket']['draw_date']);
						if(!empty($lottery_ticket_time_arr)) {
							foreach($lottery_ticket_time_arr as $key => $draw_time_one){
								$today_drawn_time    = date('Y-m-d H');
								$lottery_ticket_time = date('Y-m-d H',strtotime($draw_time_one));
								if($today_drawn_time == $lottery_ticket_time){
									if($dataInfos['Lottery']['lottery_system_draw']==1){
										$todayTicketDrawn[$lotteryKey][$dataInfos['LotteryTicket']['id']] = $dataInfos['LotteryTicket']['lottery_ticket'];
										$todayTicketDrawnresult[$lotteryKey]['LotteryTicket_id'] = $dataInfos['LotteryTicket']['id'];
										$todayTicketDrawnresult[$lotteryKey]['today_Lottery_number'] = $todayTicketDrawn[$lotteryKey][array_rand($todayTicketDrawn[$lotteryKey])];
										$todayTicketDrawnresult[$lotteryKey]['lottery_type'] = $dataInfos['Lottery']['lottery_type'];
										$todayTicketDrawnresult[$lotteryKey]['lottery_id'] = $dataInfos['LotteryTicket']['lottery_id'];
										$todayTicketDrawnresult[$lotteryKey]['draw_date']  = $dataInfos['LotteryTicket']['draw_date'];
										$todayTicketDrawnresult[$lotteryKey]['lottery_detail'] = $dataInfos;
									} else if($dataInfos['Lottery']['lottery_system_draw']==2){
										$todayTicketDrawn[$lotteryKey][$dataInfos['LotteryTicket']['id']] = $dataInfos['LotteryTicket']['lottery_ticket'];
										$todayTicketDrawnresult[$lotteryKey]['LotteryTicket_id'] = $dataInfos['LotteryTicket']['id'];
										//Generating Random Ticket
										$range_start		  = 1;
										$range_end			  = 49;
										$random_string_length = $dataInfos['Lottery']['num_lott_ball'];
										$randomLotteryNumber  = parent::__randomNumGenerator($range_start,$range_end,$random_string_length);
										if(!in_array($randomLotteryNumber,$todayTicketDrawn[$lotteryKey])){
											$todayTicketDrawnresult[$lotteryKey]['today_Lottery_number'] = $randomLotteryNumber;
										}
										$todayTicketDrawnresult[$lotteryKey]['lottery_type'] = $dataInfos['Lottery']['lottery_type'];
										$todayTicketDrawnresult[$lotteryKey]['lottery_id'] = $dataInfos['LotteryTicket']['lottery_id'];
										$todayTicketDrawnresult[$lotteryKey]['draw_date']  = $dataInfos['LotteryTicket']['draw_date'];
										$todayTicketDrawnresult[$lotteryKey]['lottery_detail'] = $dataInfos;
									} else if($dataInfos['Lottery']['lottery_system_draw']==0){}						
								}
							}					
						}
					}					
				}
			}
			//UPDATING WINNER TICKET STATUS 1
			if(!empty($todayTicketDrawnresult)){	
				foreach($todayTicketDrawnresult as $winnerlotteryticketkey => $winnerlotteryticketAll){
					$coloum_field_value_str = " `status` = '1' , `win_date`= '".$winnerlotteryticketAll['draw_date']."' , `win_ticket`= '".$winnerlotteryticketAll['today_Lottery_number']."' ";
					$this->LotteryTicket->saveManyGlobalData($table_name = 'lottery_tickets', $coloum_field_value_str, $updated_on_field ='id', $updated_on_value = $winnerlotteryticketAll['LotteryTicket_id'], $otherfields = ' and `status` = 0 ');
				}
			}
			//UPDATING LOSSER TICKET STATUS 2
			if(!empty($todayTicketDrawn)){	
				foreach($todayTicketDrawn as $lotteryticketkey => $losserlotteryticketAll){
					foreach($losserlotteryticketAll as $losserticketkey => $losserticketAll){
						$coloum_field_value_str = " `status` = '2' ";
						$this->LotteryTicket->saveManyGlobalData($table_name = 'lottery_tickets', $coloum_field_value_str, $updated_on_field='id', $updated_on_value = $losserticketkey , $otherfields = ' and `status` = 0 ');			
					}
				}
			}
			//UPDATING NEXT LOTTERY DRAW DATE OF ALL LOTTERY GAME
			if(!empty($todayTicketDrawnresult)){	
				$draw_date_arr = array();
				foreach($todayTicketDrawnresult as $lotteryticketkeyone => $winnerlotteryticketone){
					$prev_draw_date = $winnerlotteryticketone['draw_date'];
					if($prev_draw_date!=''){
					    $prev_draw_date_arr  = explode('/',$prev_draw_date);
						if(!empty($prev_draw_date_arr)) {
							foreach($prev_draw_date_arr as $key => $prev_draw_date_one){
								$draw_date_arr[] = parent::__getEndDatefromdays($prev_draw_date_one,6);
							}					
						}
					}	
					$draw_time = implode('/',$draw_date_arr);
					$coloum_field_value_str = " `draw_time` = '".$draw_time."' ";
					$this->LotteryTicket->saveManyGlobalData($table_name = 'lotterys', $coloum_field_value_str, $updated_on_field='id', $updated_on_value = $winnerlotteryticketone['lottery_id'] , $otherfields = '');					
				}
			}
		}
		$lotteryWinner = $this->LotteryTicket->getalllottteryDrawResultToday($status=0);
		return $lotteryWinner;
	}

	function admin_ticket($lottery_id=null,$ticket_id=null) {

		//checking session for publick method
		parent::checkSession();

		if($this->Session->read('uniqidTicketId')) {
			$this->Session->delete('uniqidTicketId');	
			$this->Session->delete('ticketData');
		}		
		if (is_null($lottery_id) || is_null($ticket_id)) {
            $this->redirect(array('action' => 'admin_ticketrequest'));
		}
		if(isset($data['LotteryTicket']['ticket_id']) && $data['LotteryTicket']['ticket_id']!=''){
			$this->__setError(__('This ticket has been already created.', true));
			$this->redirect(array('action' => 'tktreqlist'));
			exit;
		}
		$options['conditions'] = array('LotteryTicket.id' => $ticket_id);
		$data   = $this->LotteryTicket->find('first', $options);
		if(isset($data['LotteryTicket']['ticket_id']) && $data['LotteryTicket']['ticket_id']!=''){
			$this->__setError(__('This ticket has been already created.', true));
			$this->redirect(array('action' => 'tktreqlist'));
			exit;
		}
		$activeLotteryTypes = $this->LotteryType->getLotteryType($data['Lottery']['lottery_type']);
		if(isset($activeLotteryTypes[0]['LotteryType']['lottery_type'])){
			$data['Lottery']['lottery_type_name']  = $activeLotteryTypes[0]['LotteryType']['lottery_type'];				   
		}
		$this->set('data', $data);
		$this->set('ticket_id', $ticket_id);	
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	//function for printing ticket
	function admin_ticket_print($id=NULL, $action=NULL){

		 //checking session for publick method
		 parent::checkSession();

		 $postDataInfo = array();

		 //if ticket print
		 if(isset($action) && $action=='print'){
			$downloadDetail      = array();
			if ($this->Session->read('printedData')) {					
			    $this->Session->delete('printedData');
			}
			$options['conditions'] = array('LotteryTicket.id' => $id);
			$downloadDetail   = $this->LotteryTicket->find('first', $options);
			if(!empty($downloadDetail)) {
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($downloadDetail['Lottery']['lottery_type']);
				if(!empty($LotteryTypeDetail)) {
					foreach($LotteryTypeDetail as $key => $LotteryType){					
						$downloadDetail['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
					}	
				}
				$draw_date  = $downloadDetail['LotteryTicket']['draw_date'];
				$lottery_id = $downloadDetail['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1
				);
				$winData         = $this->LotteryTicket->find('first', $options);
				$downloadDetail['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
				$downloadDetail['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
				$downloadDetail['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				$downloadDetail['Lottery']['currency']  = Configure::read('Settings.currency');
			}
			$this->Session->write('printedData', $downloadDetail);
			$filename ='LotteryTickets/print_lottery_ticket.php';
			$returndoc	= parent::__printPDF($filename);
			exit;
		 }

		 //on submitting the form
		 if(!empty($this->request->data)) {
			 $tickid = (isset($this->request->data['LotteryTicket']['id']))?trim($this->request->data['LotteryTicket']['id']):'';
			 $lottery_id = (isset($this->request->data['LotteryTicket']['lottery_id']))?trim($this->request->data['LotteryTicket']['lottery_id']):'';
			 $ticket_id = (isset($this->request->data['LotteryTicket']['ticket_id']))?trim($this->request->data['LotteryTicket']['ticket_id']):'';
			 $stuff_ball = (isset($this->request->data['LotteryTicket']['stuff_ball']))?trim($this->request->data['LotteryTicket']['stuff_ball']):'';
			 $lottery_fee = (isset($this->request->data['LotteryTicket']['lottery_fee']))?trim($this->request->data['LotteryTicket']['lottery_fee']):'';

		    //Checking Balance and Taking Lottery Fee by deducting User Balance.
			$user_id      = $this->Session->read('Auth.User.id');
			$agent_perct  = $this->Session->read('Auth.User.agent_perct');
			$user_balance = $this->Session->read('Auth.User.balance');			
			if(isset($user_balance) && $user_balance < 0){
				$this->__setError(__('You donot have sufficient balance in your account.', true));
			    $this->redirect(array('action' => 'admin_ticket',''.$lottery_id.'',$tickid));
				exit;
			}
			//Agent percentage calculation
            $perct_lottery_fee  = number_format((($lottery_fee * $agent_perct)/100),2);
			$actual_lottery_fee = number_format(($user_balance - ($lottery_fee - $perct_lottery_fee)),2);

			$status     = 0;
			$ticket_id  = $ticket_id;
			if($stuff_ball!=''){
			   $stuff_ball = "`stuff_ball`= '".$stuff_ball."' ,";
			}
			$added_on   = date('Y-m-d H:i:s');
			$coloum_field_value_str = " `status` = 0, `ticket_id`= '".$ticket_id."' , ".$stuff_ball." `added_on`= '".$added_on."' ";
			$update = $this->LotteryTicket->saveManyGlobalData($table_name = 'lottery_tickets', $coloum_field_value_str, $updated_on_field='id', $updated_on_value = $tickid , $otherfields = '');
			if($update) {
				$this->LotteryTicket->saveGlobalData($table_name='users',$coloum_field='balance', $coloum_value=$actual_lottery_fee,$updated_on_field='id',$updated_on_value=$user_id,$otherfields='');
				$this->Session->write('uniqidTicketId', $tickid);
				$options['conditions'] = array('LotteryTicket.id' => $tickid);
				$lotteryDetail         = $this->LotteryTicket->find('first', $options);
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lotteryDetail['Lottery']['lottery_type']);
				if(!empty($lotteryDetail) && !empty($LotteryTypeDetail)){
					$lotteryDetail['LotteryTicket']['ticket_id']   = $ticket_id;
					$lotteryDetail['LotteryTicket']['stuff_ball']  = $stuff_ball;
					$lotteryDetail['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
					$postDataInfo['LotteryTicket']  = $lotteryDetail['LotteryTicket'];
					$postDataInfo['Lottery']		= $lotteryDetail['Lottery'];
					$postDataInfo['User']			= $lotteryDetail['User'];
					$this->Session->write('ticketData', $postDataInfo);
				}				
			    $this->redirect(array('action' => 'admin_ticket_print',$lottery_id,'ticket'));
				exit;
			} else {
				$this->Session->delete('uniqidTicketId');	
				$this->Session->delete('ticketData');
				$this->__setError(__('Internal error occur.Try again.', true));
			    $this->redirect(array('action' => 'admin_ticket',''.$lottery_id.'',$tickid));
				exit;
			}		
		}		
	}

	function admin_tktreqlist($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		if ($this->Session->read('printedData')) {					
			$this->Session->delete('printedData');
		}
		$user_id = $this->Session->read('Auth.User.id');
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.id DESC';
		$this->paginate['conditions'] = array(
            'LotteryTicket.agent_id' => $user_id,
		    'LotteryTicket.ticket_id <>' => '',
			'LotteryTicket.status NOT' => array(5,6)			
        );
		$data = $this->paginate('LotteryTicket');
		if(!empty($data)) {
			foreach($data as $key => $dataone){
				$LotteryTypeDetail = $this->LotteryType->getLotteryType($dataone['Lottery']['lottery_type']);
				if(isset($LotteryTypeDetail[0]['LotteryType']['lottery_type'])){
				   $data[$key]['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				}

				$draw_date  = $dataone['LotteryTicket']['draw_date'];
				$lottery_id = $dataone['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1,
					'LotteryTicket.win_ticket <>' => ''
				);
				$winData  = $this->LotteryTicket->find('first', $options);
				if(!empty($winData['LotteryTicket']['win_ticket'])){
					$data[$key]['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
					$data[$key]['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
					$data[$key]['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				} else { 
					$data[$key]['winData']['win_ticket'] = '';
					$data[$key]['winData']['win_date']   = '';
					$data[$key]['winData']['draw_date']  = $draw_date;
				}
			}
		}
		$this->set('data', $data);
		$this->set('totalitemsPage',count($data));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	public function admin_search($ticket_id=null) {

		//checking session for publick method
		parent::checkSession();

		$ticket_id = (isset($this->request->data['LotteryTicket']['ticket_id']))?trim($this->request->data['LotteryTicket']['ticket_id']):$ticket_id;
        if($this->Session->read('dashboard_type') && $this->Session->read('dashboard_type')=='admin'){
		   $this->Session->write('dashboard_type','admin_lottery');
		   $this->redirect(array('action' => 'admin_search',$ticket_id));
		   exit;
		}
		if($ticket_id!='') {
			$ticketDetail = $this->LotteryTicket->getTicket($ticket_id);
			if (!empty($ticketDetail)) {
				$this->redirect(array('action' => 'admin_view',''.$ticket_id.'','search'));		
			} else {
				$this->__setMessage(__('No such ticket ID exists.', true));
				$this->redirect(array('controller' => '/'));	
			}
		} else {
			$this->__setError(__('Please enter a Ticket ID.', true));
			$this->redirect(array('controller' => '/'));	
		}
    }

	//*** / ADMIN SECTION FUNCTION *****************************************
	
	//FRONT SECTION FUNCTION ***********************************************
	
	function search() {
		if($this->request->data['LotteryTicket']['ticket_id']!='') {
			$ticket_id = $this->request->data['LotteryTicket']['ticket_id'];
			$ticketDetail = $this->LotteryTicket->getTicket($ticket_id);
			if (!empty($ticketDetail)) {
				$this->redirect(array('action' => 'view',''.$ticket_id.'','search'));		
			} else {
				$this->__setError(__('No such ticket ID exists.Please try again', true));
				$this->redirect(array('action' => 'search','0','search'));	
			}
		}
    }

	function drawlist($id=NULL, $action=NULL){

		$winnerData = array();
		$user_id = $this->Session->read('Auth.User.id');
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.draw_date DESC';		
		$this->paginate['group'] = array('LotteryTicket.draw_date');		
		$this->paginate['conditions'] = array(
           'LotteryTicket.win_ticket <>' => '',
			'LotteryTicket.status' => 1
        );
        $winnerData = $this->paginate('LotteryTicket');		
		if(!empty($winnerData)){
			$winnerDataCount = count($winnerData);
			foreach($winnerData as $key => $lotterys){				
				$options['conditions'] = array(
					'LotteryTicket.draw_date' =>  $lotterys['LotteryTicket']['draw_date'],
					'LotteryTicket.status' => 1,
					'LotteryTicket.win_ticket <>' => '' 
				);
				$options['order'] = array('LotteryTicket.id');
				$lotteryDetailAllOneArray  = $this->LotteryTicket->find('all', $options);
				foreach($lotteryDetailAllOneArray as $key => $lotteryone){
					$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lotteryone['Lottery']['lottery_type']);
					$winnerData[$lotteryone['LotteryTicket']['draw_date']][$lotteryone['LotteryTicket']['lottery_id']] = $lotteryone;
					$winnerData[$lotteryone['LotteryTicket']['draw_date']][$lotteryone['LotteryTicket']['lottery_id']]['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				}				
			}
		}				
		$this->set('winnerData', $winnerData);		
		$this->set('user_id', $user_id);	
		$this->set('totalitemsPage',count($winnerData));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($winnerData);echo '</pre>';die;
	}

	function lotteryheader() {
		$lotteryDatainfo = array();
		$this->layout = 'ajax';
		$id = $this->params['pass'][0];
		$options['conditions'] = array('Lottery.id' => $id);
		$lotteryDetail   = $this->Lottery->find('first', $options);
		if(!empty($lotteryDetail)) {
			$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lotteryDetail['Lottery']['lottery_type']);
			if(!empty($LotteryTypeDetail)) {
				foreach($LotteryTypeDetail as $key => $LotteryType){					
					$lotteryDetail['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
				}	
			}
			$this->set('data', $lotteryDetail);
			$this->set('currency', Configure::read('Settings.currency'));
		}
    }

	function admin_lotteryheader() {
		$lotteryDatainfo = array();
		$this->layout = 'ajax';
		$id = $this->params['pass'][0];
		$options['conditions'] = array('Lottery.id' => $id);
		$lotteryDetail   = $this->Lottery->find('first', $options);
		if(!empty($lotteryDetail)) {
			$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lotteryDetail['Lottery']['lottery_type']);
			if(!empty($LotteryTypeDetail)) {
				foreach($LotteryTypeDetail as $key => $LotteryType){					
					$lotteryDetail['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
				}	
			}
			$this->set('data', $lotteryDetail);
		}
    }

	function ticketlists($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		$userid = $this->Session->read('Auth.User.id');
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.id DESC';
		$this->paginate['conditions'] = array(
            'LotteryTicket.user_id' => $userid,
			'LotteryTicket.status NOT' => array(5,6)
        );
        $data = $this->paginate('LotteryTicket');
		if(!empty($data)) {
			foreach($data as $key => $dataone){
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($dataone['Lottery']['lottery_type']);
				$data[$key]['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				$draw_date  = $dataone['LotteryTicket']['draw_date'];
				$lottery_id = $dataone['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1,
					'LotteryTicket.win_ticket <>' => ''
				);
				$winData  = $this->LotteryTicket->find('first', $options);
				if(!empty($winData['LotteryTicket']['win_ticket'])){
					$data[$key]['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
					$data[$key]['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
					$data[$key]['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				} else { 
					$data[$key]['winData']['win_ticket'] = '';
					$data[$key]['winData']['win_date']   = '';
					$data[$key]['winData']['draw_date']  = $draw_date;
				}
			}
		}	
		$this->set('data', $data);
		$this->set('totalitemsPage',count($data));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function view($id=NULL, $action=NULL) {		
		if (is_null($id) && is_null($action)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$options['conditions'] = array('LotteryTicket.id' => $id);
		$data   = $this->LotteryTicket->find('first', $options);
		if(!empty($data)) {
			$LotteryTypeDetail     = $this->LotteryType->getLotteryType($data['Lottery']['lottery_type']);
			if(!empty($LotteryTypeDetail)) {
				foreach($LotteryTypeDetail as $key => $LotteryType){					
					$data['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
				}	
			}
			$draw_date  = $data['LotteryTicket']['draw_date'];
			$lottery_id = $data['LotteryTicket']['lottery_id'];
			$options['conditions'] = array(
				'LotteryTicket.lottery_id' => $lottery_id,
				'LotteryTicket.draw_date' => $draw_date,
				'LotteryTicket.status' => 1
			);
		    $winData         = $this->LotteryTicket->find('first', $options);
			$data['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
			$data['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
			$data['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
		}		
		$this->set('data', $data);
		$this->set('lottery_id', $lottery_id);
		$this->set('currency', Configure::read('Settings.currency'));
	}

	function tickets($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		$userid = $this->Session->read('Auth.User.id');
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.id DESC';
		$this->paginate['conditions'] = array(
            'LotteryTicket.user_id' => $userid,
            'LotteryTicket.lottery_id' => $id,
			'LotteryTicket.status NOT' => array(5,6)
        );
        $data = $this->paginate('LotteryTicket');		
		if(!empty($data)) {
			foreach($data as $key => $dataone){
				$LotteryTypeDetail = $this->LotteryType->getLotteryType($dataone['Lottery']['lottery_type']);
				if(isset($LotteryTypeDetail[0]['LotteryType']['lottery_type'])){
				   $data[$key]['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				}

				$draw_date  = $dataone['LotteryTicket']['draw_date'];
				$lottery_id = $dataone['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1,
					'LotteryTicket.win_ticket <>' => ''
				);
				$winData  = $this->LotteryTicket->find('first', $options);
				if(!empty($winData['LotteryTicket']['win_ticket'])){
					$data[$key]['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
					$data[$key]['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
					$data[$key]['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				} else { 
					$data[$key]['winData']['win_ticket'] = '';
					$data[$key]['winData']['win_date']   = '';
					$data[$key]['winData']['draw_date']  = $draw_date;
				}
			}
		}
		$this->set('data', $data);
		$this->set('totalitemsPage',count($data));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function ticket($id=null) {

		//checking session for publick method
		parent::checkSession();

		if($this->Session->read('uniqidTicketId')) {
			$this->Session->delete('uniqidTicketId');	
			$this->Session->delete('ticketData');
		}		
		if (is_null($id) && is_null($action)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$this->paginate['conditions'] = array(
			'Lottery.id' => $id
		);
        $dataAll = $this->paginate('Lottery');		
        if(!empty($dataAll)){
			foreach($dataAll as $key => $records){
				$activeLotteryTypes = $this->LotteryType->getLotteryType($records['Lottery']['lottery_type']);
				if(isset($activeLotteryTypes[0]['LotteryType']['lottery_type'])){
				   $records['Lottery']['lottery_type_name']  = $activeLotteryTypes[0]['LotteryType']['lottery_type'];
				}
				$data[] = $records;
			}
			$this->set('data', $data);
			$this->set('totalitemsPage',count($data));
		    $this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
			$this->set('currency', Configure::read('Settings.currency'));
		}
	}

	//function for printing ticket
	function ticket_print($id=NULL, $action=NULL){

		 //checking session for publick method
		 parent::checkSession();

		 $postDataInfo = array();
		 //if ticket print
		 if(isset($action) && $action=='print'){
			$downloadDetail      = array();
			if ($this->Session->read('printedData')) {					
					$this->Session->delete('printedData');
			}
			$options['conditions'] = array('LotteryTicket.id' => $id);
			$downloadDetail   = $this->LotteryTicket->find('first', $options);
			if(!empty($downloadDetail)) {
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($downloadDetail['Lottery']['lottery_type']);
				if(!empty($LotteryTypeDetail)) {
					foreach($LotteryTypeDetail as $key => $LotteryType){					
						$downloadDetail['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
					}	
				}
				$draw_date  = $downloadDetail['LotteryTicket']['draw_date'];
				$lottery_id = $downloadDetail['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1
				);
				$winData         = $this->LotteryTicket->find('first', $options);
				$downloadDetail['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
				$downloadDetail['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
				$downloadDetail['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
			}
			$this->Session->write('printedData', $downloadDetail);
			$this->set('currency', Configure::read('Settings.currency'));
			$filename ='LotteryTickets/print_lottery_ticket.php';
			$returndoc	= parent::__printPDF($filename);
			exit;
		 }
		 if(!empty($this->request->data)) {	
			$lottery_id = (isset($this->request->data['ticket']['lottery_id']))?trim($this->request->data['ticket']['lottery_id']):'';
			$ticket_id = (isset($this->request->data['ticket']['ticket_id']))?trim($this->request->data['ticket']['ticket_id']):'';
			$stuff_ball = (isset($this->request->data['ticket']['stuff_ball']))?trim($this->request->data['ticket']['stuff_ball']):'';
			$lottery_fee = (isset($this->request->data['ticket']['lottery_fee']))?trim($this->request->data['ticket']['lottery_fee']):'';
			$draw_date = (isset($this->request->data['ticket']['draw_date']))?trim($this->request->data['ticket']['draw_date']):'';

		    //Checking Balance and Taking Lottery Fee by deducting User Balance.
			$user_id      = $this->Session->read('Auth.User.id');
			$user_balance = $this->Session->read('Auth.User.balance');
			if(isset($user_balance) && $user_balance < 0){
				$this->__setError(__('You donot have sufficient balance in your account.', true));
			    $this->redirect(array('action' => 'ticket',''.$lottery_id.'','ticket'));
				exit;
			}
            $actual_lottery_fee    = $user_balance - $lottery_fee;			
			$datasave['LotteryTicket']['status']      = 0;
			$datasave['LotteryTicket']['user_id']	  = $user_id;
			$datasave['LotteryTicket']['lottery_id']  = $lottery_id;
			$datasave['LotteryTicket']['lottery_fee'] = $lottery_fee;
			$datasave['LotteryTicket']['ticket_id']   = $ticket_id;
			$datasave['LotteryTicket']['stuff_ball']  = $stuff_ball;
			$datasave['LotteryTicket']['draw_date']   = $draw_date;
			$datasave['LotteryTicket']['added_on']	  = date('Y-m-d H:i:s');
			if($this->LotteryTicket->save($datasave)) {
				$this->LotteryTicket->saveGlobalData($table_name='users',$coloum_field='balance', $coloum_value=$actual_lottery_fee,$updated_on_field='id',$updated_on_value=$user_id,$otherfields='');
				$insertedID = $this->LotteryTicket->id;
				$this->Session->write('uniqidTicketId', $insertedID);
				$options['conditions'] = array('LotteryTicket.id' => $insertedID);
				$lotteryDetail         = $this->LotteryTicket->find('first', $options);
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lotteryDetail['Lottery']['lottery_type']);
				if(!empty($lotteryDetail) && !empty($LotteryTypeDetail)){
					$lotteryDetail['LotteryTicket']['ticket_id']   = $ticket_id;
					$lotteryDetail['LotteryTicket']['stuff_ball']  = $stuff_ball;
					$lotteryDetail['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
					$postDataInfo['LotteryTicket']  = $lotteryDetail['LotteryTicket'];
					$postDataInfo['Lottery']		= $lotteryDetail['Lottery'];
					$postDataInfo['User']			= $lotteryDetail['User'];
					$this->Session->write('ticketData', $postDataInfo);		
					$this->set('currency', Configure::read('Settings.currency'));
				}
			    $this->redirect(array('action' => 'ticket_print',''.$lottery_id.'','ticket'));
				exit;
			} else {
				$this->Session->delete('uniqidTicketId');	
				$this->Session->delete('ticketData');
				$this->__setError(__('Internal error occur.Try again.', true));
			    $this->redirect(array('action' => 'ticket',''.$lottery_id.'','ticket'));
				exit;
			}		
		}		
	}

	function tickets_request($id=NULL, $action=NULL) {	
		
		//checking session for publick method
		parent::checkSession();

		$data = array();		
		if(!empty($this->request->data)) {			 
			$lottery_id = (isset($this->request->data['LotteryTicket']['lottery_id']))?trim($this->request->data['LotteryTicket']['lottery_id']):'';
			$lottery_fee = (isset($this->request->data['LotteryTicket']['lottery_fee']))?trim($this->request->data['LotteryTicket']['lottery_fee']):'';
			$agent_id = (isset($this->request->data['LotteryTicket']['agent_id']))?trim($this->request->data['LotteryTicket']['agent_id']):'';
			$user_id = (isset($this->request->data['LotteryTicket']['user_id']))?trim($this->request->data['LotteryTicket']['user_id']):'';
			if($agent_id == '0' || $agent_id == '' || $user_id == ''){
			    $this->__setError(__('Please select a Agent', true));
			    $this->redirect(array('action' => 'tickets_request',''.$lottery_id.'','request'));
				exit;
			}
			$user_balance = $this->Session->read('Auth.User.balance');
			if(isset($user_balance) && $user_balance < 0){
				$this->__setError(__('You donot have sufficient balance in your account.', true));
			    $this->redirect(array('action' => 'tickets_request',''.$lottery_id.'','request'));
				exit;
			}
			$datasave['LotteryTicket']['status']	  = 5;
			$datasave['LotteryTicket']['user_id']	  = $user_id;
			$datasave['LotteryTicket']['agent_id']	  = $agent_id;
			$datasave['LotteryTicket']['lottery_id']  = $lottery_id;
			$datasave['LotteryTicket']['lottery_fee'] = $lottery_fee;
			$datasave['LotteryTicket']['added_on']	  = date('Y-m-d H:i:s');
			if($this->LotteryTicket->save($datasave)) {
				//User account deduction of lottery fee from user account.
				if(isset($user_id)){
					$user_deduction_fee  = $user_balance - $lottery_fee;
					$this->LotteryTicket->saveGlobalData($table_name='users',$coloum_field='balance', $coloum_value=$user_deduction_fee,$updated_on_field='id',$updated_on_value=$user_id,$otherfields='');
				}
				//Agent account addition of lottery fee from user account
				$agentData = $this->User->getUser($tableName='users',$fieldId='id',$fieldValue=$agent_id);	
				if(isset($agentData[0]['users'])){
					$agent_addition_fee  = $agentData['0']['users']['balance'] + $lottery_fee;
					$this->LotteryTicket->saveGlobalData($table_name='users',$coloum_field='balance', $coloum_value=$agent_addition_fee,$updated_on_field='id',$updated_on_value=$agent_id,$otherfields='');
				}				
				$this->__setMessage(__('Ticket Request has been sent successfully', true));
			    $this->redirect(array('controller' => 'lotterys','action' => 'view',''.$lottery_id.'','about'));
				exit;
			} else {
				$this->__setError(__('Internal error occur.Try again.', true));
			    $this->redirect(array('action' => 'tickets_request',''.$lottery_id.'','request'));
				exit;
			}
		}

		if (is_null($id) && is_null($action)) {
            $this->redirect(array('controller' => '/'));
        }		
		$user_id      = $this->Session->read('Auth.User.id');
		$this->set('user_id', $user_id);		
		$allAgents = $this->User->allAgent(8);
		$agentsOption = array('0'=> 'select Agent');
		if(!empty($allAgents)) {
			foreach($allAgents as $key => $agents){
				$agentsOption[$agents['User']['id']]   = $agents['User']['username'];
			}
			$this->set('agentsOption', $agentsOption);
		}
		$this->paginate['conditions'] = array(
			'Lottery.id' => $id
		);
        $dataAll = $this->paginate('Lottery');		
        if(!empty($dataAll)){
			foreach($dataAll as $key => $records){
				$activeLotteryTypes = $this->LotteryType->getLotteryType($records['Lottery']['lottery_type']);
				if(isset($activeLotteryTypes[0]['LotteryType']['lottery_type'])){
				   $records['Lottery']['lottery_type_name']  = $activeLotteryTypes[0]['LotteryType']['lottery_type'];
				}
				$data[] = $records;
			}
			$this->set('data', $data);
			$this->set('totalitemsPage',count($data));
		    $this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
			$this->set('currency', Configure::read('Settings.currency'));
		}  
	}

	//function created for payout showing for individual winner
	function payout($ticket_id=NULL) {

		$totalamountPrizeLevel = $totalamountArray = $LotteryTicketCountArray = $LotteryTicketCount = $numlottballArray = $prizeLevel = $WinnerTicketCompare = $data = array();

		$prize_level_array = array('First','Second','Third','Fourth','Fifth','Sixth','Seventh','Eighth','Nineth','Tenth');  

		if (is_null($ticket_id)) {
            $this->redirect(array('controller' => '/'));
        }
		$currency = Configure::read('Settings.currency');
		$options['conditions'] = array('LotteryTicket.id' => $ticket_id);
		$data   = $this->LotteryTicket->find('first', $options);
		if(!empty($data)) {
			$LotteryTypeDetail     = $this->LotteryType->getLotteryType($data['Lottery']['lottery_type']);
			if(!empty($LotteryTypeDetail)) {
				foreach($LotteryTypeDetail as $key => $LotteryType){					
					$data['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
				}	
			}
			
			//getting ticket owner
			$user_id    = $data['LotteryTicket']['user_id'];
			$userDetail = $this->User->userData($user_id);
			$data['User'] = $userDetail[0]['User'];

			$draw_date  = $data['LotteryTicket']['draw_date'];
			$lottery_id = $data['LotteryTicket']['lottery_id'];
			$options['conditions'] = array(
				'LotteryTicket.lottery_id' => $lottery_id,
				'LotteryTicket.draw_date' => $draw_date,
				'LotteryTicket.status' => 1
			);
		    $winData         = $this->LotteryTicket->find('first', $options);
			$data['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
			$data['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
			$data['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];

			if(isset($winData['LotteryTicket']['win_ticket'])){
				$WinnerTicketNumber = array();
				$win_ticket          = $winData['LotteryTicket']['win_ticket'];
				$stuff_ball          = $winData['LotteryTicket']['stuff_ball'];
				$prize_level		 = $winData['Lottery']['prize_level'];
				
				//************** PRIZE MONEY CALCULATION ******************
				$prize_perct	     = $data['Lottery']['prize_perct'];
				$level_perct	     = $data['Lottery']['level_perct'];
				$prize_perct_array   = explode(',',$level_perct);
				//getting all ticket of particular draw of paricular lottery
				$draw_date  = $data['LotteryTicket']['draw_date'];
				$lottery_id = $data['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date
				);
				$allSaledTicket = $this->LotteryTicket->find('all', $options);
				if(!empty($allSaledTicket)){
					foreach($allSaledTicket as $sales){
						$totalamountArray[] = $sales['Lottery']['lottery_fee'];
					}
					$totalamount = number_format(((array_sum($totalamountArray) * $prize_perct)/100),2);
			    }
				if(!empty($prize_perct_array) && $totalamount!=0) {
					foreach($prize_perct_array as $key => $prize_perct_one){
						$totalamountPrizeLevel[$prize_level_array[$key]] = number_format((($totalamount * $prize_perct_one)/100),2).' out of '.$totalamount.' '.$currency;
					}
				}
				//************** /PRIZE MONEY CALCULATION ******************
				
				if($stuff_ball=='') {
					$WinnerTicketNumber   = explode(',',$win_ticket);;
					$numlottball       = $data['Lottery']['num_lott_ball'];
				} else if(isset($win_ticket) && $win_ticket!='') {	
					$numlottball       = $data['Lottery']['num_lott_ball']-1;
					$winticketnumberArr = explode(',',$win_ticket);
					if(!empty($winticketnumberArr))
					unset($winticketnumberArr[count($winticketnumberArr)-1]);
					$WinnerTicketNumber   = $winticketnumberArr;	
				}				
				if($numlottball>0){
					for($num=1;$num<=$numlottball;$num++){
						$numlottballArray[]= $num;
					}
				}
				if(!empty($WinnerTicketNumber) && !empty($numlottballArray)) {
					$numlottballArrayOut = array_reverse($numlottballArray);								
					foreach($numlottballArrayOut as $numonekey => $numlottballone){
						$WinnerTicketCompare[$numlottballone] = implode(',',array_slice($WinnerTicketNumber,0,$numlottballone));
					}				
				}
				if(!empty($WinnerTicketCompare) && !empty($allSaledTicket)) {
					foreach($WinnerTicketCompare as $winnerlevelkey => $WinnerTicketCompareone){
						foreach($allSaledTicket as $saleduserkey => $saleduserone){
							$findoptions['conditions'] = array(
								'LotteryTicket.id' => $saleduserone['LotteryTicket']['id'],
								'LotteryTicket.ticket_id LIKE' => "%".$WinnerTicketCompareone."%",
								'LotteryTicket.draw_date' => $saleduserone['LotteryTicket']['draw_date']
							);							
							$findLotteryTicketCount = $this->LotteryTicket->find('first', $findoptions);
							if(isset($findLotteryTicketCount['LotteryTicket']['id'])){
								$LotteryTicketCountArray[$winnerlevelkey][] = $findLotteryTicketCount['LotteryTicket']['id'];
							}
						}
					}					
				}
				$uncommonTicketIdone = $uncommonTicketIdTwo = $uncommonTicketIdThree = $uncommonTicketIdFour = $uncommonTicketIdFive = '';
				if(!empty($LotteryTicketCountArray)) {
					foreach($LotteryTicketCountArray as $LotteryTicketCountkey => $LotteryTicketCountone){
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='0')){
							$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count($LotteryTicketCountone);
							$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',$LotteryTicketCountone);
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='1')){
							$ticketIdArrayLeveloneStr = $LotteryTicketCountoneStr ='';
							$ticketIdArrayLeveloneStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCountoneStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdone = array_diff($ticketIdArrayLeveloneStr,$LotteryTicketCountoneStr);
							if($uncommonTicketIdone!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdone));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdone));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='2')){
							$ticketIdArrayLeveltwoStr = $LotteryTicketCounttwoStr ='';
							$ticketIdArrayLeveltwoStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCounttwoStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdTwo = array_diff($ticketIdArrayLeveltwoStr,$LotteryTicketCounttwoStr);
							if($uncommonTicketIdTwo!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdTwo));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdTwo));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;						
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='3')){
							$ticketIdArrayLevelthreeStr = $LotteryTicketCountthreeStr ='';
							$ticketIdArrayLevelthreeStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCountthreeStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdThree = array_diff($ticketIdArrayLevelthreeStr,$LotteryTicketCountthreeStr);
							if($uncommonTicketIdThree!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdThree));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdThree));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;						
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='4')){
							$ticketIdArrayLevelFourStr = $LotteryTicketCountFourStr ='';
							$ticketIdArrayLevelFourStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCountFourStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdFour = array_diff($ticketIdArrayLevelFourStr,$LotteryTicketCountFourStr);
							if($uncommonTicketIdFour!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdFour));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdFour));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}
							$ticketIdArrayLeveloneArray = $LotteryTicketCountone;						
						}
						if(!empty($LotteryTicketCountone) && ($numlottball - $LotteryTicketCountkey=='5')){
							$ticketIdArrayLevelFiveStr = $LotteryTicketCountFiveStr ='';
							$ticketIdArrayLevelFiveStr = implode(',',$ticketIdArrayLeveloneArray);		
							$LotteryTicketCountFiveStr = implode(',',$LotteryTicketCountone);
							$uncommonTicketIdFive = array_diff($ticketIdArrayLevelFiveStr,$LotteryTicketCountFiveStr);
							if($uncommonTicketIdFive!=''){
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = count(explode(',',$uncommonTicketIdFive));
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id'] = implode(',',explode(',',$uncommonTicketIdFive));
							} else {
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_count'] = 0;
								$LotteryTicketCount[$LotteryTicketCountkey]['ticket_id']    = '';
							}					
						}
					}
				} 
				if(!empty($LotteryTicketCount) && !empty($totalamountPrizeLevel) && $prize_level>0) {
					//creating prize level
					$LotteryTicketCountOut = array_slice($LotteryTicketCount,0,$prize_level,true);		
					foreach($LotteryTicketCountOut as $countkey => $counts){						
						$level_decider = $numlottball-$countkey;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['ticket_count'] = $counts['ticket_count'];
						$finalWinnerLevel[$prize_level_array[$level_decider]]['ticket_id'] = $counts['ticket_id'];
						$finalWinnerLevel[$prize_level_array[$level_decider]]['level_number'] = $countkey.' out of '.$numlottball;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['prize_amount'] = $totalamountPrizeLevel[$prize_level_array[$level_decider]];
					}					
				} else if(!empty($WinnerTicketCompare) && !empty($totalamountPrizeLevel) && $prize_level>0) {
					$WinnerTicketCompareOut = array_slice($WinnerTicketCompare,0,$prize_level,true);	
					foreach($WinnerTicketCompareOut as $winnerlevelkey => $WinnerTicketCompareone){
						$level_decider = $numlottball-$winnerlevelkey;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['ticket_count'] = 0;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['ticket_id']    = '';
						$finalWinnerLevel[$prize_level_array[$level_decider]]['level_number'] = $winnerlevelkey.' out of '.$numlottball;
						$finalWinnerLevel[$prize_level_array[$level_decider]]['prize_amount'] = $totalamountPrizeLevel[$prize_level_array[$level_decider]];
					}

				}
				if(!empty($finalWinnerLevel)) {
					$data['prize_count'] = $finalWinnerLevel;
				}
			}
		}		
		$this->set('data', $data);
		$this->set('currency', Configure::read('Settings.currency'));
	    //echo '<pre>';print_r($data);echo '</pre>';die;		
	}

	function ticketrequest($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		$user_id    = $this->Session->read('Auth.User.id');
		$lottery_id = $id;
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.id DESC';
		$this->paginate['conditions'] = array(
            'LotteryTicket.agent_id'   => $user_id,
			'LotteryTicket.lottery_id' => $lottery_id,
		    'LotteryTicket.status'     => 5
        );
        $data = $this->paginate('LotteryTicket');	
		$this->set('data', $data);
		$this->set('totalitemsPage',count($data));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function tktreqlist($lottery_id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		if ($this->Session->read('printedData')) {					
			$this->Session->delete('printedData');
		}
		$user_id = $this->Session->read('Auth.User.id');
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryTicket.id DESC';
		$this->paginate['conditions'] = array(
            'LotteryTicket.agent_id' => $user_id,
			'LotteryTicket.lottery_id' => $lottery_id,
		    'LotteryTicket.ticket_id <>' => ''
        );
        $data = $this->paginate('LotteryTicket');
		if(!empty($data)) {
			foreach($data as $key => $dataone){
				$LotteryTypeDetail = $this->LotteryType->getLotteryType($dataone['Lottery']['lottery_type']);
				if(isset($LotteryTypeDetail[0]['LotteryType']['lottery_type'])){
				   $data[$key]['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				}

				$draw_date  = $dataone['LotteryTicket']['draw_date'];
				$lottery_id = $dataone['LotteryTicket']['lottery_id'];
				$options['conditions'] = array(
					'LotteryTicket.lottery_id' => $lottery_id,
					'LotteryTicket.draw_date' => $draw_date,
					'LotteryTicket.status' => 1,
					'LotteryTicket.win_ticket <>' => ''
				);
				$winData  = $this->LotteryTicket->find('first', $options);
				if(!empty($winData['LotteryTicket']['win_ticket'])){
					$data[$key]['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
					$data[$key]['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
					$data[$key]['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
				} else { 
					$data[$key]['winData']['win_ticket'] = '';
					$data[$key]['winData']['win_date']   = '';
					$data[$key]['winData']['draw_date']  = $draw_date;
				}
			}
		}
		$this->set('data', $data);
		$this->set('totalitemsPage',count($data));
		$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function random_ticket($lottery_id=NULL, $ticket_id=NULL){

		//checking session for publick method
		parent::checkSession();

		//on submitting delete botton of the form
		if(!empty($this->request->data['LotteryTicket']['deleteTicket'])){
			$lottery_id = (isset($this->request->data['LotteryTicket']['lottery_id']))?trim($this->request->data['LotteryTicket']['lottery_id']):'';
			$ticketid = (isset($this->request->data['LotteryTicket']['ticket_id']))?trim($this->request->data['LotteryTicket']['ticket_id']):'';
			$returnAction = $this->__action_delete($ticketid);
			if($returnAction) {
				$this->__setMessage(__('Ticket has been deleted successfully', true));
				$this->redirect(array('action' => 'ticketrequest',''.$lottery_id.'','arequest'));
				exit;
			} else {
				$this->__setError(__('Internal Erorr occurs.Please try again.', true));
			    $this->redirect(array('action' => 'random_ticket',''.$lottery_id.'',$ticketid));
				exit;
			}
		}
		
		//on submitting sumit botton of the form
		if(!empty($this->request->data['LotteryTicket']['submitTicket'])){
			$lottery_id = (isset($this->request->data['LotteryTicket']['lottery_id']))?trim($this->request->data['LotteryTicket']['lottery_id']):'';
			$ticketid = (isset($this->request->data['LotteryTicket']['ticket_id']))?trim($this->request->data['LotteryTicket']['ticket_id']):'';
			$user_id = (isset($this->request->data['LotteryTicket']['user_id']))?trim($this->request->data['LotteryTicket']['user_id']):'';
			$num_lott_ball = (isset($this->request->data['LotteryTicket']['num_lott_ball']))?trim($this->request->data['LotteryTicket']['num_lott_ball']):'';
			$lottery_fee = (isset($this->request->data['LotteryTicket']['lottery_fee']))?trim($this->request->data['LotteryTicket']['lottery_fee']):'';
			$is_stuff = (isset($this->request->data['LotteryTicket']['is_stuff']))?trim($this->request->data['LotteryTicket']['is_stuff']):'';

			if($lottery_fee=='' && $is_stuff=='' && $num_lott_ball == 0 && $lottery_id=='' && $ticketid=='' && $user_id=='') {
				$this->__setError(__('Internal Erorr occurs.Please try again.', true));
			    $this->redirect(array('action' => 'random_ticket',''.$lottery_id.'',$ticketid));
				exit;
			}

			$ticketUser = $this->User->userData($user_id);
			if(!empty($ticketUser)) {
				$this->__setError(__('Please enter the registered user ID.', true));
			    $this->redirect(array('action' => 'random_ticket',''.$lottery_id.'',$ticketid));
				exit;
			}
			//Checking Balance and Taking Lottery Fee by deducting User Balance.
			$user_id      = $this->Session->read('Auth.User.id');
			$agent_perct  = $this->Session->read('Auth.User.agent_perct');
			$user_balance = $this->Session->read('Auth.User.balance');			
			if(isset($user_balance) && $user_balance < 0){
				$this->__setError(__('You donot have sufficient balance in your account.', true));
			    $this->redirect(array('action' => 'random_ticket',''.$lottery_id.'',$ticketid));
				exit;
			}

			//Agent percentage calculation
            $perct_lottery_fee  = number_format((($lottery_fee * $agent_perct)/100),2);
			$actual_lottery_fee = number_format(($user_balance - ($lottery_fee - $perct_lottery_fee)),2);

			//Generating Random Ticket
			$range_start		  = 1;
			$range_end			  = 49;
			$random_string_length = $num_lott_ball;
			$randomLotteryNumber  = parent::__randomNumGenerator($range_start,$range_end,$random_string_length);

			if(isset($randomLotteryNumber) && $randomLotteryNumber=='') {
				$this->__setError(__('Internal Erorr occurs.Please try again.', true));
			    $this->redirect(array('action' => 'random_ticket',''.$lottery_id.'',$ticketid));
				exit;
			}
			if(isset($is_stuff) && $is_stuff==1) {
				   $stuff_ball      = end(explode(',',$randomLotteryNumber));
			       $ticketnumberArr = explode(',',$randomLotteryNumber);
			       if(isset($ticketnumberArr))
			       unset($ticketnumberArr[count($ticketnumberArr)-1]);
			       $ticket_id  = implode(',',$ticketnumberArr);
			} else {
				$stuff_ball = '';
				$ticket_id  = $randomLotteryNumber;				
			}
			
			$status     = 0;
			$ticket_id  = $ticket_id;
			$stuffBall  = $stuff_ball_str = '';
			if($stuff_ball!=''){
			   $stuffBall  = $stuff_ball;
			   $stuff_ball_str = "`stuff_ball`= '".$stuff_ball."' ,";
			}
			$added_on   = date('Y-m-d H:i:s');
			$coloum_field_value_str = " `status` = 0, `ticket_id`= '".$ticket_id."' , ".$stuff_ball_str." `added_on`= '".$added_on."' ";
			$update = $this->LotteryTicket->saveManyGlobalData($table_name = 'lottery_tickets', $coloum_field_value_str, $updated_on_field='id', $updated_on_value = $ticketid , $otherfields = '');
			if($update) {
				$this->LotteryTicket->saveGlobalData($table_name='users',$coloum_field='balance', $coloum_value=$actual_lottery_fee,$updated_on_field='id',$updated_on_value=$user_id,$otherfields='');
				$this->Session->write('uniqidTicketId', $ticketid);	
				$options['conditions'] = array('LotteryTicket.id' => $ticketid);
				$lotteryDetail         = $this->LotteryTicket->find('first', $options);
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lotteryDetail['Lottery']['lottery_type']);
				if(!empty($lotteryDetail) && !empty($LotteryTypeDetail)){
					$lotteryDetail['LotteryTicket']['ticket_id']   = $ticket_id;
					$lotteryDetail['LotteryTicket']['stuff_ball']  = $stuffBall;
					$lotteryDetail['Lottery']['lottery_type_name'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
					$postDataInfo['LotteryTicket']  = $lotteryDetail['LotteryTicket'];
					$postDataInfo['Lottery']		= $lotteryDetail['Lottery'];
					$postDataInfo['User']			= $lotteryDetail['User'];
					$this->Session->write('ticketData', $postDataInfo);		
					$this->set('currency', Configure::read('Settings.currency'));
				}
			    $this->redirect(array('action' => 'ticket_print',$lottery_id,'ticket'));
				exit;
			} else {
				$this->Session->delete('uniqidTicketId');	
				$this->Session->delete('ticketData');
				$this->__setError(__('Internal error occur.Try again.', true));
			    $this->redirect(array('action' => 'random_ticket',''.$lottery_id.'',$ticketid));
				exit;
			}
		}

		$defaultTitleArry = explode(':',Configure::read('Settings.defaultTitle'));
		if(isset($defaultTitleArry[0])){
		  $this->set('SITE_URL', $defaultTitleArry[0]);
		}
		$data = array();
		if (is_null($lottery_id) && is_null($ticket_id)) {
            $this->redirect(array('controller' => '/'));
        }
		$options['conditions'] = array('LotteryTicket.id' => $ticket_id);
		$data   = $this->LotteryTicket->find('first', $options);
		if(isset($data['LotteryTicket']['ticket_id']) && $data['LotteryTicket']['ticket_id']!=''){
			$this->__setError(__('This ticket has been already created.', true));
			$this->redirect(array('action' => 'tktreqlist'));
			exit;
		}
		if(!empty($data)) {
			$LotteryTypeDetail     = $this->LotteryType->getLotteryType($data['Lottery']['lottery_type']);
			if(!empty($LotteryTypeDetail)) {
				foreach($LotteryTypeDetail as $key => $LotteryType){					
					$data['Lottery']['lottery_type_name'] = $LotteryType['LotteryType']['lottery_type'];
				}	
			}
			$draw_date  = $data['LotteryTicket']['draw_date'];
			$lottery_id = $data['LotteryTicket']['lottery_id'];
			$options['conditions'] = array(
				'LotteryTicket.lottery_id' => $lottery_id,
				'LotteryTicket.draw_date' => $draw_date,
				'LotteryTicket.status' => 1
			);
		    $winData         = $this->LotteryTicket->find('first', $options);
			$data['winData']['win_ticket'] = $winData['LotteryTicket']['win_ticket'];
			$data['winData']['win_date']   = $winData['LotteryTicket']['win_date'];
			$data['winData']['draw_date']  = $winData['LotteryTicket']['draw_date'];
		}
		$this->set('data', $data);
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function request_ticket($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		//on sending ticket request
		if(!empty($this->request->data)) {			
			$lottery_id = (isset($this->request->data['LotteryTicket']['lottery_id']))?trim($this->request->data['LotteryTicket']['lottery_id']):'';
			$lottery_fee = (isset($this->request->data['LotteryTicket']['lottery_fee']))?trim($this->request->data['LotteryTicket']['lottery_fee']):'';
			$agent_id = (isset($this->request->data['LotteryTicket']['agent_id']))?trim($this->request->data['LotteryTicket']['agent_id']):'';
			$user_id = (isset($this->request->data['LotteryTicket']['user_id']))?trim($this->request->data['LotteryTicket']['user_id']):'';
			$ticket_id = (isset($this->request->data['LotteryTicket']['ticket_id']))?trim($this->request->data['LotteryTicket']['ticket_id']):'';
			$stuff_ball = (isset($this->request->data['LotteryTicket']['stuff_ball']))?trim($this->request->data['LotteryTicket']['stuff_ball']):'';
			$draw_date = (isset($this->request->data['LotteryTicket']['draw_date']))?trim($this->request->data['LotteryTicket']['draw_date']):'';

			if($agent_id=='0') {
			    $this->__setError(__('Please select a Agent', true));
			    $this->redirect(array('action' => 'request_ticket',''.$lottery_id.'','request'));
				exit;
			}
			$datasave['LotteryTicket']['status']	  = 5;
			$datasave['LotteryTicket']['user_id']	  = $user_id;
			$datasave['LotteryTicket']['agent_id']	  = $agent_id;
			$datasave['LotteryTicket']['lottery_id']  = $lottery_id;
			$datasave['LotteryTicket']['lottery_fee'] = $lottery_fee;
			$datasave['LotteryTicket']['ticket_id']   = $ticket_id;
			$datasave['LotteryTicket']['stuff_ball']  = $stuff_ball;
			$datasave['LotteryTicket']['draw_date']   = $draw_date;
			$datasave['LotteryTicket']['added_on']	  = date('Y-m-d H:i:s');
			if($this->LotteryTicket->save($datasave)) {						
				$this->__setMessage(__('Ticket Request has been sent successfully', true));
			    $this->redirect(array('controller' => 'lotterys','action' => 'view',''.$lottery_id.'','about'));
				exit;
			} else {
				$this->__setError(__('Internal error occur.Try again.', true));
			    $this->redirect(array('action' => 'request_ticket',''.$lottery_id.'','request'));
				exit;
			}
		}

		if (is_null($id) && is_null($action)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$user_id = $this->Session->read('Auth.User.id');
		$this->set('user_id', $user_id);		
		$allAgents = $this->User->allAgent(8);
		$agentsOption = array('0'=> 'Select Agent Name');
		if(!empty($allAgents)) {
		foreach($allAgents as $key => $agents){
			$agentsOption[$agents['User']['id']]   = $agents['User']['username'];
		}
		$this->set('agentsOption', $agentsOption);
		}
		$options['conditions'] = array('Lottery.id' => $id);
		$data   = $this->Lottery->find('first', $options);
		$activeLotteryTypes = $this->LotteryType->getLotteryType($data['Lottery']['lottery_type']);
		if(isset($activeLotteryTypes[0]['LotteryType']['lottery_type'])){
		  $data['Lottery']['lottery_type_name']  = $activeLotteryTypes[0]['LotteryType']['lottery_type'];
		}
		$this->set('data', $data);
		$this->set('currency', Configure::read('Settings.currency'));
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	//*** /FRONT SECTION FUNCTION *****************************************
}
?>