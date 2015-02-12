<?php
class PaymentBonusesController extends AppController {
	public $scaffold;
	function beforeFilter() {
		return parent::beforeFilter();
	}
	
	function __setSelectBox(){
		$l = $this->PaymentBonus->PaymentBonusGroup->find('list');
		$this->set('paymentBonusGroups',$l);
	}
	
	function admin_index($bonus_group_id = null,$conds = array()){
		if ($bonus_group_id == null){
			$this->redirect($this->request->referer());
		}
		$conds['payment_bonus_group_id'] = $bonus_group_id;
		return parent::admin_index($conds);
	}
	function admin_edit($id){
		$this->__setSelectBox();
		return parent::admin_edit($id);
	}
	
	
	function admin_add($bonus_id,$id=NULL){
		$this->__setSelectBox();
		$add = parent::admin_add($id); 
		$this->request->data['PaymentBonus']['payment_bonus_group_id'] = $bonus_id;
		return $add;
	}
	
	function admin_search(){
		$this->__setSelectBox();
		return parent::admin_search();
	}
	
	function admin_delete($id){
		return parent::admin_delete($id);
	}
}