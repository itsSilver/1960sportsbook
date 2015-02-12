<?php
class PaymentBonusGroup extends AppModel{
	public $hasMany= array('PaymentBonus');
	public $displayField = 'name';
	
	public function getActions(){
		$actions = parent::getActions(); 
		
		$show_codes = array(
				'name' => 'Edit codes',
				'action' => 'index',
				'controller' => 'payment_bonuses'
		);
		
		array_unshift($actions, $show_codes);
		
		return $actions;
	}
}