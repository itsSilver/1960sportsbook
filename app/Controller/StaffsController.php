<?php

class StaffsController extends AppController {

    public $name = 'Staffs';

    function admin_edit($id = NULL) {
        if (!empty($this->request->data)) {
            if (empty($this->request->data['Staff']['password'])) {
                $staff = $this->Staff->getItem($id);
                $this->request->data['Staff']['password'] = $staff['Staff']['password'];
            }
            else
                $this->request->data['Staff']['password'] = $this->Auth->password($this->request->data['Staff']['password']);
        }
        parent::admin_edit($id);
        $this->request->data['Staff']['password'] = '';
    }

    function admin_add($id = NULL) {
        if (!empty($this->request->data)) {
            $this->request->data['Staff']['password'] = $this->Auth->password($this->request->data['Staff']['password_raw']);
            $this->request->data['Staff']['status'] = 1;
        }
        parent::admin_add($id);
        $this->request->data['Staff']['password'] = '';
    }
    
    public function admin_deposit_bonus_history($id) {
    	//FIXME: someday in traint (php 5.4)
    	$this->view="admin_index";
    	$conditions['user_id'] = $id;
    	$ret =  parent::admin_index($conditions,'PaymentBonusUsage');
    	return $ret;
    
    }

}

?>