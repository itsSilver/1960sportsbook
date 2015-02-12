<?php

class PluginsController extends AppController {

    public $name = 'Plugins';
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getPlugins'));
    }
    
    function getPlugins($position) {
        return $this->Plugin->getPlugins($position);
    }

}

?>