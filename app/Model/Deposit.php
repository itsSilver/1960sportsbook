<?php

class Deposit extends AppModel {

    public $name = 'Deposit';
    public $belongsTo = array('User');
    public $actsAs = array('Containable');
	public $hasMany = array('Referral');
	
    public function saveDeposit($userId, $amount, $type, $depositId, $details = '', $status = 'Pending') {        
        $data['Deposit'] = array(
            'user_id' => $userId,
            'amount' => $amount,
            'type' => $type,
            'deposit_id' => $depositId,
            'status' => $status,
            'details' => $details,
            'date' => $this->getSqlDate()
        );
        return $this->save($data);
    }
    
    public function updateDeposit($id, $amount = null, $type = null, $details = null) {
        $deposit = $this->getItem($id);
        if ($amount !== null) {
            $deposit['Deposit']['amount'] = $amount;
        }
        if ($type !== null) {
            $deposit['Deposit']['type'] = $type;
        }
        if ($details !== null) {
            $deposit['Deposit']['details'] = $details;
        }
        return $this->save($deposit);
    }
    
    function makeDeposit($data) {
        $deposit = $this->getDepositBuDepositId($data['Deposit']['deposit_id']);
        if (empty($deposit)) {
            return $this->save($data);
        }
        return false;
    }

    public function getDepositBuDepositId($depositId) {
        $options['conditions'] = array(
            'Deposit.deposit_id' => $depositId
        );
        $this->contain();
        $data = $this->find('first', $options);
        return $data;
    }

    public function getDepositsByType($type, $status = 'pending') {
        $options['conditions'] = array(
            'Deposit.type' => $type,
            'Deposit.status' => $status
        );
        $options['recursive'] = -1;        
        $data = $this->find('all', $options);
        return $data;
    }

    //get deposits. user only or all
    function get($id) {
        $options['conditions'] = array(
            'Deposit.user_id' => $id
        );
        $this->contain();
        $data = $this->find('all', $options);
        return $data;
    }

    function getAll() {
        $data = $this->find('all', $options);
        return $data;
    }

    //RISKSv function    
    function getBigDeposits($bigDeposit) {
        $options['conditions'] = array(
            'Deposit.amount >=' => $bigDeposit
        );
        //$options['order'] = 'Deposit.amount DESC';
        $this->contain('User');
        $data = $this->find('all', $options);
        return $data;
    }

    function getReport($from, $to, $userId = null, $limit = NULL) {
        $options['conditions'] = array(
            'Deposit.date BETWEEN ? AND ?' => array($from, $to)
        );
        if ($userId != NULL)
            $options['conditions']['Deposit.user_id'] = $userId;
        if ($limit != NULL)
            $options['limit'] = $limit;
        $this->contain('User');
        $data = $this->find('all', $options);
        $data['header'] = array(
            'Deposit ID',
            'User ID',
            'User name',
            'Deposit time',
            'Deposit type',
            'Amount'
        );
        return $data;
    }

    public function getPagination($options = 'pending') {
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'fields' => array(
                'Deposit.id',
                'User.username',
			    'User.group_id',
                'Deposit.amount',
                'Deposit.date',
                'Deposit.details'
            ),
            'conditions' => array(
                'Deposit.status' => $options
            )
        );
        return $pagination;
    }

    public function setStatus($id, $status) {
        $data = $this->getItem($id);
        $data['Deposit']['status'] = $status;
        $this->save($data);
    }

    function getTabs($params) {
        $tabs = array();
        $tabs[] = $this->__makeTab(__('Pending', true), 'index', 'deposits', NULL, false);
        $tabs[] = $this->__makeTab(__('Completed', true), 'completed', 'deposits', NULL, false);
        $tabs[] = $this->__makeTab(__('Canceled', true), 'canceled', 'deposits', NULL, false);
        if ($params['action'] == 'admin_completed') {
            $tabs[1]['active'] = true;
        } else if ($params['action'] == 'admin_canceled') {
            $tabs[2]['active'] = true;
        } else {
            $tabs[0]['active'] = true;
        }
        return $tabs;
    }

    function getActions() {
        $actions = array();
        $actions[] = array('name' => __('Complete', true), 'action' => 'complete', 'controller' => NULL);
        $actions[] = array('name' => __('Cancel', true), 'action' => 'cancel', 'controller' => NULL);
        return $actions;
    }

}

?>