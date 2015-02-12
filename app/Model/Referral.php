<?php

class Referral extends AppModel {
	public $name = 'Referral';
	public $belongsTo = array(
		'User',		
        'SignedUp' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'ReferredBy' => array(
            'className' => 'User',
            'foreignKey' => 'referral_id'
        ),
		'Deposit'
    );
	
	public function addFirstDeposit($user_id)
	{
		
	}
	
	
	public function setStatus($id, $status) {
		$data = $this->getItem($id);
		$data['Referral']['status'] = $status;
		$this->save($data);
	}

	public function getTabs($params) {
		$tabs = array();
		$tabs[] = $this->__makeTab(__('Pending', true), 'index', 'referrals', NULL, false);
		$tabs[] = $this->__makeTab(__('Completed', true), 'completed', 'referrals', NULL, false);
		$tabs[] = $this->__makeTab(__('Canceled', true), 'canceled', 'referrals', NULL, false);
		if ($params['action'] == 'admin_completed') {
			$tabs[1]['active'] = true;
		} else if ($params['action'] == 'admin_canceled') {
			$tabs[2]['active'] = true;
		} else {
			$tabs[0]['active'] = true;
		}
		return $tabs;
	}

	public function getPagination($options = 'pending') {
		$pagination = array(
				'limit' => Configure::read('Settings.itemsPerPage'),
				'fields' => array(
						'Referral.id',
						'SignedUp.username as registered',
						'ReferredBy.username as referredby',
						'Referral.deposit_id',
						'Deposit.amount',
						'Referral.date'
				),
				'conditions' => array(
						'Referral.status' => $options
				)
		);
		return $pagination;
	}
	
	public function complete($id) 
	{			
		$referral = $this->getItem($id);
		$percentage = Configure::read('Settings.referral_deposit_percentage');
		$amount = $referral['Referral']['deposit_amount'];
		$userId = $referral['Referral']['referral_id'];				
		$bonusAmount = $amount*(($percentage)/100);		
		$this->User->addFunds($userId,$bonusAmount);
		$this->setStatus($id, 'completed');
		
	}
	
	
	public function getActions() {
		$actions = array();
		$actions[] = array('name' => __('Complete', true), 'action' => 'complete', 'controller' => NULL);
		$actions[] = array('name' => __('Cancel', true), 'action' => 'cancel', 'controller' => NULL);
		return $actions;
	}
 	/**
     * Implementing date
     * @see Model::beforeSave()
     */
    function beforeSave($options) {    	
    	$dateTime = new DateTime('now');
    	$this->data['Referral']['date'] = $dateTime->format('Y-m-d H:i:s');
    	
    	return true;
    }
}