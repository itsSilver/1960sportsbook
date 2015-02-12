<?php

class DepositsController extends AppController {

    public $name = 'Deposits';
    public $helpers = array('Paginator');
    public $components = array('VTN', 'Eyowo');
    public $uses = array('Deposit', 'PaymentBonus', 'PaymentBonusGroup', 'PaymentBonusUsage', 'DepositMeta', 'User');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('voguepayCallback', 'vtnCallback', 'bardoCallback', 'umfCallback', 'bardoDisplay', 'umfCallback'));
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
        $this->request->data['Deposit']['type'] = 'Manual deposit';
        $this->request->data['Deposit']['deposit_id'] = uniqid($userId . '-');
        $this->request->data['Deposit']['date'] = $this->__getSqlDate();
        $this->request->data['Deposit']['status'] = 'pending'; //TODO change to constant

        $amount = $this->request->data['Deposit']['amount'];
        $depositId = $this->request->data['Deposit']['deposit_id'];

        $this->request->data['bonus_code'] = $this->request->data['Deposit']['bonus_code'];

        $ret = $this->__check_bonus($amount);


        $calc = $this->PaymentBonus->calculateBonus($ret, $amount);
        if ($ret != null and isset($ret['PaymentBonus'])) {
            $details = 'Used bonus code ' . $ret['PaymentBonusGroup']['name'] . '>' . $ret['PaymentBonus']['bonus_code'] . ' added amount ' . $calc['bonusAmount'];
        } else {
            $details = "";
        }
        $result = $this->Deposit->saveDeposit($userId, $calc['totalAmount'], 'Manual deposit', $depositId, $details);

        $this->PaymentBonusUsage->commitBonus($ret, $calc, $userId);

        if ($result) {
            return true;
        }
        return false;
    }

    function preview() {

        $this->_checkAmount();
        $this->__check_bonus($this->request->data['Deposit']['amount']);
        $this->set('bonusCode', $this->request->data['bonus_code']);
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
        $this->__bonus_list();
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

    /**
     * Generate bonus list
     */
    protected function __bonus_list() {
        $l = $this->PaymentBonusGroup->find('list');
        array_unshift($l, array('-1' => __('None')));
        $this->set('bonuses', $l);
    }

    /**
     * Check bonus code and expirity
     * Set view vairables
     */
    protected function __check_bonus($amount) {
        /*
          if ($this->data['bonus'] < 1){
          return true; // In case, when none selected
          }
         */
        $code = trim($this->request->data['bonus_code']);
        if (strlen($code) < 1) {
            return true;
        }

        $ret = $this->PaymentBonus->getBonus(NULL, $this->data['bonus_code']);

        if ($ret == null) {
            $this->__setError(__('Bonus code expired or wrong'));
            $this->redirect($this->referer());
            return false;
        }

        $userId = $this->Auth->user('id');

        $used = $this->PaymentBonusUsage->getUsedCount($ret['PaymentBonus']['id'], $userId);

        if ($used >= $ret['PaymentBonus']['max_used_count']) {
            $this->__setError(__('Bonus code expired'));
            $this->redirect($this->referer());
            return false;
        }

        if (($ret['PaymentBonus']['min_amount'] > 0) && ($ret['PaymentBonus']['min_amount'] > $amount)) {
            $this->__setError(__('Your amount is too low for bonus'));
            $this->redirect($this->referer());
            return false;
        }

        if (($ret['PaymentBonus']['max_amount'] > 0) && ($ret['PaymentBonus']['max_amount'] < $amount)) {
            $this->__setError(__('Your amount is too high for bonus'));
            $this->redirect($this->referer());
            return false;
        }

        $this->set('DepositBonusUsed', $used);
        $this->set('DepositBonusMax', $ret['PaymentBonus']['max_used_count']);
        $this->set('DepositBonuxValitUntil', $ret['PaymentBonus']['valid_before']);

        $this->set('DepositBonusName', $ret['PaymentBonusGroup']['name']);

        $calc = $this->PaymentBonus->calculateBonus($ret, $amount);

        $this->set('DepositBonusPriemium', $calc['bonusAmount']);

        $this->Session->write('PaymentBonus', $ret);
        return $ret;
    }

    public function choose() {
        
    }

    public function umf() {
        $this->__bonus_list();
    }

    public function umfPreview() {
        $this->_checkAmount();
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $bonusas = $this->__check_bonus($amount);
            $userId = $this->Auth->user('id');
            $depositId = uniqid($userId . '-');
            $this->set('itemDescription', 'Deposit for ' . $userId);
            $this->set('amount', $amount);
            $this->set('depositId', $depositId);
            $this->set('type', 'UMF');
            $this->DepositMeta->saveDeposit($userId, $amount, $depositId, serialize($bonusas));
        } else {
            $this->redirect($this->referer());
        }
    }

    protected function __makeBonus() {
        
    }

    public function umfCallback() {
        if (isset($_REQUEST['ItemID'])) {
            $depositId = $_REQUEST['ItemID'];
            $amount = $_REQUEST['ItemPrice'];
            $userId = $_REQUEST['inv_customer_id'];
            CakeLog::write('debug', 'umf precheck: ' . $userId . ' | ' . $amount . ' | ' . $depositId);
            if (isset($userId)) {


                $ret = $this->DepositMeta->getDeposit($userId, $amount, $depositId);
                $ret = unserialize($ret);

                $calc = $this->PaymentBonus->calculateBonus($ret, $amount);
                if ($ret != null and isset($ret['PaymentBonus'])) {
                    $details = $ret['PaymentBonusGroup']['name'] . '>' . $ret['PaymentBonus']['bonus_code'] . ' added amount ' . $calc['bonusAmount'];
                } else {
                    $details = "";
                }
                $this->Deposit->saveDeposit($userId, $calc['totalAmount'], 'UMF', $depositId, 'Used bonus code ' . $details);
                $this->PaymentBonusUsage->commitBonus($ret, $calc, $userId);

                $this->Deposit->setStatus($this->Deposit->id, 'completed');
                $d = $this->Deposit->read();
                $deposited = $d['Deposit']['amount'];
                $this->Deposit->User->addFunds($userId, $deposited);
                CakeLog::write('debug', 'umf callback: ' . $userId . ' | ' . $calc['totalAmount'] . ' (with bonus) | ' . $depositId);
            }
        }
        die;
    }

    public function eyowo() {
        /**
         * @warning: there are no bonus counting
         */
    }

    public function eyowoPreview() {
        $this->_checkAmount();

        $this->__check_bonus($this->request->data['Deposit']['amount']);
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $userId = $this->Auth->user('id');
            //get bonus
            $bonusas = $this->__check_bonus($amount);
            $depositId = uniqid($userId . '-');
            $type = 'Eyowo';
            $this->Deposit->saveDeposit($userId, $amount, $type, $depositId);
            $this->set('eyowoAmount', $amount * 100);
            $this->set('depositId', $depositId);
            $this->set('type', $type);
            $this->set('amount', $amount);
            //save bonus
            $this->DepositMeta->saveDeposit($userId, $amount, $depositId, serialize($bonusas));
        } else {
            $this->redirect($this->referer());
        }
    }

    public function eyowoCallback() {
        
    }

    public function bardo() {
        $this->__bonus_list();
    }

    public function bardoPreview() {
        $this->_checkAmount();
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $bonusas = $this->__check_bonus($amount);
            $userId = $this->Auth->user('id');
            $depositId = uniqid($userId . '-');
            $this->set('depositId', $depositId);
            $this->set('amount', $amount);
            $this->set('type', 'Bardo');
            $this->DepositMeta->saveDeposit($userId, $amount, $depositId, serialize($bonusas));
        } else {
            $this->redirect($this->referer());
        }
    }

    public function bardoDisplay() {
        $this->redirect(array('controller' => 'deposits', 'action' => 'index'));
    }

    public function bardoSubmit() {
        if (isset($_POST["SHOP_ID"])) {

            $params = array(
                'SHOP_ID' => $_POST["SHOP_ID"],
                'SHOP_NUMBER' => $_POST["SHOP_NUMBER"],
                'LANGUAGE_CODE' => $_POST["LANGUAGE_CODE"],
                'TRANSAC_AMOUNT' => $_POST["TRANSAC_AMOUNT"],
                'CURRENCY_CODE' => $_POST["CURRENCY_CODE"],
                'CUSTOMER_EMAIL' => $_POST["TRANSAC_AMOUNT"],
                'CURRENCY_CODE' => $_POST["CURRENCY_CODE"],
                'CUSTOMER_EMAIL' => $_POST["CUSTOMER_EMAIL"],
                'CUSTOMER_LAST_NAME' => $_POST["CUSTOMER_LAST_NAME"],
                'CUSTOMER_FIRST_NAME' => $_POST["CUSTOMER_FIRST_NAME"],
                'CUSTOMER_ADDRESS' => $_POST["CUSTOMER_ADDRESS"],
                'CUSTOMER_ZIP_CODE' => $_POST["CUSTOMER_ZIP_CODE"],
                'CUSTOMER_STATE' => ($this->request->data['CUSTOMER_STATE'] ? $this->request->data['CUSTOMER_STATE'] : ' '),
                'CUSTOMER_COUNTRY' => $this->request->data['CUSTOMER_COUNTRY'],
                'CUSTOMER_CITY' => $_POST["CUSTOMER_CITY"],
                'CUSTOMER_PHONE' => $_POST["CUSTOMER_PHONE"],
                'CB_TYPE' => $_POST["CB_TYPE"],
                'PRODUCT_NAME' => $_POST["PRODUCT_NAME"],
                'CUSTOMER_IP' => $_POST["CUSTOMER_IP"],
                'CB_NUMBER' => $_POST["CB_NUMBER"],
                'CB_MONTH' => $_POST["CB_MONTH"],
                'CB_YEAR' => $_POST["CB_YEAR"],
                'CB_CVC' => $_POST["CB_CVC"]
            );


            $url = "https://payment.bardo-secured.com/bardo/process.aspx";


            $amount = $_POST["TRANSAC_AMOUNT"] / 100;
            $userId = $this->Auth->user('id');
            $depositId = $_POST["SHOP_NUMBER"];
            $type = 'Bardo';

            $ret = $this->DepositMeta->getDeposit($userId, $amount, $depositId);
            $ret = unserialize($ret);

            //var_dump( $ret ); 


            $calc = $this->PaymentBonus->calculateBonus($ret, $amount);

            if ($ret != null && isset($ret['PaymentBonus'])) {
                $details = $ret['PaymentBonusGroup']['name'] . '>' . $ret['PaymentBonus']['bonus_code'] . ' added amount ' . $calc['bonusAmount'];
            } else {
                $details = "";
            }


            //var_dump($calc);
            //var_dump( $calc['totalAmount'] );

            $this->Deposit->saveDeposit($userId, $calc['totalAmount'], $type, $depositId);

            if ($ret != null && isset($ret['PaymentBonus']))
                $this->PaymentBonusUsage->commitBonus($ret, $calc, $userId);



//$url is the URL where the data are posted
            $url = "https://payment.bardo-secured.com/bardo/process.aspx";
            $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";

// You can use Curl method or Redirection method
// CURL Method
//For example CURL can be used to send
            /*
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_POST,1);
              curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
              curl_setopt($ch, CURLOPT_URL,$url);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
              curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // this line makes it work under https

              $result=curl_exec ($ch);
              curl_close ($ch);
              //The result is displayed to the user.
              echo(htmlspecialchars( $result ));
              die();
             * 
             */

            $url = "https://payment.bardo-secured.com/bardo/process.aspx?";

            foreach ($params AS $key => $value) {
                $url .= $key . '=' . urlencode($value) . '&';
            }

            $url = str_replace("+", "%20", $url); //WHy you don\'t like + ??? 
            //$url = substr($url, -5);
            //print_r( $params );
            //die($url);
            //CakeLog::write('debug', 'bardo request: ' . $url);


            header("location:" . $url);
            die;
        } else {
            $this->redirect($this->referer());
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

        CakeLog::write('debug', 'bardo callback: ' . $ShopNumber . ' | ' . $Bardonumber . ' | ' . $Status . ' | ' . $Status2 . ' | ' . $T3ds);

        $deposit = $this->Deposit->getDepositBuDepositId($ShopNumber);
        if ($Status == '00') {
            $this->Deposit->setStatus($deposit['Deposit']['id'], 'completed');
            $amount = $deposit['Deposit']['amount'];
            $userId = $deposit['Deposit']['user_id'];
            $this->User->addFunds($userId, $amount);
        } else {
            $this->Deposit->setStatus($deposit['Deposit']['id'], 'canceled');
        }
        $this->layout = 'ajax';
    }

    public function vtn() {
        
    }

    public function vtnPreview() {
        $this->_checkAmount();
        $this->__check_bonus($this->request->data['Deposit']['amount']);
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $bonusCode = trim($this->data['bonus_code']);

            $this->set('bonusCode', $bonusCode);
            $this->set('amount', $amount);
            $this->set('type', 'VTN');
        }
    }

    public function vtnCallback() {
        CakeLog::write('vtn', serialize($this->request->data));

        if (!empty($this->request->data['ipn_refno'])) {
            CakeLog::write('vtn', 'lala');
            $data = $this->VTN->ProcessPage($this->request->data['ipn_refno']);
            if (!empty($data)) {
                CakeLog::write('vtn', 'not callback');
                $amount = $data['Deposit']['amount'];
                $userId = $data['Deposit']['user_id'];
                $depositId = $data['Deposit']['deposit_id'];
                $deposit = $this->Deposit->getDepositBuDepositId($depositId);
                if (empty($deposit)) {
                    CakeLog::write('vtn', 'new deposit');
//                    $this->request->data['bonus_code'] = $data['Deposit']['bonus_code'];
//                    $bonusas = $this->__check_bonus($amount);
//                    $ret = $bonusas;
//
//                    $calc = $this->PaymentBonus->calculateBonus($ret, $amount);
//                    if ($ret != null and isset($ret['PaymentBonus'])) {
//                        $details = $ret['PaymentBonusGroup']['name'] . '>' . $ret['PaymentBonus']['bonus_code'] . ' added amount ' . $calc['bonusAmount'];
//                    } else {
//                        $details = "";
//                    }
//                    $this->Deposit->saveDeposit($userId, $calc['totalAmount'], 'UMF', $depositId, 'Used bonus code ' . $details);
//                    $this->PaymentBonusUsage->commitBonus($ret, $calc, $userId);
                    $this->request->data['bonus_code'] = $data['Deposit']['bonus_code'];
                    $ret = $this->__check_bonus($amount);

                    $calc = $this->PaymentBonus->calculateBonus($ret, $amount);
                    if ($ret != null and isset($ret['PaymentBonus'])) {
                        $details = $ret['PaymentBonusGroup']['name'] . '>' . $ret['PaymentBonus']['bonus_code'] . ' added amount ' . $calc['bonusAmount'];
                    } else {
                        $details = "";
                    }
                    $this->Deposit->saveDeposit($userId, $calc['totalAmount'], 'VTN', $depositId, 'Used bonus code ' . $details);
                    $this->PaymentBonusUsage->commitBonus($ret, $calc, $userId);

                    $this->Deposit->setStatus($this->Deposit->id, 'completed');

                    $this->Deposit->User->addFunds($userId, $calc['totalAmount']);
                }
            } else {
                
            }
        }
        $this->layout = 'ajax';
        $this->view = 'callback';
    }

    public function success() {
        $this->__setMessage(__('Money transaction completed'));
    }

    public function admin_index($status = 'pending') {

		$data = array();

        $model = $this->__getModel();
        $this->paginate = $this->Deposit->getPagination($status);
        $dataArray = $this->paginate();

		foreach($dataArray as $values){
			if($values['User']['group_id']!='8'){
				$data[] = $values;
			}
		}

        foreach ($dataArray as &$row) {
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

    public function voguepay() {
        
    }

    public function voguepayPreview() {
        $this->_checkAmount();
        $this->__check_bonus($this->request->data['Deposit']['amount']);
        if (isset($this->request->data['Deposit']['amount'])) {
            $amount = $this->request->data['Deposit']['amount'];
            $bonusCode = trim($this->data['bonus_code']);

            $this->set('bonusCode', $bonusCode);
            $this->set('amount', $amount);
            $this->set('type', 'VoguePay');
        }
    }

    public function voguepayCallback() {
        if (!empty($this->request->data['transaction_id'])) {
            $transaction_id = $this->request->data['transaction_id'];
            //get the full transaction details as an json from voguepay
            $json = file_get_contents('https://voguepay.com/?v_transaction_id=' . $transaction_id . '&type=json');
            //create new array to store our transaction detail
            
            $transaction = json_decode($json, true);

            /*
              Now we have the following keys in our $transaction array
              $transaction['merchant_id'],
              $transaction['transaction_id'],
              $transaction['email'],
              $transaction['total'],
              $transaction['merchant_ref'],
              $transaction['memo'],
              $transaction['status'],
              $transaction['date'],
              $transaction['referrer'],
              $transaction['method']
             */
            
            if ($transaction['status'] != 'Approved') {
                $a = explode('|||', $transaction['merchant_ref']);
                $userId = $a[0];
                $bonus = $a[1];
                $amount = $transaction['total'];
                $depositId = $transaction_id;
                $deposit = $this->Deposit->getDepositBuDepositId($depositId);
                if (empty($deposit)) {
                    $this->request->data['bonus_code'] = $bonus;
                    $ret = $this->__check_bonus($amount);

                    $calc = $this->PaymentBonus->calculateBonus($ret, $amount);
                    if ($ret != null and isset($ret['PaymentBonus'])) {
                        $details = $ret['PaymentBonusGroup']['name'] . '>' . $ret['PaymentBonus']['bonus_code'] . ' added amount ' . $calc['bonusAmount'];
                    } else {
                        $details = "";
                    }
                    $this->Deposit->saveDeposit($userId, $calc['totalAmount'], 'VoguePay', $depositId, '');
                    $this->Deposit->setStatus($this->Deposit->id, 'canceled');
                    die('Failed transaction');
                }
            }

            $a = explode('|||', $transaction['merchant_ref']);
            $userId = $a[0];
            $bonus = $a[1];
            $amount = $transaction['total'];
            $depositId = $transaction_id;
            $deposit = $this->Deposit->getDepositBuDepositId($depositId);
            if (empty($deposit)) {
                $this->request->data['bonus_code'] = $bonus;
                $ret = $this->__check_bonus($amount);

                $calc = $this->PaymentBonus->calculateBonus($ret, $amount);
                if ($ret != null and isset($ret['PaymentBonus'])) {
                    $details =  'Used bonus code ' . $ret['PaymentBonusGroup']['name'] . '>' . $ret['PaymentBonus']['bonus_code'] . ' added amount ' . $calc['bonusAmount'];
                } else {
                    $details = "";
                }
                $this->Deposit->saveDeposit($userId, $calc['totalAmount'], 'VoguePay', $depositId, $details);
                $this->PaymentBonusUsage->commitBonus($ret, $calc, $userId);

                $this->Deposit->setStatus($this->Deposit->id, 'completed');

                $this->Deposit->User->addFunds($userId, $calc['totalAmount']);
            }
            die;
        }
    }

    public function voguepayFailed() {
        $this->__setError(__('Transaction failed'));
        $this->view = 'success';
    }



}

?>
