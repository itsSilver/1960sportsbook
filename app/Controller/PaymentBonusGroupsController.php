<?php
class PaymentBonusGroupsController extends AppController {
	public $scaffold;
	
	function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_index($conditions = array()){
		parent::admin_index($conditions);
	}
	
}