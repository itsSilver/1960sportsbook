<?php

class SettingsController extends AppController {

    public $name = 'Settings';

    //list all settings
    function admin_index() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getGeneralSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
        
        $this->loadModel('Currency');
        $this->loadModel('Languages');
        $currencies = $this->Currency->getList();
        $locales = $this->Language->getIdLangueageList();
        $this->set('currencies', $currencies);
        $this->set('locales', $locales);
    }
    
    function admin_ticket() {
        
    }
    
    function admin_seo() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getSeoSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }
    
    function admin_warnings() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getWarningsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }
    
    function admin_jackpot() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getJackpotSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }
    
    function admin_tickets() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getTicketsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    function admin_deposits() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getDepositsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }
    function admin_depositsRisks() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getDepositsRisksSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }
    function admin_withdraws() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getWithdrawsSettings();
        $this->request->data = $data;        
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }
    function admin_withdrawsRisks() {
        if (!empty($this->request->data)) {            
            if ($this->__save()) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getWithdrawsRisksSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }
    
    function admin_risk() {
        
    }    
    //do we need more interface?
       
    public function admin_referral() {
    	if (!empty($this->request->data)) {
    		if ($this->__save()) {
    			$this->__setMessage(__('Settings saved.', true));
    		} else {
    			$this->__setError(__('can\'t save settings.', true));
    		}
    	}
    	$data = $this->Setting->getReferralSettings();
    	$this->request->data = $data;
    	$this->set('data', $data);
    	$this->set('tabs', $this->Setting->getTabs($this->params));
    }
    
    public function admin_promo()
    {
		$this->layout = $this->Session->read('dashboard_type');
    	if (!empty($this->request->data)) {
    		if ($this->__save()) {
    			$this->__setMessage(__('Settings saved.', true));
    		} else {
    			$this->__setError(__('can\'t save settings.', true));
    		}
    	}
    	$data = $this->Setting->getPromoSettings($this->layout);
    	$this->request->data = $data;
    	$this->set('data', $data);
    	$this->set('tabs', $this->Setting->getTabs($this->params));
    }
    
    function admin_deposit() {

        if (!empty($this->request->data)) {
        if ($this->__save()) {
        $this->__setMessage(__('Settings saved.', true));
        } else {
        $this->__setError(__('can\'t save settings.', true));
        }
        }
        $data = $this->Setting->getDepositsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));

    }
    
    function __save() {
        $data = array();
        foreach ($this->request->data['Setting'] as $key => $value) {
            $data[] = array(
                'id' => $key,
                'value' => $value
            );
        }        
        return ($this->Setting->saveAll($data));
    }
    
}

?>
