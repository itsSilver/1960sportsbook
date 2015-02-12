<?php

define('CANCELED', -2);
define('LOST', -1);
define('PENDING', 0);
define('WIN', 1);

App::import('Sanitize');

class AppController extends Controller {

    public $components = array('Acl', 'Auth', 'Session', 'Cookie', 'Email', 'BetApi', 'RequestHandler', 'Rest.Rest');
    public $helpers = array('Html', 'Form', 'Js', 'Session', 'Beth', 'Text', 'TimeZone', 'MyForm', 'MyHtml');
    public $paginate = array();
    public $viewClass = 'Theme';
    public $uses = array('Log');

	function checkSession() {		
		if (!$this->Session->check('Auth.User')) {
		   $this->redirect(array('controller' => 'users','action' => 'logout'));
		}		
	}

    function beforeFilter() {
        //$this->Session->write('Config.language', 'fre');
        //TODO timezone?
        date_default_timezone_set('UTC');

		if($this->Session->read('dashboard_type')){
			$layout = $this->Session->read('dashboard_type');
			if($layout == 'admin'){
				$type = 0;
			} else {
				$type = 1;	
			}				
		}
		if($this->Session->read('dashboard_type_user')){
			$layout = $this->Session->read('dashboard_type_user');
			if($layout == 'default'){
				$type = 0;
			} else {
				$type = 1;	
			}
		}
        $this->__loadSettings($type);

        $this->Auth->authenticate = array('Form');
        $this->Auth->authorize = array(
            'Actions' => array('actionPath' => 'controllers')
        );

        //$this->Auth->authorize = 'actions';
        //$this->Auth->actionPath = 'controllers/';

        $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
        $this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'main');
        $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'main');
        if (isset($this->params['admin']) && $this->params['admin'] == 1) {
			//Code for changing Dashboard
			if (isset($this->request->data['dashbaordAdmin'])) {
				$this->Session->write('dashboard_type', $this->request->data['dashbaordAdmin']);
				$this->redirect(array('controller' => 'dashboard'));
				exit;
			} else if($this->Session->read('dashboard_type')) {
				$this->Session->write('dashboard_type', $this->Session->read('dashboard_type'));
			} else {
				$this->Session->write('dashboard_type', 'admin');
			}

            //$this->layout = 'admin';
			$this->layout = $this->Session->read('dashboard_type');
            $this->Auth->loginRedirect  = array('controller' => 'dashboard');
            $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
            $this->Auth->autoRedirect = false;
            if ($this->Session->read('Auth.User.id')) {
                if ($this->Session->read('Auth.User.group_id') == 1) {
                    $this->redirect($this->Auth->logout());
                }
            }
        } else {

			//Code for changing Dashboard
			if (isset($this->params['pass'][0]) && ($this->params['pass'][0]=='default' || $this->params['pass'][0]=='user_lottery')) {
				$this->Session->write('dashboard_type_user', $this->params['pass'][0]);
				$this->redirect(array('controller' => 'pages','action' => 'main'));
				exit;				
			} else if($this->Session->read('dashboard_type_user')) {
				$this->Session->write('dashboard_type_user', $this->Session->read('dashboard_type_user'));
			} else {
				$this->Session->write('dashboard_type_user', 'default');
			}
			$this->layout = $this->Session->read('dashboard_type_user');
			if ($this->Session->read('Auth.User.id')) {
                if ($this->Session->read('Auth.User.group_id') != 1) {
                    //redirect admins who try to access betting site                    
                    //$this->redirect(array('controller' => 'dashboard', 'prefix' => 'admin', 'admin' => 1));
                    //$this->Auth->logout();
                }
            }
        }
    }

    function __loadSettings($type=0) {
        if (Configure::Read('Settings.initialized') == NULL) {
            $this->loadModel('Setting');
			$options['conditions'] = array('Setting.type' => $type);
            $settings = $this->Setting->find('all',$options);
            foreach ($settings as $setting) {
                Configure::Write('Settings.' . $setting['Setting']['key'], $setting['Setting']['value']);
            }
            Configure::Write('Settings.initialized', 1);
            //fix time zone issues  
            Configure::write('time_zone', Configure::read('Settings.defaultTimezone'));
            $this->Session->write('time_zone', Configure::read('Settings.defaultTimezone'));
            //set themes            
            Configure::Write('Settings.theme', Configure::read('Settings.defaultTheme'));
            if ($this->Cookie->read('theme')) {
                Configure::write('Settings.theme', $this->Cookie->read('theme'));
            } else {
                //$this->Cookie->write('theme', Configure::read('Settings.defaultTheme'));
            }
            $this->theme = Configure::read('Settings.theme');

            //set default currency codes
            $this->loadModel('Currency');
            $currencies = $this->Currency->getCodesList();
            $this->Session->write('Currencies', $currencies);
            Configure::Write('Settings.currency', $currencies[Configure::Read('Settings.defaultCurrency')]);

            if ($this->Session->read('Auth.User.id')) {
                //reset default currency 
                //Configure::Write('Settings.currency', $currencies[$this->Auth->user('currency_id')]);

                $this->loadModel('Language');
                $locale = $this->Language->findById($this->Session->read('Auth.User.language_id'));
                $localeName = $locale['Language']['name'];
                $language = $locale['Language']['language'];
                Configure::write('Config.language', $localeName);
                //var_dump( $localeName );
                $this->loadModel('User');
                $user = $this->User->getItem($this->Session->read('Auth.User.id'));
                $this->Session->write('Auth.User.balance', $user['User']['balance']);

                $this->loadModel('Group');
                $group = $this->Group->getItem($this->Session->read('Auth.User.group_id'));
                $this->Session->write('Auth.User.language', $language);
                $this->Session->write('Auth.User.group', $group['Group']['name']);
            } else {

                if ($this->Cookie->read('language')) {
                    $language = $this->Cookie->read('language');
                    Configure::write('Config.language', $language);
                } else {
                    //var_dump($this->Cookie->read('language'));
                    //FIXME: default o english. WE have a problem when no english locale or default language must be different
                    Configure::write('Config.language', 'en_us');
                }
            }

            //handle jackpot
            $lastUpdate = Configure::read('Settings.jackpotLastUpdate');

            $year = date('Y');
            $month = date('m');
            $monthStar = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
            $monthStart = strtotime($monthStar);

            if ($monthStart > $lastUpdate) {
                $this->loadModel('JackpotWinning');
                $this->loadModel('Jackpot');
                $this->loadModel('Deposit');
                $this->loadModel('User');
                $winner = $this->JackpotWinning->getMonthTop(1);
                $winning = $this->Jackpot->getSize();
                $this->Setting->updateField('jackpotLastUpdate', $monthStart);
                if (!empty($winner)) {
                    $winnerId = $winner[0]['JackpotWinning']['user_id'];
                    $winning = $winning[0][0]['pot'];
                    $this->Deposit->saveDeposit($winnerId, $winning, 'JackPot', $monthStar, '', 'completed');
                    $this->User->addFunds($winnerId, $winning);
                }
            }
        }
        //timed events
        if (strtotime($this->__getSqlDate()) - strtotime(Configure::read('Settings.lastDepositUpdate')) > 60) {

            $this->loadModel('Setting');
            $this->Setting->updateField('lastDepositUpdate', $this->__getSqlDate());
            //handle payment eyowo                
            $this->_updateDeposits();
        }
    }

    private function _updateDeposits() {
        $this->loadModel('Deposit');
        $this->loadModel('User');
        $deposits = $this->Deposit->getDepositsByType('Eyowo');

        foreach ($deposits as $deposit) {
            $url = 'https://www.eyowo.com/api/gettransactionstatus?format=xml&'
                    . 'walletcode=' . Configure::read('Settings.D_EyowoWalletCode')
                    . '&transactionref=' . $deposit['Deposit']['deposit_id'];
            $response = simplexml_load_file($url);
            $status = (string) $response->STATUS;
            if ($status == 'Error') {
                //cancel old
                if (strtotime($this->__getSqlDate()) - strtotime($deposit['Deposit']['date']) > 60 * 60 * 24) {
                    $this->Deposit->setStatus($deposit['Deposit']['id'], 'canceled');
                }
            } else if (($status == 'Aborted') || ($status == 'Failed') || ($status == 'Denied')) {
                $this->Deposit->setStatus($deposit['Deposit']['id'], 'canceled');
            } else if ($status == 'Approved') {
                $this->loadModel('DepositMeta');
                $this->loadModel('PaymentBonus');
                $this->loadModel('PaymentBonusUsage');
                $amount = $deposit['Deposit']['amount'];
                $userId = $deposit['Deposit']['user_id'];
                $depositId = $deposit['Deposit']['deposit_id'];

                $ret = $this->DepositMeta->getDeposit($userId, $amount, $depositId);
                $ret = unserialize($ret);

                $calc = $this->PaymentBonus->calculateBonus($ret, $amount);
                if ($ret != null and isset($ret['PaymentBonus'])) {
                    $details = 'Used bonus code ' . $ret['PaymentBonusGroup']['name'] . '>' . $ret['PaymentBonus']['bonus_code'] . ' added amount ' . $calc['bonusAmount'];
                } else {
                    $details = "";
                }

                //$this->Deposit->saveDeposit($userId, $calc['totalAmount'], 'Eyowo', $depositId, 'Used bonus code ' . $details);
                $this->Deposit->updateDeposit($deposit['Deposit']['id'], $calc['totalAmount'], 'Eyowo', $details);
                $this->Deposit->setStatus($deposit['Deposit']['id'], 'completed');

                $this->PaymentBonusUsage->commitBonus($ret, $calc, $userId);
                $amount = $calc['totalAmount'];

                $this->User->addFunds($userId, $amount);
            }
        }
    }

    function __sendMail($templateName, $to, $vars) {
        App::import('Model', 'Template');
        $Template = new Template();
        $template = $Template->find('first', array('conditions' => array('Title' => $templateName)));
        $content = $template['Template']['content'];
        $content = $this->__insertVariables($content, $vars);
        $subject = $template['Template']['subject'];
        $subject = $this->__insertVariables($subject, $vars);
        $this->__send($to, $subject, $content);
    }

    function __send($to, $subject, $content, $bcc = array()) {
        App::uses('Validation', 'Utility');
        if (Validation::email($to)) {
            $email = new CakeEmail();
            $email->to($to);
            $email->bcc($bcc);
            $email->subject($subject);
            //TODO get from config
            $email->replyTo(Configure::read('Settings.contactMail'));
            $email->from(Configure::read('Settings.contactMail'));
            $email->emailFormat('both');
            $email->template('default', 'default');
            $email->viewVars(array('template' => $content));

            $email->send();
        }
    }

    function __insertVariables($template, $vars = array()) {
        foreach ($vars as $key => $value) {
            if (is_string($value))
                $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }

    //------------------------
    //admin scaffold functions  
    //------------------------  


    function admin_index($conditions = array(), $model = NULL) {

        $model = $this->__getModel($model);

        if (!is_array($conditions)) {
            $parent = $this->$model->getParent();
            $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
            $conditions = array(
                $model . '.' . $foreignKey => $conditions
            );
        }

        //get pagination conditions
        $this->paginate = $this->{$model}->getIndex();
        $this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        if ($this->$model->isOrderable()) {
            $this->paginate['order'] = array($model . '.order' => 'asc');
        }

        if (isset($this->paginate['conditions'])){
            $this->paginate['conditions'] = array_merge($this->paginate['conditions'], $conditions);
		} else {
            $this->paginate['conditions'] = $conditions;
		}

		//-- THIS CODE ADDED BY PRAVEEN SINGH ON 12/12/2013
		if(isset($this->params['prefix']) && ($this->params['prefix']=='admin') && isset($this->params['controller']) && ($this->params['controller']=='slides' || $this->params['controller']=='mh_menus')) {			
			$this->layout = $this->Session->read('dashboard_type');
			if (isset($this->layout) && $this->layout == 'admin'){
				$this->paginate['conditions'] = array('type' => 0);
			} else {
				$this->paginate['conditions'] = array('type' => 1);
			}			
		}
		//-- /THIS CODE ADDED BY PRAVEEN SINGH ON 12/12/2013

        $this->$model->locale = Configure::read('Admin.defaultLanguage');
        $data = $this->paginate($model);	

        $data = $this->{$model}->getIdNames($data);
        $this->set('data', $data);

        $translate = false;
        if (isset($this->{$model}->actsAs['Translate'])) {
            $translate = true;
        }
        $this->set('translate', $translate);

        $this->set('actions', $this->{$model}->getActions());
        return $data;
    }

    function admin_search() {
        $model = $this->__getModel();
        if (!empty($this->request->data)) {
            $conditions = $this->{$model}->getSearchConditions($this->request->data);
            $this->admin_index($conditions);
            $this->view = 'admin_index';
            //$this->render('admin_index');
            return;
        }
        $fields = $this->{$model}->getSearch();
        $this->set('fields', $fields);
    }

    function admin_view($id = -1) {
        $model = $this->__getModel();
        $this->{$model}->locale = Configure::read('Admin.defaultLanguage');
        $data = $this->{$model}->getView($id);
        if (!empty($data)) {
            $data = $this->{$model}->getIdNames($data);
            $this->set('fields', $data);
        } else {
            $this->__setError(__('can\'t find', true));
        }
    }

    function __getModel($model = NULL) {
        if ($model == NULL)
            $model = Inflector::singularize($this->name);
        if (!isset($this->$model))
            $this->loadModel($model);
        $this->set('model', $this->{$model}->name);
        $this->set('singularName', $this->{$model}->getName());
        $this->set('pluralName', $this->$model->getPluralName());
        $this->set('tabs', $this->$model->getTabs($this->params));
        $this->set('orderable', $this->$model->isOrderable());
        $this->set('mainField', 1);
        $this->set('translate', false);
        $this->viewPath = 'AdminCommon';
        return $model;
    }

    function admin_edit($id) {
        $model = $this->__getModel();
        $this->$model->validate = $this->$model->getValidation();
        if (!empty($this->request->data)) {
            //save changes
            $this->{$model}->locale = Configure::read('Admin.defaultLanguage');
            $this->request->data[$model]['id'] = $id;
            if ($this->{$model}->save($this->request->data)) {
                $this->__setMessage(__('Changes saved', true));
                $this->redirect(array('action' => 'index'));
            }
            $this->__setError(__('can\'t  save item', true));
        }

        $this->request->data = $this->$model->getItem($id);

        $fields = $this->{$model}->getEdit();
        $this->set('fields', $fields);
    }

    function admin_add($id = NULL) {
        $model = $this->__getModel();
        $this->$model->validate = $this->$model->getValidation();
        if (!empty($this->request->data)) {
            //save changes
            $this->{$model}->locale = Configure::read('Admin.defaultLanguage');
            if ($id != NULL) {
                $parent = $this->$model->getParent();
                $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
                $this->request->data[$model][$foreignKey] = $id;
            }
            if ($this->$model->isOrderable()) {
                //get the biggest order
                $order = $this->$model->findLastOrder();
                $this->request->data[$model]['order'] = $order + 1;
            }
            if ($this->{$model}->validates()) {
                if ($this->{$model}->save($this->request->data)) {
                    $this->__setMessage(__('Item added', true));
                    $this->redirect(array('action' => 'index', $id));
                }
            }
            $this->__setError(__('can\'t  add item', true));
        }

        if ($id != NULL) {
            $parent = $this->$model->getParent();
            $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
            $this->request->data[$model][$foreignKey] = $id;
        }
        $fields = $this->{$model}->getAdd();
        $this->set('fields', $fields);
    }

    function admin_translate($id, $locale = NULL) {
        $model = $this->__getModel();

        //save translation
        if (!empty($this->request->data)) {
            //save changes
            $this->{$model}->locale = $this->request->data[$model]['locale'];
            $this->request->data[$model]['id'] = $id;
            if ($this->{$model}->save($this->request->data)) {
                $this->__setMessage(__('Item added', true));
                $this->redirect(array('action' => 'index'));
            }
            $this->__setError(__('can\'t  add item', true));
        }

        $this->loadModel('Language');
        $locales = $this->Language->getLanguagesList();
        unset($locales[Configure::read('Admin.defaultLanguage')]);
        $this->set('locales', $locales);


        if (isset($locale))
            $this->{$model}->locale = $locale;
        $this->request->data = $this->{$model}->getItem($id);

        $fields = $this->{$model}->getTranslate();
        $this->set('fields', $fields);
    }

    function admin_delete($id) {
        $model = $this->__getModel();
        if ($this->$model->delete($id)) {
            $this->__setMessage(__('Item deleted', true));
            //$this->redirect(array('action' => 'index'));
            $this->redirect($this->referer(array('action' => 'index')));
        } else {
            $this->__setError(__('can\'t delete item', true));
        }
    }

    function admin_moveUp($id) {
        $model = $this->__getModel();
        $this->$model->moveUp($id);
        $this->redirect(array('action' => 'index'));
    }

    function admin_moveDown($id) {
        $model = $this->__getModel();
        $this->$model->moveDown($id);
        $this->redirect(array('action' => 'index'));
    }

    function __setMessage($message) {
        $this->Session->setFlash($message, 'flash_message', array('class' => 'success'));
    }

    function __setError($message) {
        $this->Session->setFlash($message, 'flash_message', array('class' => 'error'));
    }

    function __getSqlDate($date = null) {
        if (isset($date)) {
            return date('Y-m-d H:i:s', $date);
        }
        return gmdate('Y-m-d H:i:s');
    }

    function __uploadFiles($folder, $formdata, $itemId = null) {

        // setup dir names absolute and relative  
        $folder_url = WWW_ROOT . $folder;
        $rel_url = $folder;

        // create the folder if it does not exist  
        if (!is_dir($folder_url)) {
            mkdir($folder_url);
        }

        // if itemId is set create an item folder  
        if ($itemId) {
            // set new absolute folder  
            $folder_url = WWW_ROOT . $folder . '/' . $itemId;
            // set new relative folder  
            $rel_url = $folder . '/' . $itemId;
            // create directory  
            if (!is_dir($folder_url)) {
                mkdir($folder_url);
            }
        }

        // list of permitted file types, this is only images but documents can be added  
        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');

        // loop through and deal with the files  
        foreach ($formdata as $file) {
            // replace spaces with underscores  
            $filename = str_replace(' ', '_', $file['name']);
            // assume filetype is false  
            $typeOK = false;
            // check filetype is ok  
            foreach ($permitted as $type) {
                if ($type == $file['type']) {
                    $typeOK = true;
                    break;
                }
            }

            // if file type ok upload the file  
            if ($typeOK) {
                // switch based on error code  
                switch ($file['error']) {
                    case 0:
                        // check filename already exists  
                        if (!file_exists($folder_url . '/' . $filename)) {
                            // create full filename  
                            $full_url = $folder_url . '/' . $filename;
                            // upload the file  
                            $success = move_uploaded_file($file['tmp_name'], $full_url);
                        } else {
                            // create unique filename and upload file                              
                            $now = (int) gmdate('U');
                            $filename = $now . $filename;
                            $full_url = $folder_url . '/' . $filename;
                            $success = move_uploaded_file($file['tmp_name'], $full_url);
                        }
                        // if upload was successful  
                        if ($success) {
                            // save the url of the file                              
                            $result['urls'][] = $filename;
                        } else {
                            $result['errors'][] = "Error uploaded $filename. Please try again.";
                        }
                        break;
                    case 3:
                        // an error occured  
                        $result['errors'][] = "Error uploading $filename. Please try again.";
                        break;
                    default:
                        // an error occured  
                        $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                        break;
                }
            } elseif ($file['error'] == 4) {
                // no file was selected for upload  
                $result['nofiles'][] = "No file Selected";
            } else {
                // unacceptable file type  
                $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
            }
        }
        return $result;
    }

	//Function added by Praveen Singh on 12/13/2013
	Public function __printPDF($filename){
		ob_start();		
		include($_SERVER['DOCUMENT_ROOT'].'/app/webroot/View/'.$filename.'');		
		$content = ob_get_clean();		
		// convert in PDF
		require_once($_SERVER['DOCUMENT_ROOT'].'/app/Vendor/htmltopdf/html2pdf.class.php');
		try {
			$html2pdf = new HTML2PDF('P', 'A4', 'en');
			//$html2pdf->setModeDebug();
			$html2pdf->setDefaultFont('Arial');
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			//$html2pdf->Output(''.$file_name.'.pdf');
			$html2pdf->Output(''.$file_name.'.pdf','d');
		} catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
	function __arraysort($array, $on, $order=SORT_ASC){

		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}
		unset($array,$sortable_array);
		return $new_array;
	}

	function __randomNumGenerator($range_start=1,$range_end=49,$random_string_length=10){
		$random_string = "";
		for ($i = 0; $i < $random_string_length; $i++) {
		  $ascii_no = round( mt_rand( $range_start , $range_end ) ); // generates a number within the range
		  // finds the character represented by $ascii_no and adds it to the random string
		  // study **chr** function for a better understanding
		  $random_string_str .= $ascii_no.',';
		}
		if($random_string_str!='')
		return $random_string=substr($random_string_str,0,-1);
	}

	function __getEndDatefromdays($currentDate,$numberofdays) {
		return $enddate = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s", strtotime($currentDate)) . " +".$numberofdays."days"));
	}

}