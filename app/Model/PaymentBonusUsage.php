<?php
class PaymentBonusUsage extends AppModel{
	public $belongsTo = array('User');
	
	/**
	 * 
	 * @param int $bonus_id
	 * @param int $user_id
	 * @return int count of used bonus code
	 */
	public function getUsedCount($bonus_id,$user_id){
		$options['recursive'] = -1;
		$options['conditions'] = array (
				'PaymentBonusUsage.user_id' => $user_id,
				'PaymentBonusUsage.payment_bonus_id' => $bonus_id
			);
		
		return $this->find('count',$options);
	}
	
	/**
	 * Disable uneeded tabs
	 * (non-PHPdoc)
	 * @see AppModel::getTabs()
	 */
	public function getTabs($params){
		$tabs = parent::getTabs($params);
		
		unset($tabs['usersadmin_add']);
		unset($tabs['usersadmin_search']);
		
		return $tabs;
	}
	
	public function getActions(){
		return array();
	}
	
	/**
	 * Save log
	 * @param array $paymentbonus
	 * @param array $calcamounts
	 */
	public function commitBonus($paymentbonus,$calcamounts,$userid){
	  if ($paymentbonus == null || !isset($paymentbonus['PaymentBonus']) ) return true;
	  $data['PaymentBonusUsage'] = array();
	  $data['PaymentBonusUsage']['user_id'] = $userid;
	  $data['PaymentBonusUsage']['payment_bonus_id'] = $paymentbonus['PaymentBonus']['id'];
	  $data['PaymentBonusUsage']['transfer_total_amount'] = $calcamounts['totalAmount'];
	  $data['PaymentBonusUsage']['transfer_bonus'] = $calcamounts['bonusAmount'];
	  $data['PaymentBonusUsage']['payment_bonus_title'] =  $paymentbonus['PaymentBonusGroup']['name'];
	  $data['PaymentBonusUsage']['payment_bonus_code'] = $paymentbonus['PaymentBonus']['bonus_code'];
	  return $this->save($data);
	}
}