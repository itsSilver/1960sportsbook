<?php

class Setting extends AppModel {

    public $name = 'Setting';

    function saveSettings($settings) {
        $data = array();
        foreach ($settings['Setting'] as $key => $value) {
            $data[] = array(
                'id' => $key,
                'value' => $value
            );
        }
        return ($this->saveAll($data));
    }

    function getGeneralSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'timeFormat',
                'eventDateFormat',
                'websiteName',
                'registration',
                'contactMail',
                'defaultCurrency',
                'defaultTimezone',
                'defaultLanguage',
                'printing',
                'ticketPreview',
                'itemsPerPage',
                'defaultTheme',
                'contactEmail',
                'copyright',
                'referals',
                'login',
                'passwordReset',
                'testDeposit',
                'allowMultiSingleBets',
                'charset',
            	'show_main_event_id',
            	'show_sub_event_id',
                'feedType'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getSeoSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'metaDescription',
                'metaKeywords',
                'metaAuthor',
                'metaReplayTo',
                'metaCopyright',
                'metaRevisitTime',
                'metaIdentifierUrl',
                'defaultTitle'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getWarningsSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'bigDeposit',
                'bigWithdraw',
                'bigStake',
                'bigOdd',
                'bigWinning'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getTicketsSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'printing',
                'ticketPreview'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getDepositsSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'deposits',
                'D_VoguePayMerchantId',
                'D_VoguePay',
                'D_EyowoWalletCode',
                'D_Eyowo',
                'D_Manual',
                'D_Vtn',
                'D_VtnMerchantEmailId',
                'D_VtnCallbackId',
                'D_Umf',
                'D_UmfSeller',
                'D_Bardo',
                'D_BardoShopId',
            	'lr_merchantEmail',
            	'lr_merchantSecurityWord',
            	'lr_merchantStoreName',
            	'lr_merchantAccountNumber',
            	'wm_pursue',
            	'paypal_password',
            	'paypal_email',
            	'paypal_signature',
            	'deposit_funding_percentage',
            	'vtn_deposit_funding_percentage',
            	'eyowo_deposit_funding_percentage',
            	'bardo_deposit_funding_percentage',
            	'lr_deposit_funding_percentage',
            	'webmoney_deposit_funding_percentage',
            	'paypal_deposit_funding_percentage'
            		
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getDepositsRisksSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'minDeposit',
                'maxDeposit'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getWithdrawsSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'withdraws'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getWithdrawsRisksSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'minWithdraw',
                'maxWithdraw'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getRiskSettings() {
        $options['conditions'] = array(
            'Setting.key' => array(
                'minBet',
                'maxBet',
                'maxBetsCount',
                'minBetsCount',
                'maxWin'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    function getJackpotSettings() {
        $options['conditions'] = array(
            'Setting.key' => array(
                'jackpot',
                'jackpotPercent',
                'jackpotMinOdds'/*,
                'jackpotIncrease',
                'jackpotPicks6',
                'jackpotPicks7',
                'jackpotPicks8',
                'jackpotPicks9',
                'jackpotPicks10'*/
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }
  
    
    /**
     * 
     */
    public function getDepositSettings() {
    	$options['conditions'] = array(
    			'Setting.key' => array(
    					'deposit_funding_percentage'    					
    			)
    	);
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
    		$list[$setting['Setting']['key']] = $setting['Setting'];
    	}    	
    	return $list;
    }
    
    /**
     *
     */
    public function getReferralSettings() {
    	$options['conditions'] = array(
    			'Setting.key' => array(
    					'referral_deposit_percentage'
    			)
    	);
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
    		$list[$setting['Setting']['key']] = $setting['Setting'];
    	}
    	return $list;
    }
    
    /**
     *
     */
    public function getPromoSettings($layout='') {

		if ($layout == 'admin') {
			$type = 0;
		} else {
			$type = 1;
		}
    	$options['conditions'] = array(
    			'Setting.key' => array(    					
    					'left_promo_header',
    					'left_promo_body',
    					'right_promo_header',
    					'right_promo_body',
    					'left_promo_enabled',
    					'right_promo_enabled',
    					'bottom_promo_header',
    					'bottom_promo_body',
    					'bottom_promo_enabled'
    			),
				'Setting.type' => $type
    	);
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
    		$list[$setting['Setting']['key']] = $setting['Setting'];
    	}
    	return $list;
    }
    

    function getTabs($params) {
        $tabs = array();
        if ($params['action'] == 'admin_warnings') {
            $tabs[] = $this->__makeTab(__('Warnings', true), 'warnings', 'risks');
            $tabs[] = $this->__makeTab(__('Settings', true), 'warnings', 'settings', NULL, true);
            return $tabs;
        }
        $tabs[] = $this->__makeTab('General Settings', 'index', 'settings', NULL, true);
        return $tabs;
    }

    function updateField($field, $value) {
        $options['conditions'] = array(
            'Setting.key' => $field
        );
        $data = $this->find('first', $options);
        $data['Setting']['value'] = $value;
        $this->save($data);
    }

}

?>
