<?php

class DepositsController extends AppController {

    public $name = 'Deposits';
    public $helpers = array('Paginator');
    public $components = array('VTN', 'Eyowo', 'Paypal');

    function beforeFilter() {
        parent::beforeFilter();       
        $this->Auth->allow(array('vtnCallback'));
    }

    function index() {
    	
        $userId = $this->Auth->user('id');
        $this->paginate['conditions'] = array(
            'Deposit.user_id' => $userId
        );
        $this->paginate['limit'] = 20;
        $this->paginate['order'] = 'Deposit.date DESC';
        $data = $this->paginate('Deposit');
        $this->set('data', $data);
    }

    function __makeDeposit($userId) {
        $this->request->data['Deposit']['user_id'] = $userId;
        $this->request->data['Deposit']['type'] = 'Test deposit';
        $this->request->data['Deposit']['deposit_id'] = 'U' . $userId . 'D';
        $this->request->data['Deposit']['date'] = $this->__getSqlDate();
        $this->request->data['Deposit']['status'] = 'pending'; //TODO change to constant
        if ($this->Deposit->save($this->request->data)) {
            $this->request->data['Deposit']['id'] = $this->Deposit->id;
            $this->request->data['Deposit']['deposit_id'] = sprintf('U%1$05dD%2$05d', $userId, $this->Deposit->id);
            $this->Deposit->save($this->request->data);
            //don\'t add alance yet, require admin confirmation
            //$newBalance = $this->Session->read('Auth.User.balance') + $this->request->data['Deposit']['amount'];
            //$this->Deposit->User->addFunds($userId, $this->request->data['Deposit']['amount']);
            //$this->Session->write('Auth.User.balance', $newBalance);
            return true;
        }
        return false;
    }

    function preview() {
        $this->_checkAmount();        
        $this->set('type', 'Manual Deposit');
        $this->set('amount', $this->request->data['Deposit']['amount']);
    }

    private function _checkAmount() {
        if (!empty($this->request->data)) {
            if ($this->request->data['Deposit']['amount'] < Configure::read('Settings.minDeposit')) {
                $this->__setError(__('Min deposit is ') . Configure::read('Settings.minDeposit'));
                $this->redirect($this->referer());
            } else if ($this->request->data['Deposit']['amount'] > Configure::read('Settings.maxDeposit')) {
                $this->__setError(__('Max deposit is ') . Configure::read('Settings.maxDeposit'));
                $this->redirect($this->referer());
            }
        }
    }

    function result() {

        $this->_checkAmount();

        if (!empty($this->request->data)) {
            $userId = $this->Auth->user('id');
            if ($this->__makeDeposit($userId)) {
                $this->__setMessage('Success');
            } else {
                $this->__setMessage('Error');
            }
        }
    }

    function deposit() {
    	
    }

    function admin_user($userId = NULL) {
        $args = array();
        if (isset($userId)) {
            $args['conditions'] = array(
                'Deposit.user_id' => $userId
            );
        }
        $this->admin_index($args);
        $this->view = 'admin_index';
    }

    public function choose() {
        
    }

    public function umf() {
        
    }

    public function umfPreview() {
        $this->_checkAmount();
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $userId = $this->Auth->user('id');
            $depositId = uniqid($userId . '-');
            $this->set('itemDescription', 'Deposit for ' . $userId);
            $this->set('amount', $amount);
            $this->set('depositId', $depositId);
            $this->set('type', 'UMF');
        } else {
            $this->redirect($this->referer());
        }
    }

    public function umfCallback() {
        
    }

    /*
    public function eyowo() {
        
    }

    public function eyowoPreview() {
        $this->_checkAmount();
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $userId = $this->Auth->user('id');
            $depositId = uniqid($userId . '-');
            $type = 'Eyowo';
            $this->Deposit->saveDeposit($userId, $amount, $type, $depositId);
            $this->set('eyowoAmount', $amount * 100);
            $this->set('depositId', $depositId);
            $this->set('type', $type);
            $this->set('amount', $amount);
        } else {
            $this->redirect($this->referer());
        }
    }

    public function eyowoCallback() {
        
    }
*/
    public function bardo() {
        
    }

    public function bardoPreview() {
        $this->_checkAmount();
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $this->set('amount', $amount);
            $this->set('type', 'Bardo');
        }
    }

    public function bardoCallback() {
        // Merchant Transaction Number 
        $ShopNumber = $_REQUEST['SHOP_NUMBER'];
        // BARDO Transaction Number 
        $Bardonumber = $_REQUEST['BARDO_NUMBER'];
        // Status of the Transaction 
        $Status = $_REQUEST['TRANSAC_STATUS'];
        // Status detailled of the Transaction 
        $Status2 = $_REQUEST['STATUS_DETAILLED'];
        // 3DS mode 
        $T3ds = $_REQUEST['3DS'];

        $this->layout = 'ajax';
    }
	public function libertyr() {
		
	}
	
    public function libertyrPreview() 
    {
    	$this->_checkAmount();
    	if (isset($this->request->data['Deposit']['amount'])) {
    		$amount = $this->request->data['Deposit']['amount'];
    		$this->set('amount', $amount);
    		$this->set('type', 'Liberty Reserve');
    	}
    }
    
    public function libertyrCallback() {
    	$this->layout = 'ajax';
    }
    
    public function webmoney() {}
    
    public function webmoneyPreview()
    {
    	$this->_checkAmount();
    	if (isset($this->request->data['Deposit']['amount'])) {
    		$amount = $this->request->data['Deposit']['amount'];
    		$this->set('amount', $amount);
    		$this->set('type', 'Webmoney');
    	}
    }
    
    public function webmoneyCallback()
    {
    	$this->layout = 'ajax';
    }
    
    public function paypal() {}
    
    public function paypalPreview()
    {
    	$this->Paypal->sandboxMode = false;
    	$this->Paypal->config['password'] = Configure::read('Settings.paypal_password');
    	$this->Paypal->config['email'] = Configure::read('Settings.paypal_email');
    	$this->Paypal->config['signature'] = Configure::read('Settings.paypal_signature');
    	$percentage = Configure::read('Settings.paypal_deposit_funding_percentage');
    	if ($percentage != 0) {
    		$this->Paypal->amount = $this->request->data['Deposit']['amount'] * (1+$percentage/100);
    	}else {
    		$this->Paypal->amount = $this->request->data['Deposit']['amount'];
    	}
    	$this->Paypal->currencyCode = 'USD';
    	$this->Paypal->returnUrl = Router::url(array('action' => 'paypalCallback'), true);
    	$this->Paypal->cancelUrl = Router::url($this->here, true);
    	$this->Paypal->orderDesc = 'Deposit';
    	$this->Paypal->itemName = 'Money transfer';
    	$this->Paypal->quantity = 1;
    	$this->Paypal->expressCheckout();
    }
    
    public function paypalCallback() {
    	try {
	    		$this->Paypal->token = $this->request->query['token'];
	    		$this->Paypal->payerId = $this->request->query['PayerID'];
				$customer_details = $this->Paypal->getExpressCheckoutDetails();
	    		//debug($customer_details);
	    		$this->set('token', $_GET['token']);
	    		$this->set('payerId', $_GET['PayerID']);
	    		$this->set('customer_details', $customer_details);
	    		
    		} catch(Exception $e) {
    			echo ($e->getMessage());
			$this->Session->setFlash($e->getMessage());
		}
    }
    
    public function complete_express_checkout($token,$payerId,$amount) {
    	try{
    		$this->Paypal->amount = $amount;
    		$this->Paypal->currencyCode = 'USD';
    		$this->Paypal->token = $token;
    		$this->Paypal->payerId = $payerId;
    		$response = $this->Paypal->doExpressCheckoutPayment();
    		if ($response['PAYMENTSTATUS'] == "Completed")
    		{
    			$this->Deposit->create(
    					array(
    							'user_id' => $this->Auth->user('id'),
    							'payment_method' => 1,
    							'date' => date('Y-m-d H:i:s'), 
    							'deposit_id' => $payerId,
    							'type' => "paypal",   							
    							'amount' => $amount,
    							'status' => "completed"
    					)
    			);
    			$this->Deposit->save();
    
    		}
    		$this->set('message', 'Your deposit was succesful');
    	} catch(Exception $e) {
    		$this->set('message', $e->getMessage());
    		$this->Session->setFlash($e->getMessage());
    	}
    }
    
    /*
    public function vtn() {
        
    }

    public function vtnPreview() {
        $this->_checkAmount();
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $this->set('amount', $amount);
            $this->set('type', 'VTN');
        }
    }

    public function vtnCallback() {
        if (!empty($this->request->data['ipn_refno'])) {
            $data = $this->VTN->ProcessPage($this->request->data['ipn_refno']);
            if (!empty($data)) {
                $this->Deposit->makeDeposit($data);
            } else {
                
            }
        }
        $this->layout = 'ajax';
        $this->view = 'callback';
    }
*/
    
    public function success() {
        debug($this->request->data);
    }

    public function admin_index($status = 'pending') {
        $model = $this->__getModel();
        $this->paginate = $this->Deposit->getPagination($status);
        $data = $this->paginate();
        
        foreach ($data as &$row) {
            foreach ($row['User'] as $key => $value) {
                $row['Deposit'][$key] = $value;
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
        $this->Deposit->setStatus($id, 'completed');
        //give some money to user
        $deposit = $this->Deposit->getItem($id);
        $amount = $deposit['Deposit']['amount'];
        $userId = $deposit['Deposit']['user_id'];
        $this->Deposit->User->addFunds($userId, $amount);
        $this->__setMessage(__('Deposit request set as completed'));
        $this->redirect($this->referer());
    }

    public function admin_cancel($id) {
        $this->Deposit->setStatus($id, 'canceled');
        $this->__setMessage(__('Deposit request canceled'));
        $this->redirect($this->referer());
    }

}

?>