<?php
/******************************************
* @Created on Dec 04, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class LotteryTypesController extends AppController {

    public $name = 'LotteryTypes';

	function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_type','admin_types','admin_edit','admin_action'));
    }

	function admin_type() {

		//checking session for publick method
		 parent::checkSession();

		if(!empty($this->request->data)){
			$datasave = array();
			$this->Session->write('postedData', $this->request->data['LotteryType']);
			if($this->request->data['LotteryType']['lottery_type']=='') {
				$this->__setError(__('Please enter the lottery Type', true));
                $this->redirect(array('action' => 'admin_type'));
				exit;
			} else {			
				$datasave['LotteryType']['lottery_type'] = $this->request->data['LotteryType']['lottery_type'];
				$datasave['LotteryType']['is_active'] = $this->request->data['LotteryType']['is_active'];
				$datasave['LotteryType']['added_on']	= date('Y-m-d H:i:s');				
							
				if ($this->LotteryType->save($datasave)) {					
					$this->Session->delete('postedData');					
					$this->__setMessage(__('Lottery Type has been saved.', true));
					$this->redirect(array('action' => 'admin_types'));			
				} else {
					$this->__setError(__('Internal error occur.Try again.', true));
					$this->redirect(array('action' => 'admin_type'));
				}
			}		
		}		
    }

	function admin_edit($id=NULL, $action=NULL) {	
		
		//checking session for publick method
		parent::checkSession();
		
		//if submit button is clicked
		if(!empty($this->request->data)){
			
			$datasave = array();
			if($this->request->data['LotteryType']['lottery_type']=='') {
				$this->__setError(__('Please enter the lottery Type', true));
               $this->redirect(array('action' => 'admin_edit',$id,'edit'));
				exit;
			} else {				
				$id           = $this->request->data['LotteryType']['id'];
				$lottery_type = $this->request->data['LotteryType']['lottery_type'];
				$is_active    = $this->request->data['LotteryType']['is_active'];				
				$coloum_field_value_str = " `lottery_type`= '".$lottery_type."' , `is_active`= '".$is_active."' ";
				$update = $this->LotteryType->saveManyGlobalData($table_name = 'lottery_types', $coloum_field_value_str, $updated_on_field ='id', $updated_on_value = $id, $otherfields = '');							
				if ($update) {										
					$this->__setMessage(__('Lottery Type has been updated.', true));
					$this->redirect(array('action' => 'admin_edit',$id,'edit'));			
				} else {
					$this->__setError(__('Internal error occur.Try again.', true));
					$this->redirect(array('action' => 'admin_edit',$id,'edit'));
				}
			}
		}
		if (is_null($id) && is_null($action)) {
            $this->redirect(array('controller' => '/'));
        }
		$option['conditions'] = array(
			'LotteryType.id' => $id
		);
        $data = $this->LotteryType->find('first',$option);		
        $this->set('data', $data);
		//echo '<pre>';print_r($data);echo '</pre>';die;
	}

	function admin_types() {

		//checking session for publick method
		 parent::checkSession();

		$this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->paginate['order'] = 'LotteryType.id DESC';
        $data = $this->paginate('LotteryType');		
        $this->set('data', $data);
	}

	function admin_action($id=NULL, $action=NULL) {	

		if (is_null($id) && is_null($action)) {
            $this->redirect(array('controller' => '/'));
        }
		if(isset($action) && $action=='active'){
			$coloumStr = " SET `is_active` = 1 "; 
			$message   = "Lottery Type has been activated.";
		} else {
			$coloumStr = " SET `is_active` = 0 ";
			$message   = "Lottery Type has been deactivated.";
		}		
		$activedeactiveStatus = $this->LotteryType->global_action($DMLTtype='UPDATE',$tablename= 'lottery_types',$coloumStr,$updated_field='id',$updated_value=$id);
		$activelotteryStatus = $this->LotteryType->global_action($DMLTtype='UPDATE',$tablename= 'lotterys',$coloumStr,$updated_field='lottery_type',$updated_value=$id);
		if ($activedeactiveStatus && $activelotteryStatus) {						
			$this->__setMessage(__($message, true));
			$this->redirect(array('action' => 'admin_types'));			
		} else {
			$this->__setError(__($message, true));
			$this->redirect(array('action' => 'admin_action',$id,'edit'));
		}
	}
    
}

?>
