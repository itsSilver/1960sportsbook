<?php

class MtMenusController extends AppController {

    public $name = 'MtMenus';
    

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getmenu'));
    }

    function getmenu() {
        return $this->MtMenu->getMenuItems();
    }
   
}

?>