<?php

class UsersController extends AppController {

    public $name = 'Users';
    public $components = array('RequestHandler', 'Email', 'Recaptcha.Recaptcha');
    public $uses = array('User', 'BonusCode', 'BonusCodesUser', 'PaymentBonusUsage');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('confirm', 'register', 'reset', 'admin_login', 'admin_logout', 'login', 'logout', 'setTheme','account','admin_agentset','admin_agent_list','admin_select_group'));
    }

    function index() {        
    }

	function admin_select_group(){
		$username = $this->request->data['username'];
		$data	  = $this->User->userGroup($username);
		if(!empty($data)){
			echo $data['User']['group_id'];exit;
		} else {
			echo '0';exit;
		}
	}

	function admin_agent_list($id=NULL, $action=NULL) {

		//checking session for publick method
		 parent::checkSession();

		$agentsData = array();
		$allAgents = $this->User->allAgent(8);		
		if(!empty($allAgents)) {
			foreach($allAgents as $key => $agents){
				$agentsData[$agents['User']['id']]['id']          = $agents['User']['id'];
				$agentsData[$agents['User']['id']]['username']    = $agents['User']['username'];
				$agentsData[$agents['User']['id']]['agent_perct'] = $agents['User']['agent_perct'];
			}
			$this->set('agentsData', $agentsData);
		}
	}

	function admin_agentset($agent_id=NULL, $action=NULL) {

		//checking session for publick method
		 parent::checkSession();

		if (!empty($this->request->data)) {
			$agent_perct = $this->request->data['User']['agent_perct'];
			$agent_id    = $this->request->data['User']['agent_id'];
			if($agent_perct=='' || $agent_id =='' || $agent_id =='0') {
				$this->__setError(__('Please select all the fields.', true));
			    $this->redirect(array('action' => 'admin_agentset',''.$agent_id.'','add'));
				exit;
			}
			$update_agent_perct = $this->User->saveGlobalDataUser($table_name='users',$coloum_field='agent_perct', $coloum_value=$agent_perct,$updated_on_field='id',$updated_on_value=$agent_id,$otherfields=null);
			if($update_agent_perct){				
			    $this->__setMessage(__('Entry has been saved.', true));
			    $this->redirect(array('action' => 'admin_agentset',''.$agent_id.'','edit'));
			    exit;
			} else {
				$this->__setError(__('Internal error occur.Try again.', true));
				$this->redirect(array('action' => 'admin_agentset'));
				exit;
			}
		}
		//Agent account
		$agentData = $this->User->getUser($tableName='users',$fieldId='id',$fieldValue=$agent_id);	
		if(isset($agentData[0]['users'])){
			$this->set('agent_id', $agentData[0]['users']['id']);
			$this->set('username', $agentData[0]['users']['username']);
			$this->set('agent_perct', $agentData[0]['users']['agent_perct']);
		}
	}

    function register() {
        if (Configure::read('Settings.registration') != 1) {
            $this->redirect('/');
        }

        $this->layout = 'register';
        $this->layoutPath = 'Users';

        $this->set('countries', $this->User->getCountriesList());
        $this->set('questions', $this->User->getQuestions());
        if (!empty($this->request->data)) {

            if ($this->request->data['User']['password'] != $this->request->data['User']['password_confirm']) {
                $this->User->invalidate('password_confirm');
            }
            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
            $this->request->data['User']['balance'] = 0; //TODO set initial balance from config

            $this->request->data['User']['registration_date'] = $this->__getSqlDate();

            $this->request->data['User']['ip'] = $this->RequestHandler->getClientIP();

            $this->request->data['User']['group'] = 1; //TODO user group = 1?

            $this->request->data['User']['confirmation_code'] = $this->__generateCode();   
//            if ($this->Recaptcha->verify()) {    //commented on 8/30/2012
                if ($this->User->save($this->request->data)) {
                    //$this->__setMessage(__('Registration successful. Check your email.', true));
                    // send welcome email?
                    $url = Router::url(array('controller' => 'users', 'action' => 'confirm', 'code' => $this->request->data['User']['confirmation_code']), true);
                    $link = '<a href="' . $url . '">' . $url . '</a>';
                    $vars = array('link' => $link, 'first_name' => $this->request->data['User']['first_name'], 'last_name' => $this->request->data['User']['last_name']);
                    $this->__sendMail('confirmation', $this->request->data['User']['email'], $vars);
                    // Success! Redirect to a thanks page.
                    //$this->redirect('/users/login');
                    $this->layout = 'ok';
                }
			/*   Commented on 8/30/2012       
			} else {
			$this->set('captcha_error', $this->Recaptcha->error);
			//$this->__setError(__($this->Recaptcha->error));
			//$this->redirect('/users/register');
			return;
			}
			*/
			//$this->redirect(array('action' => 'index'));
			$this->request->data['User']['password'] = NULL;
            $this->request->data['User']['password_confirm'] = NULL;
        }
    }

    function password() {
        if (!empty($this->request->data)) {
            if ($this->request->data['User']['new_password'] == $this->request->data['User']['new_password_confirm']) {
                $oldPassword = $this->Auth->password($this->request->data['User']['password']);
                $user = $this->User->getItem($this->Session->read('Auth.User.id'));
                if ($oldPassword == $user['User']['password']) {
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['new_password']);
                    $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
                    if ($this->User->save($this->request->data, false)) {
                        $this->set('success', 1);
                        $this->__setMessage(__('Password changed'));
                    }
                } else {
                    $this->__setError(__('Wrong old password'));
                }
            } else {
                $this->__setError(__('Passwords do not match'));
            }
        }
        unset($this->request->data['User']['password']);
    }

    function confirm() {
        if (isset($this->params['named']['code'])) {
            $code = $this->params['named']['code'];
            $options['conditions'] = array(
                'User.confirmation_code' => $code
            );
            $this->User->contain();
            $user = $this->User->find('first', $options);

            if (isset($user) && $user['User']['confirmation_code'] != '') {
                $user['User']['confirmation_code'] = '';
                $user['User']['status'] = '1';

                $this->User->save($user, false);

                //send mail            
                $url = Router::url(array('controller' => 'users', 'action' => 'login'), true);
                $link = '<a href="' . $url . '">' . $url . '</a>';
                $user['User']['link'] = $link;
                $this->__sendMail('welcome', $user['User']['email'], $user['User']);
                $this->set('success', 1);
            }
        }
    }

    function __generateCode() {
        $code = '';
        $alphabet = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
        $max = strlen($alphabet) - 1;
        for ($i = 0; $i < 10; $i++) {
            $r = rand(0, $max);
            $code .= $alphabet[$r];
        }
        return $code;
    }

    function account() {
        if (!empty($this->request->data)) {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
            unset($this->request->data['User']['username']);
            unset($this->request->data['User']['first_name']);
            unset($this->request->data['User']['last_name']);
            unset($this->request->data['User']['date_of_birth']);
            if ($this->User->save($this->request->data)) {
                $this->__setMessage(__('Account information updated.', true));
            }
        }
        $options['fields'] = array(
            'User.id',
            'User.username',
            'User.first_name',
            'User.last_name',
            'User.date_of_birth',
            'User.address1',
            'User.address2',
            'User.zip_code',
            'User.city',
            'User.country',
            'User.mobile_number',
            'User.bank_name',
            'User.account_number'
        );

        $user = $this->User->getItem($this->Auth->user('id'));
        $this->set('user', $user['User']);
    }

    function settings() {
        if (!empty($this->request->data)) {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
            if ($this->User->save($this->request->data)) {
                if (!empty($this->request->data['User']['odds_type']))
                    $this->Session->write('Auth.User.odds_type', $this->request->data['User']['odds_type']);
                if (!empty($this->request->data['User']['time_zone']))
                    $this->Session->write('Auth.User.time_zone', $this->request->data['User']['time_zone']);
                if (!empty($this->request->data['User']['language_id']))
                    $this->Session->write('Auth.User.language_id', $this->request->data['User']['language_id']);
                $locale = $this->User->Language->findById($this->request->data['User']['language_id']);
                if (isset($locale)) {
                    $language = $locale['Language']['language'];
                    Configure::write('Config.language', $language);
                }
                $this->__setMessage(__('Account settings updated.', true));
                $this->redirect(array('action' => 'settings'));
            }
        }

        $options['fields'] = array(
            'User.time_zone',
            'User.language_id',
            'User.odds_type'
        );
        $options['conditions'] = array(
            'User.id' => $this->Session->read('Auth.User.id')
        );
        $user = $this->User->find('first', $options);

        $locales = $this->User->Language->getIdLangueageList();
        $this->set('locales', $locales);

        $this->request->data['User'] = $user['User'];
        $this->set('user', $user['User']);
    }

    function admin_settings() {
        $this->settings();
        $this->set('tabs', array());
    }

    function reset() {
        if (!empty($this->request->data['User']['email'])) {
            $this->User->contain();
            $user = $this->User->findByEmail($this->request->data['User']['email']);
            if ($user != NULL) {
                $user['User']['confirmation_code'] = $this->__generateCode();
                $this->User->save($user, false);
                //get url
                $url = Router::url(array('controller' => 'users', 'action' => 'reset', 'code' => $user['User']['confirmation_code']), true);
                $link = '<a href="' . $url . '">' . $url . '</a>';
                $user['User']['link'] = $link;
                $this->__sendMail('passwordReset', $user['User']['email'], $user['User']);
                $this->__setMessage(__('Password reset link sent to your email', true));
                $this->set('success', 1);
            } else {
                $this->__setError(__('E-mail not valid', true));
            }
        } else if (!empty($this->params['named']['code'])) {
            $code = $this->params['named']['code'];
            $options['conditions'] = array(
                'User.confirmation_code' => $code
            );
            $this->User->contain();
            $user = $this->User->find('first', $options);
            if (isset($user['User'])) {
                $this->set('code', $code);
                $this->render('reset1');
            }
        } else if (!empty($this->request->data)) {
            $this->set('code', $this->request->data['User']['code']);
            $password = $this->request->data['User']['password'];
            $this->request->data['User']['password'] = '';
            if ($password == $this->request->data['User']['password_confirm']) {
                $code = $this->request->data['User']['code'];
                $options['conditions'] = array(
                    'User.confirmation_code' => $code
                );
                $this->User->contain();
                $user = $this->User->find('first', $options);
                if (isset($user['User'])) {

                    $user['User']['confirmation_code'] = '';
                    $user['User']['password'] = $this->Auth->password($this->request->data['User']['password_confirm']);
                    $this->User->save($user, false);
                    $this->__setMessage(__('Password changed', true));
                    $this->set('success', 1);
                    $this->render('reset1');
                }
            } else {
                $this->__setError(__('Passwords do not match', true));
                $this->render('reset1');
            }
        }
    }

    function bonus() {
        if (!empty($this->request->data)) {
            $code = $this->request->data['User']['bonus_code'];
            $bonusCode = $this->BonusCode->findBonusCode($code);
            if (!empty($bonusCode)) {
                $userId = $this->Session->read('Auth.User.id');
                $bonusCodeId = $bonusCode['BonusCode']['id'];
                $used = $this->BonusCodesUser->findBonusCode($bonusCodeId, $userId);
                if (empty($used)) {
                    //TODO update balance imidiately, decrease promo codes
                    $this->User->addFunds($userId, $bonusCode['BonusCode']['amount']);
                    $this->BonusCode->useCode($bonusCodeId);
                    $this->BonusCodesUser->addCode($bonusCodeId, $userId);
                    $this->set('success', true);
                    $this->__setMessage(__('Promotional code successfully used', true));
                } else {
                    $this->__setError(__('Invalid promotional code', true));
                }
            } else {
                $this->__setError(__('Invalid promotional code', true));
            }
        }
    }

    function login() {
        if ($this->request->isPost()) {
            if ($this->Auth->login()) {
                //check if valid email
                if ($this->Auth->user('status') == 0) {
                    $this->Auth->logout();
                    $this->__setError(__('Confirm your email.'));
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } else if ($this->Auth->user('status') == 2) {
                    $this->Auth->logout();
                    $this->__setError(__('Your account has been indefinitely suspended for violating terms of service'));
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));                    
                }
                $this->_loadPermissions();
                $this->User->updateLastVisit($this->Auth->user('id'));
                return $this->redirect($this->referer($this->Auth->redirect()));
            } else {
                $this->__setError(__('Username or password is incorrect'));
            }
        }
        if ($this->Session->check('Auth.User')) {
            $this->redirect(array('controller' => 'pages', 'action' => 'main'));
        }
    }

    function admin_login() {
        $this->Session->write('Auth.Acos', null);
        $this->layout = 'admin_login';
        $groups = $this->User->Group->getAdminGroups();
        $this->set('groups', $groups);
        if (!empty($this->request->data)) {
            $this->Auth->login();
            if ($this->Session->read('Auth.User.group_id') != $this->request->data['User']['group_id']) {
                $this->__setError(__('Username or password is incorrect'), 'default', array(), 'auth');
                $this->redirect(array('controller' => 'users', 'action' => 'logout'));
            }
            $this->_loadPermissions();
        }
        if ($this->Session->check('Auth.User')) {
            $this->User->updateLastVisit($this->Auth->user('id'));
            $this->redirect(array('controller' => 'dashboard'));
        }
    }

    private function _loadPermissions() {
        $permissions = array();
        $groupId = $this->Auth->user('group_id');
        $this->loadModel('Permission');
        $options = array(
            'conditions' => array(
                'Aro.foreign_key' => $groupId
            ),
            'recursive' => -1
        );
        $aro = $this->Permission->Aro->find('first', $options);
        $options = array(
            'conditions' => array(
                'Permission.aro_id' => $aro['Aro']['id']
            ),
            'fields' => array(
                'Permission.id',
                'Permission.aco_id'
            )
        );
        $acos = $this->Permission->find('list', $options);
        foreach ($acos as $acoId) {
            $nodes = $this->Permission->Aco->getPath($acoId);
            $nodesList = array();
            if ($nodes) {
                foreach ($nodes as $node) {
                    if ($node['Aco']['parent_id'] == 1) {
                        $node['Aco']['alias'] = strtolower($node['Aco']['alias']);
                    }
                    $nodesList[] = $node['Aco']['alias'];
                }
            }
            $path = implode('/', $nodesList);
            $permissions[$path] = true;
        }
        $this->Session->write('permissions', $permissions);
    }

    function logout() {
        $this->redirect($this->Auth->logout());
    }

    function admin_logout() {
        $this->Session->destroy();
        $this->Auth->logout();
        $this->redirect(array('controller' => 'users', 'action' => 'login'));
    }

    function admin_addBalance($id = 0) {

		if (!empty($this->request->data)) {		

            $this->User->addFunds($id, $this->request->data['User']['amount']);

			$deposit['Deposit'] = array(
                'user_id' => $id,
                'type' => __('%s (id: %d) made deposit to your account', $this->Auth->user('username'), $this->Auth->user('id')),
                'amount' => $this->request->data['User']['amount'],
                'status' => 'completed',
                'date' => $this->__getSqlDate()
            );		

            if ($this->User->Deposit->save($deposit)) {
                $deposit['Deposit']['id'] = $this->User->Deposit->id;
                $deposit['Deposit']['deposit_id'] = sprintf('U%1$05dD%2$05d', $id, $this->User->Deposit->id);
                $this->User->Deposit->save($deposit);
            }		

            $user = $this->User->getItem($id);

            $deposit['Deposit'] = array(
                'user_id' => $this->Auth->user('id'),
                'type' => __('add balance to %s (id: %d)', $user['User']['username'], $id),
                'amount' => $this->request->data['User']['amount'],
                'status' => 'completed',
                'date' => $this->__getSqlDate()
            );		

            $this->User->Deposit->create();
            if ($this->User->Deposit->save($deposit)) {
                $deposit['Deposit']['id'] = $this->User->Deposit->id;
                $deposit['Deposit']['deposit_id'] = sprintf('U%1$05dD%2$05d', $id, $this->User->Deposit->id);
                $deposit['Deposit']['status'] = 'completed';
                $this->User->Deposit->save($deposit);
            }

            $this->__setMessage('Funds added successfully');
            $this->request->data = array();
        }
    }

    public function admin_add() {
        if (!empty($this->request->data['User'])) {
            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password_raw']);
            $this->request->data['User']['status'] = 1;
        }
        parent::admin_add();
        $this->request->data['User']['password'] = '';
    }

    function admin_edit($id = NULL) {
        if (!empty($this->request->data)) {
            if (empty($this->request->data['User']['password'])) {
                $user = $this->User->getItem($id);
                $this->request->data['User']['password'] = $user['User']['password'];
            } else {
                $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
            }
        }
        parent::admin_edit($id);
        $this->request->data['User']['password'] = '';
    }

    public function admin_myReport() {
        $this->admin_report($this->Auth->user('id'));
        $this->view = 'admin_report';
    }

    public function admin_report($id = 0) {
        
    }

    public function admin_tickets($id) {
        parent::admin_index(array('Ticket.user_id' => $id), 'Ticket');
        $this->viewPath = 'users';
    }

    public function admin_deposits($id) {
        parent::admin_index(array('Deposit.user_id' => $id), 'Deposit');
        $this->viewPath = 'users';
    }

    public function admin_withdraws($id) {
        parent::admin_index(array('Withdraw.user_id' => $id), 'Withdraw');
        $this->viewPath = 'users';
    }

    public function setTheme($theme) {
        $this->Cookie->write('theme', $theme);
        $this->redirect($this->referer());
    }

    public function admin_deposit_bonus_history($id) {
        //FIXME: someday in traint (php 5.4)
        $this->view = "admin_index";
        $conditions['user_id'] = $id;
        $ret = parent::admin_index($conditions, 'PaymentBonusUsage');
        return $ret;
    }

}

?>