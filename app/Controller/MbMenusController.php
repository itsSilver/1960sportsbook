<?php

class MbMenusController extends AppController {

    public $name = 'MbMenus';    
    

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getmenu'));
    }
    
    function getmenu() {
        return $this->MbMenu->getMenuItems();
    }
    
}

?>