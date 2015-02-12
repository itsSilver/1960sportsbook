<?php
class ReferralsController extends AppController {
	
	public $name = 'Referrals';
	public $actsAs = array('Containable');
	
	public function admin_index($status = "pending")
	{
		$model = $this->__getModel();
		$this->paginate = $this->Referral->getPagination($status);
		$data = $this->paginate();		
		foreach ($data as &$row) {
			foreach ($row['SignedUp'] as $key => $value) {
				$row['Referral'][$key] = $value;
			}	
			foreach ($row['ReferredBy'] as $key => $value) {
				$row['Referral'][$key] = $value;
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
		$this->Referral->complete($id);	
		$this->__setMessage(__('Referral bonus was sent. Total bonus of ' . $bonusAmount));
		$this->redirect($this->referer());
	}
	
	public function admin_cancel($id) {
		$this->Referral->setStatus($id, 'canceled');
		$this->__setMessage(__('Referral bonus was cancelled'));
		$this->redirect($this->referer());
	}
}
?>
