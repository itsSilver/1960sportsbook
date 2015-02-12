<?php

class PaymentBonus extends AppModel {

    public $belongsTo = array('PaymentBonusGroup');

    /**
     * Get bonus code wich is valid
     * @param int $bonusGroup bonus group id
     * @param string $bonusCode code
     */
    public function getBonus($bonusGroup, $bonusCode) {
        $options['conditions'] = array(
            'PaymentBonus.bonus_code' => $bonusCode,
#	 		'PaymentBonus.payment_bonus_group_id' => $bonusGroup,
            'PaymentBonus.valid_before > NOW()',
            'PaymentBonus.valid_after  < NOW()',
        );

        //$options['recursive'] = -1;

        return $this->find('first', $options);
    }

    /**
     * (non-PHPdoc)
     * @see AppModel::getTabs()
     */
    public function getTabs($params) {
        $tabs = parent::getTabs($params);
        unset($tabs['payment_bonusesadmin_search']);
        foreach ($tabs as $key => &$value) {
            if ($value['url'] != '#') {
                $value['url'][] = $params['pass'][0];
            }
        }

        return $tabs;
    }

    /**
     * Calculate bonus from given amount
     * @bug: maybe someone knows better place for this function (in traint)
     * @warning: rounds up
     * @see http://php.net/round
     * @param $bonuscodeObject array bonus stucture
     * @param $amount amount to deposit
     * @return double bonus amount
     */
    function calculateBonus($bonuscodeObject, $amount) {
        if ($bonuscodeObject == null or !isset($bonuscodeObject['PaymentBonus'])) {
            $ret['totalAmount'] = $amount;
            $ret['bonusAmount'] = 0;
            return $ret;
        }
        $ret = array();
        $bonus = round($amount * $bonuscodeObject['PaymentBonus']['bonus_multiplier'] - $amount, 2);
        if ($bonuscodeObject['PaymentBonus']['max_bonus'] > 0) {
            if ($bonuscodeObject['PaymentBonus']['max_bonus'] < $bonus) {
                $bonus = $bonuscodeObject['PaymentBonus']['max_bonus'];
            }
        }
        $ret['totalAmount'] = round($bonus + $amount, 2);
        $ret['bonusAmount'] = $bonus;
        //apply bonus code limits        
        return $ret;
    }

}