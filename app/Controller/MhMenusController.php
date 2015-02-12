<?php

class MhMenusController extends AppController {

    public $name = 'MhMenus';
    

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getmenu','getmenulottery'));
    }

    function getmenu() {
        return $this->MhMenu->getMenuItems();
    }

	function getmenulottery() {
        return $this->MhMenu->getmenuitemsLottery();
    }
   
}

?>