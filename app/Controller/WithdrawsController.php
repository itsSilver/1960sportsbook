<?php

class WithdrawsController extends AppController {

    public $name = 'Withdraws';
    public $actsAs = array('Containable');

    function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow(array('*'));
    }

    function index() {
        if (!Configure::read('Settings.withdraws')) {
            $this->redirect('/');
        }
        
        $userId = $this->Auth->user('id');
        if (!empty($this->request->data)) {
            if ($this->__makeWithdraw($userId)) {
                $this->__setMessage('Success');
            }
        }
        $data = $this->Withdraw->get($userId);
        $this->set('data', $data);
    }

    public function settings() {
        if (!empty($this->request->data)) {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
            unset($this->request->data['User']['username']);
            unset($this->request->data['User']['first_name']);
            unset($this->request->data['User']['last_name']);
            unset($this->request->data['User']['date_of_birth']);
            if ($this->Withdraw->User->save($this->request->data)) {
                $this->__setMessage(__('Bank information updated.', true));
            }
        }
        $options['fields'] = array(
            'User.bank_name',
            'User.account_number'
        );
        $user = $this->Withdraw->User->getItem($this->Auth->user('id'));
        $this->set('user', $user['User']);
    }
    
    function __makeWithdraw($userId) {
        if ($this->request->data['Withdraw']['amount'] < Configure::read('Settings.minWithdraw')) {
            $this->__setError(__('Min withdraw is ') . Configure::read('Settings.minWithdraw'));
            return false;
        } else if ($this->request->data['Withdraw']['amount'] > Configure::read('Settings.maxWithdraw')) {
            $this->__setError(__('Max withdraw is ') . Configure::read('Settings.maxWithdraw'));
            return false;
        } else if ($this->request->data['Withdraw']['amount'] > $this->Session->read('Auth.User.balance')) {
            $this->__setError(__('You don\'t have enough balance'));
            return false;
        }
        
        $pendingWithdraw = $this->Withdraw->getPendingUserWithdraw($userId);
        if (!empty($pendingWithdraw)) {
            $this->__setError(__('You have pending withdraw'));
            return false;
        }
        
        $this->request->data['Withdraw']['user_id'] = $userId;
        $this->request->data['Withdraw']['type'] = 'request';
        $this->request->data['Withdraw']['status'] = 'pending';
        $this->request->data['Withdraw']['date'] = $this->__getSqlDate();
        if ($this->Withdraw->save($this->request->data)) {
            $newBalance = $this->Session->read('Auth.User.balance') - $this->request->data['Withdraw']['amount'];
            $this->Withdraw->User->addFunds($userId, -($this->request->data['Withdraw']['amount']));
            $this->Session->write('Auth.User.balance', $newBalance);
            return true;
        }
        return false;
    }

    public function admin_index($status = 'pending') {
        $model = $this->__getModel();        
        $this->paginate = $this->Withdraw->getPagination($status);
        $data = $this->paginate();        
        foreach ($data as &$row) {        	
            foreach ($row['User'] as $key => $value) {
                $row['Withdraw'][$key] = $value;
            }
        }
        $this->set('data', $data);
        $this->set('model', $model);

        $this->set('actions', $this->{$model}->getActions());
        return $data;
    }
    
    public function admin_completed() {
        $this->admin_index('completed');
        $this->view = 'admin_index';
        $this->set('actions', array());
    }
    public function admin_canceled() {
        $this->admin_index('canceled');
        $this->view = 'admin_index';
        $this->set('actions', array());
    }
    
    public function admin_complete($id) {
        $this->Withdraw->setStatus($id, 'completed');
        $this->__setMessage(__('Withdraw request set as completed'));
        $this->redirect($this->referer());
    }
    
    public function admin_cancel($id) {
        $this->Withdraw->setStatus($id, 'canceled');
        $withdraw = $this->Withdraw->getItem($id);
        $amount = $withdraw['Withdraw']['amount'];
        $userId = $withdraw['Withdraw']['user_id'];
        $this->Withdraw->User->addFunds($userId, $amount);
        $this->__setMessage(__('Withdraw request canceled'));
        $this->redirect($this->referer());
    }
    
    function admin_user($userId = NULL) {
        $args = array();
        if (isset($userId)) {
            $args['conditions'] = array(
                'Withdraw.user_id' => $userId
            );
        }
        $this->admin_index($args);
        $this->view = 'admin_index';
    }
    
    function admin_view($id = -1) {
    	$model = $this->__getModel();
    	$this->{$model}->locale = Configure::read('Admin.defaultLanguage');
    	$data = $this->{$model}->getView($id);
    	$aData =$data;
    	if (!empty($data)) {
    		$data = $this->{$model}->getIdNames($data);
    		$this->set('fields', $data);
    	} else {
    		$this->__setError(__('can\'t find', true));
    	}
    	
    	$user = $this->Withdraw->User->getItem($aData['Withdraw']['user_id']);
        $this->set('user', $user['User']);
        $this->render('/Withdraws/admin_view');
    }
    

}

?>