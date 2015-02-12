<?php

class DashboardController extends AppController {

    public $name = 'Dashboard';

    function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow(array('admin_index'));
    }

    function admin_index() {
     
        //prevent redirect loop in case group don\'t have access to dashboard \
        if (!$this->Session->check('Auth.User.id')) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        
    }
}

?>