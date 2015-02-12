<?php
class DepositCreditsController extends AppController {
	
	public $name = 'DepositCredits';
	public $uses = array('User', 'DepositCredit', 'Deposit', 'DepositMeta');


    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_index', 'admin_add', 'admin_action', 'admin_delete'));
    }

	public function admin_index(){

		$userId = $this->Auth->user('id');	

		$userDetail = $this->Auth->user(null);
		
		if($userDetail['group_id'] != 2){
			$this->paginate['conditions'] = array(
				'DepositCredit.agent_id' => $userId
			);
		}

        $this->paginate['limit'] = Configure::read('Settings.itemsPerPage');;
        $this->paginate['order'] = 'DepositCredit.date_added DESC';
        $data = $this->paginate('DepositCredit');
		
		$this->set('userdetail', $userDetail);

		$users = $this->User->find('list');
        $this->set('data', $data);
		$this->render('admin_index');
		
	}

	public function admin_add(){

		$filteruserjsonArray = $player_idArray = array();
		$flagerror  = $playerStr = '';

		$userId = $this->Auth->user('id');	
		$userDetail = $this->Auth->user(null);

		if($userDetail['balance'] <=0){
			$this->__setError(__('You donot have balance in account to perform this. Please buy some credits first.', true));
			$this->redirect(array(
				'controller' => 'deposit_credits',
				'action' => 'index'
			));
		}

		$users = $this->User->find('list', array('conditions' => array('User.group_id' => 1), 'fields' => array('User.id', 'User.username')));

		if(!empty($users)) {
			foreach($users as $key => $value){
				$filteruserjsonArray[] = array('id' => trim($key), 'name' => trim($value));
			}
			$filteruserJson = json_encode($filteruserjsonArray);
		}

		if (!empty($this->request->data)) {	
			
			$amount				= $this->request->data['User']['credits'];
			$playerStr		    = $this->request->data['User']['player_id'];
			$availablebalance	= $this->request->data['User']['availablecredits'];

			if($amount== '' || !is_numeric($amount)){
				$this->__setError(__('Please enter valid no. of credits', true));
			} else	if($amount > $availablebalance){
				$this->__setError(__('Credits are more than the available balance of ('.$availablebalance.')', true));
			} else if(isset($playerStr) && $playerStr == '') {
				$this->__setError(__('Please enter player name', true));
			} else {	
				
				if(isset($playerStr) && $playerStr != '') {

					$player_idArray	= explode(';',$playerStr);

					foreach($player_idArray as $player_id){							
						if($player_id!=''){
							
							//saving data in credits_transfered table
							$savecreditstransfered = $this->DepositCredit->query("insert into `credits_transfered` SET agent_id ='".$userId."',user_id ='".$player_id."',credits ='".$amount."',status ='1',date_added ='".date('Y-m-d H:i:s')."' ");

							//saving data in deposits table
							$deposit_id = uniqid($player_id . '-');
							$type = 'Deposit by agent ('.$userDetail['username'].') agent ID ('.$userDetail['id'].') to the player ID ('.$player_id.')';

							$savedeposits = $this->Deposit->query("insert into `deposits` SET user_id ='".$userId."', type ='".$type."', amount ='".$amount."',date ='".date('Y-m-d H:i:s')."', deposit_id ='".$deposit_id."', status ='1', details ='completed'  ");	
							
							//saving data in deposit_metas table
							$savedepositmetas = $this->DepositMeta->query("insert into `deposit_metas` SET user_id ='".$player_id."',amount ='".$amount."',created='".date('Y-m-d H:i:s')."', deposit_id ='".$deposit_id."' ");

							//saving data in users table							
							$this->Deposit->User->addFunds($player_id, $amount);							
							$amountdeducted =  -1 * abs($amount);
							$this->Deposit->User->addFunds($userId, $amountdeducted);	
						} else {
							$flagerror = 'error';
						}
					}

					if(isset($flagerror) && $flagerror =='') {
						$this->__setMessage(__('Credits Transfered to Player Account. You Have 24 Hours to Request Refund for this Credits.', true));		
					} else {
						$this->__setError(__('Due to some error credits could not be transfered', true));
					}
				}				

				$this->redirect(array(
					'controller' => 'deposit_credits',
					'action' => 'index'
				));
			}
		}

		$this->set('filteruserJson', $filteruserJson);
		$this->set('userdetail', $userDetail);
		$this->render('admin_add');
	}

	public function admin_delete($id = null){
		 if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }

        if ($this->DepositCredit->delete($id)) {
            $this->__setMessage(__('Entry deleted successfully', true));
            $this->redirect(array(
                'controller' => 'deposit_credits',
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }

	}

	public function admin_action($id, $status){
		
		$this->request->data['DepositCredit']['status'] = $status;
		$this->request->data['DepositCredit']['id'] = $id;
		
		if($this->DepositCredit->save($this->request->data)){
			$this->__setMessage(__('Action performed successfully', true));
		} else {
			$this->__setError(__('Action could not be performed successfully', true));
		}

		$this->redirect(array(
			'controller' => 'deposit_credits',
			'action' => 'index'
		));

	}

}
?>