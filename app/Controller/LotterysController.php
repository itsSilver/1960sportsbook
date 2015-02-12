<?php
/******************************************
* @Created on Nov 18, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class LotterysController extends AppController {

    public $name = 'Lotterys';
	public $uses = array('Lottery','LotteryType','LotteryTicket');

    function beforeFilter() {
        parent::beforeFilter();		
        $this->Auth->allow(array('get_pastlotteryresult','get_pastresultajax','get_lotteryresult','chance','getLottery','view','admin_create','admin_view','admin_list','admin_edit','admin_delete','admin_levels','admin_winners','admin_amount','admin_levelprizeajax','admin_prizeamountajax','admin_jackpotset','admin_jackpotsetajax','admin_system','admin_drawdate'));
    }

	//Global function *****************************************************
	function __action_delete($contentid){
		if($this->Lottery->delete($contentid)) {
			return true;
		} else {
			return false;
		}
	}
	//Global function *****************************************************

	//ADMIN SECTION FUNCTION ***********************************************
	
	function admin_drawdate() {

		//checking session for publick method
		parent::checkSession();

		//CODE FOR AJAX REQUEST
		if(isset($this->request->data['lottery_id']) && isset($this->request->data['draw_time'])) {
			$lottery_id  = $this->request->data['lottery_id'];
			$draw_time   = $this->request->data['draw_time'];
			if($this->Lottery->savelevelprizeData($lottery_id,$level_perct_field='draw_time',$draw_time)) {
				echo 'Saved';exit;
			} else {
				echo 'Error';exit;
			}
		}
		$dataAll = array();
		$this->paginate['conditions'] = array('Lottery.is_active' => 1);
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'Lottery.id DESC';
        $data = $this->paginate('Lottery');
		if(!empty($data)){
			foreach($data as $key => $records){
				$activeLotteryTypes = $this->LotteryType->getLotteryType($records['Lottery']['lottery_type']);
				if(isset($activeLotteryTypes[0]['LotteryType']['lottery_type'])){
				   $data[$key]['Lottery']['lottery_type_name']  = $activeLotteryTypes[0]['LotteryType']['lottery_type'];
				}
			}
			foreach($data as $key => $dataone){
				$dataAll[$dataone['Lottery']['lottery_type_name']][$dataone['Lottery']['id']]['lottery_type_name'] = $dataone['Lottery']['lottery_type_name'];
				$dataAll[$dataone['Lottery']['lottery_type_name']][$dataone['Lottery']['id']]['lottery_id'] = $dataone['Lottery']['id'];
				$dataAll[$dataone['Lottery']['lottery_type_name']][$dataone['Lottery']['id']]['lottery_name'] = $dataone['Lottery']['name'];
				$dataAll[$dataone['Lottery']['lottery_type_name']][$dataone['Lottery']['id']]['draw_time'] = $dataone['Lottery']['draw_time'];
			}
			$this->set('data', $dataAll);
		}
		//echo '<pre>';print_r($dataAll);echo '</pre>';die;
	}

	function admin_create() {

		//checking session for publick method
		parent::checkSession();

		//LIRABARY OF DAY OPTION
		$day_option_array  = array('01'=>'MON','02'=>'TUE','03'=>'WED','04'=>'THRS','05'=>'FRI','06'=>'SAT','07'=>'SUN');
		$day_option = array();
		foreach($day_option_array as $key => $value){
			$day_option[] = array('id' => trim($key), 'name' => trim($value));
		}
		//LIRABARY OF HOUR OPTION
		$hour_option = array();
		for($i=1;$i<=24;$i++){
		   if(strlen($i)==1){
			   $hour_option[] = array('id' => trim('0'.$i), 'name' => trim('0'.$i));
		   } else {
		       $hour_option[] = array('id' => trim($i), 'name' => trim($i));
		   }
		}
		//LIRABARY OF MINUTUES OPTION
		$min_option = array();
		for($i=0;$i<=60;$i++){
		   if(strlen($i)==1){
			   $min_option[] = array('id' => trim('0'.$i), 'name' => trim('0'.$i));
		   } else {
		       $min_option[] = array('id' => trim($i), 'name' => trim($i));
		   }
		}

		$this->set('day_option', $day_option);
		$this->set('hour_option', $hour_option);
		$this->set('min_option', $min_option);
		$this->set('day_option_json', json_encode($day_option));
		$this->set('hour_option_json', json_encode($hour_option));
		$this->set('min_option_json', json_encode($min_option));

		//CODE FOR DISPLAYING ALL LOTTERY TYPE GAME IN A SELECTBOX.
		$lotterytypeOption = array();
		$activeLotteryTypes = $this->LotteryType->activeLotteryType();
		if(!empty($activeLotteryTypes)){		
			foreach ($activeLotteryTypes as $lotteryKey => $lotteryTypes) {
				$lotterytypeOption[$lotteryTypes['LotteryType']['id']]=$lotteryTypes['LotteryType']['lottery_type'];
			}
			$this->set('lotterytypeOption',$lotterytypeOption);
			$this->set('currency', Configure::read('Settings.currency'));
		}
		if(!empty($this->request->data)){
			$formdata = $datasave = array();
			$this->Session->write('postedData', $this->request->data['Lottery']);
			if($this->request->data['Lottery']['name']=='') {
				$this->__setError(__('Please enter the lottery name', true));
                $this->redirect(array('action' => 'admin_create'));				
			} else if($this->request->data['Lottery']['num_lott_ball']=='') {
				$this->__setError(__('Please enter the number of ball', true));
                $this->redirect(array('action' => 'admin_create'));
			} else if($this->request->data['Lottery']['prize_level']=='') {
				$this->__setError(__('Please enter the lottery prize level', true));
                $this->redirect(array('action' => 'admin_create',''.$id.'','edit'));
			} else if($this->request->data['Lottery']['lottery_fee']=='') {
				$this->__setError(__('Please enter the lottery fee', true));
                $this->redirect(array('action' => 'admin_create'));
			} else if($this->request->data['Lottery']['logo']['name']=='') {
				$this->__setError(__('Please select the lottery logo', true));
                $this->redirect(array('action' => 'admin_create'));
			} else if($this->request->data['Lottery']['day_option']=='') {
				$this->__setError(__('Please enter the lottery day option', true));
                $this->redirect(array('action' => 'admin_create'));
			} else if($this->request->data['Lottery']['hour_option']=='') {
				$this->__setError(__('Please enter the lottery hour option', true));
                $this->redirect(array('action' => 'admin_create'));
			} else if($this->request->data['Lottery']['min_option']=='') {
				$this->__setError(__('Please enter the lottery minute option', true));
                $this->redirect(array('action' => 'admin_create'));			
			} else {	
				
				$defaultday      = date('w');
				$hour_option_val = trim($this->request->data['Lottery']['hour_option']);
				$min_option_val  = trim($this->request->data['Lottery']['min_option']);
				$day_option_Str  = trim($this->request->data['Lottery']['day_option']);
				$day_option_Arr  = explode(';',$day_option_Str);
				if(!empty($day_option_Arr)){
					foreach($day_option_Arr as $day_option_val){
						$draw_time_arr[]   = trim(date('Y-m-d', strtotime('+'.($day_option_val-$defaultday).' days')).' '.$hour_option_val.':'.$min_option_val.':'.'01');
					}
					if(!empty($draw_time_arr))
					sort($draw_time_arr);
					$draw_time = implode('/',$draw_time_arr);
				}				
				$folder					= 'img/lottery/';
				$formdata['Lottery']	= $this->request->data['Lottery']['logo'];
				$returnImagename		= parent::__uploadFiles($folder, $formdata, $itemId = null);

				if(!isset($draw_time)){
					$this->__setError(__('Please select the Lottery draw time.', true));
					$this->redirect(array('action' => 'admin_create'));
					exit;
				} 
					
				if(!isset($returnImagename['urls']['0'])){
					$this->__setError(__('Please select the small lottery logo', true));
					$this->redirect(array('action' => 'admin_create'));
					exit;
				} 
				$newimageName						   = $returnImagename['urls']['0'];
				$datasave['Lottery']['name']		   = $this->request->data['Lottery']['name'];
				$datasave['Lottery']['lottery_type']   = $this->request->data['Lottery']['lottery_type'];
				$datasave['Lottery']['num_lott_ball']  = $this->request->data['Lottery']['num_lott_ball'];
				$datasave['Lottery']['prize_level']	   = $this->request->data['Lottery']['prize_level'];
				$datasave['Lottery']['lottery_fee']    = $this->request->data['Lottery']['lottery_fee'];
				$datasave['Lottery']['logo']	       = $newimageName;
				$datasave['Lottery']['is_stuff']	   = $this->request->data['Lottery']['is_stuff'];
				$datasave['Lottery']['draw_time']	   = $draw_time;
				$datasave['Lottery']['is_active']	   = $this->request->data['Lottery']['is_active'];
				$datasave['Lottery']['added_on']	   = date('Y-m-d H:i:s');				
							
				if ($this->Lottery->save($datasave)) {	
					$Lottery_id = $this->Lottery->id;
					$this->Session->delete('postedData');					
					$this->__setMessage(__('Lottery detail has been saved.Please assign Lottery Game Level Prize.', true));
					if(isset($this->request->data['Lottery']['is_active']) && $this->request->data['Lottery']['is_active'] == 0){
						$this->redirect(array('action' => 'admin_list'));
					} else {
					   $this->redirect(array('action' => 'admin_levels',$Lottery_id));
					}
					exit;
				} else {
					$this->__setError(__('Internal error occur.Try again.', true));
					$this->redirect(array('action' => 'admin_create'));
					exit;
				}
			}		
		}
	}

	function admin_edit($id=NULL, $action=NULL) {

		//checking session for publick method
		parent::checkSession();

		if (is_null($id) && is_null($action)) {
            $this->redirect(array('action' => 'admin_list'));	
        }

		//LIRABARY OF DAY OPTION
		$day_option_array  = array('01'=>'MON','02'=>'TUE','03'=>'WED','04'=>'THRS','05'=>'FRI','06'=>'SAT','07'=>'SUN');
		$day_option = array();
		foreach($day_option_array as $key => $value){
			$day_option[] = array('id' => trim($key), 'name' => trim($value));
		}
		//LIRABARY OF HOUR OPTION
		$hour_option = array();
		for($i=1;$i<=24;$i++){
		   if(strlen($i)==1){
			   $hour_option[] = array('id' => trim('0'.$i), 'name' => trim('0'.$i));
		   } else {
		       $hour_option[] = array('id' => trim($i), 'name' => trim($i));
		   }
		}
		//LIRABARY OF MINUTUES OPTION
		$min_option = array();
		for($i=0;$i<=60;$i++){
		   if(strlen($i)==1){
			   $min_option[] = array('id' => trim('0'.$i), 'name' => trim('0'.$i));
		   } else {
		       $min_option[] = array('id' => trim($i), 'name' => trim($i));
		   }
		}

		$this->set('day_option', $day_option);
		$this->set('hour_option', $hour_option);
		$this->set('min_option', $min_option);
		$this->set('day_option_json', json_encode($day_option));
		$this->set('hour_option_json', json_encode($hour_option));
		$this->set('min_option_json', json_encode($min_option));

		//CODE FOR DISPLAYING ALL LOTTERY TYPE GAME IN A SELECTBOX.
		$lotterytypeOption = array();
		$activeLotteryTypes = $this->LotteryType->activeLotteryType();
		if(!empty($activeLotteryTypes)){		
			foreach ($activeLotteryTypes as $lotteryKey => $lotteryTypes) {
				$lotterytypeOption[$lotteryTypes['LotteryType']['id']]=$lotteryTypes['LotteryType']['lottery_type'];
			}
			$this->set('lotterytypeOption',$lotterytypeOption);
		}
		$this->paginate['conditions'] = array(
			'Lottery.id' => $id
		);
        $data = $this->paginate('Lottery');		
        $this->set('data', $data);
		$this->set('currency', Configure::read('Settings.currency'));

		if(!empty($this->request->data)){
			$formdata = $datasave = array();
			if($this->request->data['Lottery']['name']=='') {
				$this->__setError(__('Please enter the lottery name', true));
                $this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));				
			} else if($this->request->data['Lottery']['num_lott_ball']=='') {
				$this->__setError(__('Please enter the number of ball', true));
                $this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
			} else if($this->request->data['Lottery']['prize_level']=='') {
				$this->__setError(__('Please enter the lottery prize level', true));
                $this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
			} else if($this->request->data['Lottery']['lottery_fee']=='') {
				$this->__setError(__('Please enter the lottery fee', true));
                $this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
			} else if($this->request->data['Lottery']['day_option']=='') {
				$this->__setError(__('Please enter the lottery day option', true));
                $this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
			} else if($this->request->data['Lottery']['hour_option']=='') {
				$this->__setError(__('Please enter the lottery hour option', true));
                $this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
			} else if($this->request->data['Lottery']['min_option']=='') {
				$this->__setError(__('Please enter the lottery minute option', true));
                $this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
			} else {
				$defaultday      = date('w');
				$hour_option_val = trim($this->request->data['Lottery']['hour_option']);
				$min_option_val  = trim($this->request->data['Lottery']['min_option']);
				$day_option_Str  = trim($this->request->data['Lottery']['day_option']);
				$day_option_Arr  = explode(';',$day_option_Str);
				if(!empty($day_option_Arr)){
					foreach($day_option_Arr as $day_option_val){
						$draw_time_arr[]   = trim(date('Y-m-d', strtotime('+'.($day_option_val-$defaultday).' days')).' '.$hour_option_val.':'.$min_option_val.':'.'01');
					}
					if(!empty($draw_time_arr))
					sort($draw_time_arr);
					$draw_time = implode('/',$draw_time_arr);
				}
				if(!isset($draw_time)){
					$this->__setError(__('Please select the Lottery draw time.', true));
					$this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
					exit;
				} 
				
				if($this->request->data['Lottery']['logo']['size'] !='0') {
					$folder		 = 'img/lottery/';					
					$oldlogoname = $this->request->data['Lottery']['oldlogo'];
					$removedir   = WWW_ROOT.'img/lottery/'.$oldlogoname;
					//removinf previous Image
					unlink($removedir);
					//uploading New Image
					$formdata['Lottery']	= $this->request->data['Lottery']['logo'];
					$returnImagename		= parent::__uploadFiles($folder, $formdata, $itemId = null);
					if(!isset($returnImagename['urls']['0'])){
						$this->__setError(__('Please select the small lottery logo', true));
						$this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
						exit;
					}
					$recentnewimageName	= $returnImagename['urls']['0'];
				} else {
					$recentnewimageName = $this->request->data['Lottery']['oldlogo'];
				}				
				$datasave['Lottery']['name']		   = $this->request->data['Lottery']['name'];
				$datasave['Lottery']['lottery_type']   = $this->request->data['Lottery']['lottery_type'];
				$datasave['Lottery']['num_lott_ball']  = $this->request->data['Lottery']['num_lott_ball'];
				$datasave['Lottery']['prize_level']	   = $this->request->data['Lottery']['prize_level'];
				$datasave['Lottery']['lottery_fee']    = $this->request->data['Lottery']['lottery_fee'];
				$datasave['Lottery']['logo']	       = $recentnewimageName;
				$datasave['Lottery']['is_stuff']	   = $this->request->data['Lottery']['is_stuff'];
				$datasave['Lottery']['draw_time']	   = $draw_time;
				$datasave['Lottery']['is_active']	   = $this->request->data['Lottery']['is_active'];
				$datasave['Lottery']['added_on']	   = date('Y-m-d H:i:s');	
				
				if ($this->Lottery->saveLotteryData($datasave['Lottery'],$id)) {					
					$this->__setMessage(__('Lottery detail has been updated.', true));
					$this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));		
				} else {
					$this->__setError(__('Internal error occur.Try again.', true));
					$this->redirect(array('action' => 'admin_edit',''.$id.'','edit'));
				}
			}		
		}
	}

    function admin_list() {

		//checking session for publick method
		parent::checkSession();

		$datainfo = array();
		$this->paginate['conditions'] = array('Lottery.is_active' => 1);
		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'Lottery.id DESC';
        $dataAll = $this->paginate('Lottery');	
		if(!empty($dataAll)){
			foreach($dataAll as $key => $records){
				$activeLotteryTypes = $this->LotteryType->getLotteryType($records['Lottery']['lottery_type']);
				if(isset($activeLotteryTypes[0]['LotteryType']['lottery_type'])){
				   $records['Lottery']['lottery_type_name']  = $activeLotteryTypes[0]['LotteryType']['lottery_type'];
				}
				$datainfo[] = $records;
			}
			$this->set('data', $datainfo);
			$this->set('totalitemsPage',count($datainfo));
			$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
			$this->set('currency', Configure::read('Settings.currency'));
			//echo '<pre>';print_r($datainfo);echo '</pre>';die;			
		}       
	}

	function admin_view($id=NULL, $action=NULL) {

		//checking session for publick method
		parent::checkSession();

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
				$datainfo[] = $records;
			}
			$this->set('data', $datainfo);
			$this->set('totalitemsPage',count($datainfo));
			$this->set('itemsPerPage',Configure::read('Settings.itemsPerPage'));
			$this->set('currency', Configure::read('Settings.currency'));
			//echo '<pre>';print_r($datainfo);echo '</pre>';die;
		}     
	}

	public function admin_delete($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$this->paginate['conditions'] = array(
			'Lottery.id' => $id
		);
        $data = $this->paginate('Lottery');
		if(empty($data[0]['Lottery']['logo'])){
			$this->__setMessage(__('Lottery detail not found.', true));
			$this->redirect(array('action' => 'admin_list'));	
			exit;
		}
		$folder		 = 'img/lottery/';					
		$oldlogoname = $data[0]['Lottery']['logo'];
		$removedir   = WWW_ROOT.'img/lottery/'.$oldlogoname;
		//removinf previous Image
		unlink($removedir);

        if($this->Lottery->delete($id)) {
            $this->__setMessage(__('Entry deleted successfully', true));
            $this->redirect(array('controller' => 'lotterys','action' => 'admin_list'));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
	}

	public function admin_levels($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		//unsetting session
		if (is_null($id)) {
			$this->Session->delete('lotteryid');
		}		
		//code for printing level prize value.		
		$option['conditions'] = array(
			'Lottery.id' => $id
		);
		$data = $this->Lottery->find('first',$option);
		if(!empty($data['Lottery'])) {
			$level_data         = explode(',',$data['Lottery']['level_perct']);
			$start_prize_amount = $data['Lottery']['start_prize_amount'];
			$this->set('level_data',$level_data);	
			$this->set('start_prize_amount',$start_prize_amount);	
		}
		//code for displaying all lottery game in a selectbox.
		$lotteryOption = array('0' => 'Select Lottery Game');
		$activeLotterys = $this->Lottery->activeLottery();
		if(!empty($activeLotterys)){		
			foreach ($activeLotterys as $lotteryKey => $lotterys) {
				$lotteryOption[$lotterys['Lottery']['id']]=$lotterys['Lottery']['name'];
			}
			$this->set('lotteryOption',$lotteryOption);			
		}
		//code for displaying all level of the lottery game.
		if($this->request->data['Lottery']['lottery_id']!='') {				
			$id = $this->request->data['Lottery']['lottery_id'];			
			$options['conditions'] = array('Lottery.id' => $id);
			$dataDetail = $this->Lottery->find('first', $options);
			if(empty($dataDetail['Lottery']['prize_level'])){
				$this->__setMessage(__('Lottery detail not found.', true));
				$this->redirect(array('action' => 'admin_list'));
				exit;
			}
			$lotttery_id   = $dataDetail['Lottery']['id'];
			$level_number  = $dataDetail['Lottery']['prize_level'];
			$this->Session->delete('lotteryid');
			$this->Session->write('lotteryid', $lotttery_id);
			$this->redirect(array('action' => 'admin_levels',''.$lotttery_id.'',''.$level_number.''));
			exit;
		}
	}

	//function for adding prize level money
	public function admin_levelprizeajax(){
		$prizemoneyArr = array();	
		$level_perct = '';
		
		if(empty($this->request->data['start_prize_amount'])){
		    echo '1'; exit;
		}
		if(!is_numeric($this->request->data['start_prize_amount'])) {
		    echo '0'; exit;
		}
		$prizemoneyArr   = $this->request->data['prizemoney'];
		if(array_search('', $prizemoneyArr)!==false){
		    echo '1'; exit;
		}
		$level_perct        = implode(',',$prizemoneyArr);
		$start_prize_amount	= $this->request->data['start_prize_amount'];
		$lottery_id	        = $this->request->data['Lottery']['lottery_id'];

		$coloum_field_value_str = " `level_perct` = '".$level_perct."' , `start_prize_amount` = '".$start_prize_amount."' ";
		if($this->Lottery->saveManyGlobalData($table_name='lotterys',$coloum_field_value_str,$updated_on_field='id',$updated_on_value=$lottery_id,$otherfields=null)) {	
		    echo '2'; exit;
		} else {
			echo '3'; exit;
		}
	}

	public function admin_amount($id=NULL, $action=NULL){

		//checking session for publick method
		parent::checkSession();

		//unsetting session
		if (is_null($id)) {
			$this->Session->delete('lotteryid');
		}		
		//code for printing level prize value.
		if (!is_null($id)) {
			$options['conditions'] = array('Lottery.id' => $id);
			$dataDetail = $this->Lottery->find('first', $options);
			if(!empty($dataDetail['Lottery']['prize_perct'])){
				$prize_perct = $dataDetail['Lottery']['prize_perct'];
				$this->set('prize_perct',$prize_perct);	
			}			
		}
		//code for displaying all lottery game in a selectbox.
		$lotteryOption = array('0' => 'Select Lottery Game');
		$activeLotterys = $this->Lottery->activeLottery();
		if(!empty($activeLotterys)){		
			foreach ($activeLotterys as $lotteryKey => $lotterys) {
				$lotteryOption[$lotterys['Lottery']['id']]=$lotterys['Lottery']['name'];
			}
			$this->set('lotteryOption',$lotteryOption);			
		}
		//code for displaying all level of the lottery game.
		if($this->request->data['Lottery']['lottery_id']!='') {				
			$lotttery_id = $this->request->data['Lottery']['lottery_id'];			
			$this->Session->delete('lotteryid');
			$this->Session->write('lotteryid', $lotttery_id);	
			$this->redirect(array('action' => 'admin_amount',''.$lotttery_id.''));
			exit;
		}
	}

	//function for adding prize level money
	public function admin_prizeamountajax(){
		$prize_perct  = $this->request->data['Lottery']['prize_perct'];
		if(isset($prize_perct) && $prize_perct == ''){
		    echo '1'; exit;
		}
		$id = $this->request->data['Lottery']['lottery_id'];
        if($this->Lottery->savelevelprizeData($id,$level_perct_field='prize_perct',$prize_perct)) {		
		    echo '2'; exit;
		} else {
			echo '3'; exit;
		}
	}

	//function for setting Jackpot prize money
	public function admin_jackpotset($id=NULL, $action=NULL){
		//unsetting session
		if (is_null($id)) {
			$this->Session->delete('lotteryid');
		}		
		//code for printing level prize value.
		if (!is_null($id)) {
			$options['conditions'] = array('Lottery.id' => $id);
			$dataDetail = $this->Lottery->find('first', $options);
			if(!empty($dataDetail['Lottery']['jackpot_set'])){
				$jackpot_set = $dataDetail['Lottery']['jackpot_set'];
				$this->set('jackpot_set',$jackpot_set);	
			}			
		}
		//code for displaying all lottery game in a selectbox.
		$lotteryOption = array('0' => 'Select Lottery Game');
		$activeLotterys = $this->Lottery->activeLottery();
		if(!empty($activeLotterys)){		
			foreach ($activeLotterys as $lotteryKey => $lotterys) {
				$lotteryOption[$lotterys['Lottery']['id']]=$lotterys['Lottery']['name'];
			}
			$this->set('lotteryOption',$lotteryOption);			
		}
		//code for displaying all level of the lottery game.
		if($this->request->data['Lottery']['lottery_id']!='') {				
			$lotttery_id = $this->request->data['Lottery']['lottery_id'];			
			$this->Session->delete('lotteryid');
			$this->Session->write('lotteryid', $lotttery_id);	
			$this->redirect(array('action' => 'admin_jackpotset',''.$lotttery_id.''));
			exit;
		}
		
	}

	//function for setting Jackpot prize money Ajax
	public function admin_jackpotsetajax(){
		$jackpot_set  = $this->request->data['Lottery']['jackpot_set'];
		if(isset($jackpot_set) && $jackpot_set == ''){
		    echo '1'; exit;
		}
		$id = $this->request->data['Lottery']['lottery_id'];
        if($this->Lottery->savelevelprizeData($id,$level_perct_field='jackpot_set',$jackpot_set)) {		
		    echo '2'; exit;
		} else {
			echo '3'; exit;
		}
	}

	//FUNCTION FOR SETTING LOTTERY SYSTEM DRAW AND JACKPOT SYSTEM DRAW
	public function admin_system($id=NULL, $action=NULL){

		//CODE FOR AJAX REQUEST.
		if(isset($this->request->data['posttype'])) {			
			$lotttery_id         = $this->request->data['Lottery']['lottery_id'];	
			$lottery_system_draw = $this->request->data['Lottery']['lottery_system_draw'];
			//$jackpot_system_draw = $this->request->data['Lottery']['jackpot_system_draw'];
			$lotterysystemdraw = $this->Lottery->savelevelprizeData($lotttery_id,$level_perct_field='lottery_system_draw',$lottery_system_draw);	
			//$jackpotsystemdraw = $this->Lottery->savelevelprizeData($lotttery_id,$level_perct_field='jackpot_system_draw',$jackpot_system_draw);
			echo '1'; exit;
		}

		//checking session for publick method
		parent::checkSession();

		//unsetting session
		if (is_null($id)) {
			$this->Session->delete('lotteryid');
		}		
		//code for printing level prize value.
		if (!is_null($id)) {
			$options['conditions'] = array('Lottery.id' => $id);
			$dataDetail = $this->Lottery->find('first', $options);
			if(!empty($dataDetail['Lottery']['lottery_system_draw']) && !empty($dataDetail['Lottery']['jackpot_system_draw'])) {
				$lottery_system_draw = $dataDetail['Lottery']['lottery_system_draw'];
				$jackpot_system_draw = $dataDetail['Lottery']['jackpot_system_draw'];
				$this->set('lottery_system_draw',$lottery_system_draw);	
				$this->set('jackpot_system_draw',$jackpot_system_draw);	
			}			
		}
		//code for displaying all lottery game in a selectbox.
		$lotteryOption = array('0' => 'Select Lottery Game');
		$activeLotterys = $this->Lottery->activeLottery();
		if(!empty($activeLotterys)){		
			foreach ($activeLotterys as $lotteryKey => $lotterys) {
				$lotteryOption[$lotterys['Lottery']['id']]=$lotterys['Lottery']['name'];
			}
			$this->set('lotteryOption',$lotteryOption);			
		}

		//code for displaying all level of the lottery game.
		if($this->request->data['Lottery']['lottery_id']!='') {				
			$lotttery_id = $this->request->data['Lottery']['lottery_id'];			
			$this->Session->delete('lotteryid');
			$this->Session->write('lotteryid', $lotttery_id);
			$this->redirect(array('action' => 'admin_system',''.$lotttery_id.''));
			exit;
		}		
	}

	//** /ADMIN SECTION FUNCTION *******************************************


	//*** FRONT SECTION FUNCTION *******************************************
	function chance($id=NULL, $action=NULL) {
		$lotteryDetail = array();		
		if (is_null($id) && is_null($action)) {
            throw new NotFoundException(__l('Invalid request'));
        }		
		$lotteryDetail = $this->Lottery->getLottery($id);
		$lotteryTypes  = $this->LotteryType->getLotteryType($lotteryDetail[0]['Lottery']['lottery_type']);
		$lotteryDetail[0]['Lottery']['lottery_type_name']  = $lotteryTypes[0]['LotteryType']['lottery_type'];
		$this->set('data', $lotteryDetail[0]);
		$this->set('currency', Configure::read('Settings.currency'));
    }

	function getLottery() {
		$lotteryDatainfo = array();
		$this->layout = 'ajax';
		$lotteryData  = $this->Lottery->activeLottery();        
		if(!empty($lotteryData)){
			foreach($lotteryData as $key => $records){
				$activeLotteryTypes = $this->LotteryType->getLotteryType($records['Lottery']['lottery_type']);	
				if(isset($activeLotteryTypes[0]['LotteryType']['lottery_type'])){
				$lotteryDatainfo[$activeLotteryTypes[0]['LotteryType']['lottery_type']][] = $records['Lottery'];
				}
			}
			$this->set('lotteryDatainfo', $lotteryDatainfo);
		} 
    }

	function view($id=NULL, $action=NULL) {			
		$data=array();		
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
			$this->set('currency', Configure::read('Settings.currency'));
		}  
	}
	
	//function for displaing on main page
	function get_lotteryresult(){
		$mainDataArray = array();		
		$options['conditions'] = array(
			'LotteryTicket.status' => 1,
			'LotteryTicket.win_ticket <>' => ''
		);
		$options['order']		= array('LotteryTicket.draw_date DESC');
		$mainData				= $this->LotteryTicket->find('all', $options);
		$optionss['conditions'] = array('Lottery.is_active' => 1);
		$numlotterygame   = $this->Lottery->find('count',$optionss);
		if(!empty($mainData))
		return $mainData  = array_slice($mainData,0,$numlotterygame);
		//echo '<pre>';print_r($mainData);echo '</pre>';die;		
	}

	//function for displaing on main page
	function get_pastlotteryresult(){

		$resultslotteryTable = $resultslotteryInfo = $resultsTableDataArray = $lotteryHeaderData = $resultsTableData = $mainTableDataArray = $lotteryHeaderDataArray = $yearData = $monthArray = $resultslotteryHeader = $mainDataArs = $lotteryOption = $mainDataArrays = $mainDataArray = $mainData = array();
		
		//************* HEADER DATA PART **********************		
		//Year Data
		$yearData = array('00' => '---');
		for($i=1990;$i<=2020;$i++){
			$yearData[$i] = $i;
		}
		//Month Data
		$monthArray = array('00' => '---','01' => 'JAN','02' => 'FEB','03' => 'MAR','04' => 'APR','05' => 'MAY','06' => 'JUN','07' => 'JUL','08' => 'AUG','09' => 'SEP','10' => 'OCT','11' => 'NOV','12' => 'DEC');
		
		$lotteryHeaderArray = $this->LotteryTicket->getalllottteryDrawResultToday($status=1);
		if(!empty($lotteryHeaderArray)){
			foreach($lotteryHeaderArray as $key => $lotteryHeaders){
				$resultslotteryHeader[$lotteryHeaders['lt']['id']]  = $lotteryHeaders['lt']['name'];
			}
		}
		if(isset($this->request->data['lottery_id']) && $this->request->data['lottery_id']!=''){
			$this->set('lotteryidKey', $this->request->data['lottery_id']);
			$lotteryHeaderDataArray = $this->LotteryTicket->getlottteryDrawResult($status=1,$this->request->data['lottery_id']);
		} else {
			 $lotteryHeaderDataArray = $this->LotteryTicket->getalllottteryDrawResultToday($status=1);
		}

		if(!empty($lotteryHeaderDataArray)){
			foreach($lotteryHeaderDataArray as $key => $lotterys){
				$resultsTableData[$lotterys['lt']['id']] = $lotterys['lt']['id'];
				$resultslotteryInfo[$lotterys['lt']['id']]['lottery_name'] = $lotterys['lt']['name'];
				$resultslotteryInfo[$lotterys['lt']['id']]['num_lott_ball'] = $lotterys['lt']['num_lott_ball'];
				$resultslotteryInfo[$lotterys['lt']['id']]['stuff_ball'] = $lotterys['ltk']['stuff_ball'];
			}
		}

		//************* /HEADER DATA PART **********************
		
		//************* TABULAR DATA PART **********************
		if(!empty($resultsTableData)){
			foreach($resultsTableData as $lotteryidkey => $lotteryArr){	
				
				 if(isset($this->request->data['ticket_id'])) {
					$ticketidArray = $this->request->data['ticket_id'];					
					if(isset($ticketidArray) && count(array_filter($ticketidArray)) == count($ticketidArray)) {					
					   $ticketidStr	= implode(',',$ticketidArray);
					   $stisfiedCondition .= " and ltk.ticket_id = '".$ticketidStr."' ";
					}
			     }
			     if(isset($this->request->data['stuff_ball']) && $this->request->data['stuff_ball']!='') { 
					 $stuff_ball = $this->request->data['stuff_ball'];
					 $stisfiedCondition .= " and ltk.stuff_ball = '".$stuff_ball."' ";
			     }
				 if(!empty($this->request->data['getpastresult'])){
					 $ticketidArray = array();$ticketidStr='';
					 extract($this->request->data['getpastresult']);
					 $yearFrom  = $yearFrom;
					 $monthFrom = $monthFrom;
					 $yearTo    = $yearTo;
					 $monthTo   = $monthTo;
					 $this->set('yearFrom', $yearFrom);			
					 $this->set('monthFrom', $monthFrom);
					 $this->set('yearTo', $yearTo);
					 $this->set('monthTo', $monthTo);
					 
					 if($yearFrom!= '00' && $monthFrom!= '00' && $yearTo!= '00' && $monthTo!= '00'){
					 $stisfiedCondition .= " and (YEAR(ltk.win_date) between ".$yearFrom." and ".$yearTo.") and (MONTH(ltk.win_date) between ".$monthFrom." and ".$monthTo.") ";
					 }
				 }				 
				 $resultsTableDataArray[$lotteryidkey] = $this->LotteryTicket->getlottteryDrawResultCondition($lotteryidkey , $stisfiedCondition);
			}

			if(!empty($resultsTableDataArray)){
				foreach($resultsTableDataArray as $key => $lotterysArr){
					foreach($lotterysArr as $keyone => $lotterys){					
						$mainTableData[$lotterys['lt']['id']][$keyone]['lottery_id']        = $lotterys['lt']['id'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['ticket_id']         = $lotterys['ltk']['id'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['lottery_type_id']   = $lotterys['lty']['id'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['lottery_type_name'] = $lotterys['lty']['lottery_type'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['lottery_name']      = $lotterys['lt']['name'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['num_lott_ball']     = $lotterys['lt']['num_lott_ball'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['logo']              = $lotterys['lt']['logo'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['draw_time']         = $lotterys['lt']['draw_time'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['lottery_number']    = $lotterys['ltk']['ticket_id'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['stuff_ball']        = $lotterys['ltk']['stuff_ball'];
						$mainTableData[$lotterys['lt']['id']][$keyone]['win_date']          = $lotterys['ltk']['win_date'];	
						$mainTableData[$lotterys['lt']['id']][$keyone]['win_ticket']        = $lotterys['ltk']['win_ticket'];
					}
				}
			}

			if(!empty($mainTableData)){		
				$mainTableDataArray = array_slice($mainTableData, 0, 1,true);
				$mainDataArrayskey  = array_rand($mainTableDataArray,1);
				$mainDataArrays	    = $mainTableData[$mainDataArrayskey];
				$mainDataArray_out  = array_slice($mainDataArrays, 0, 100,true);
				if($this->request->data['page']){
					  $startpage        = ($this->request->data['page']-1) * 10;
					  $resultslotteryTable = array_slice($mainDataArray_out, $startpage,10,true);
				} else {
					  $resultslotteryTable = array_slice($mainDataArray_out, 0,10,true);
				}
				$totalRows       = count($mainDataArray_out);
				$pages			 = ceil($totalRows/10);
				if($startpage ==0){
					$startpage = 1;
					$this->set('startpage', $startpage);
				} else {
					$this->set('startpage', $startpage);
				}			
				$this->set('lotteryidKey', $mainDataArrayskey);
				$this->set('pages', $pages);
				$this->set('totalRows', $totalRows);
			}			
			$this->set('resultslotteryHeader', $resultslotteryHeader);			
			$this->set('resultslotteryInfo', $resultslotteryInfo);
			$this->set('resultslotteryTable', $resultslotteryTable);
			$this->set('monthArray', $monthArray);
			$this->set('yearData', $yearData);
		}	

		//************* /TABULAR DATA PART **********************
	}

	//*** /FRONT SECTION FUNCTION *******************************************

}
?>