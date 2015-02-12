<?php

class Withdraw extends AppModel {

    public $name = 'Withdraw';
    public $belongsTo = array('User');
    public $actsAs = array('containable');

    //get deposits. user only or all
    public function get($userId) {
        $options['conditions'] = array(
            'Withdraw.user_id' => $userId
        );
        $this->contain();
        $data = $this->find('all', $options);
        return $data;
    }
    
    public function getPendingUserWithdraw($userId) {
        $options['conditions'] = array(
            'Withdraw.user_id' => $userId,
            'Withdraw.status' => 'pending'
        );
        $this->contain();
        $data = $this->find('first', $options);
        return $data;
    }
    
    public function getWithdraw($id)
    {    	
    	$options['conditions'] = array('Withdraw.id' => $id);
    	$options['fields'] = array('Withdraw.id','Withdraw.amount','Withdraw.date');
    	$this->contain();
    	$data = $this->find('first', $options);
    	return $data;
    }
    
    function getAll() {
        $data = $this->find('all', $options);
        return $data;
    }
    
    
    
    function getActions() {
        $actions = array();
        $actions[] = array('name' => __('Complete', true), 'action' => 'complete', 'controller' => NULL);
        $actions[] = array('name' => __('Cancel', true), 'action' => 'cancel', 'controller' => NULL);
        return $actions;
    }
    
    function getTabs($params) {
        $tabs = array();
        $tabs[] = $this->__makeTab(__('Pending', true), 'index', 'withdraws', NULL, false);
        $tabs[] = $this->__makeTab(__('Completed', true), 'completed', 'withdraws', NULL, false);
        $tabs[] = $this->__makeTab(__('Canceled', true), 'canceled', 'withdraws', NULL, false);        
        if ($params['action'] == 'admin_completed') {
            $tabs[1]['active'] = true;
        } else if ($params['action'] == 'admin_canceled') {
            $tabs[2]['active'] = true;        
        } else {
            $tabs[0]['active'] = true;
        }
        return $tabs;
    }
    
    public function setStatus($id, $status) {
        $data = $this->getItem($id);
        $data['Withdraw']['status'] = $status;
        $this->save($data);
    }
    
    public function getPagination($options = 'pending') {
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'fields' => array(
                'Withdraw.id',
                'User.username',
                'Withdraw.amount',
                'Withdraw.date'
            ),
            'conditions' => array(
                'Withdraw.status' => $options
            )                
        );
        return $pagination;
    }
    
     //RISKS function
    function getBigWithdraws($bigWithdraw) {
        $options['conditions'] = array(
            'Withdraw.amount >=' => $bigWithdraw
        );
        $options['order'] = 'Withdraw.amount DESC';
        $this->contain('User');
        $data = $this->find('all', $options);        
        return $data;
    }
    
    function getReport($from, $to, $userId = null, $limit = NULL) {        
        $options['conditions'] = array(
            'Withdraw.date BETWEEN ? AND ?' => array($from, $to)            
        );
        if ($userId != NULL)
            $options['conditions']['Withdraw.user_id'] = $userId;
        if ($limit != NULL)
            $options['limit'] = $limit;
        $this->contain('User');
        $data = $this->find('all', $options);
        $data['header'] = array(
            'Withdraw ID',
            'User ID',
            'User name',
            'First last name',
            'Bank name',
            'Bank code',
            'Account no.',
            'Request time',
            'Withdraw type',
            'Amount'
        );        
        return $data;
    }
    
    public function getView($id) {
    	$options['fields'] = array();
    	$options['recursive'] = -1;
    	$options['conditions'] = array($this->name . '.id' => $id);
    
    	foreach ($this->_schema as $key => $value) {
    		if (($key != 'id') && ($key != 'order')) {
    			$options['fields'][] = $this->name . '.' . $key;
    			
    		}
    	}    	
    	$data = $this->find('first', $options);
    	return $data;
    }
}

?>