<?php

class MenusController extends AppController {

    public $name = 'Menus';


    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getmenu'));
    }

    function getmenu() {
        return $this->{$this->name}->getMenuItems();
    }

}

?>
