<?php

class Risk extends AppModel {

    public $name = 'Risk';
    public $useTable = false;

    function getTabs($params) {        
        $tabs = array();
        if ($params['action'] == 'admin_warnings') {
            $tabs[] = $this->__makeTab(__('Warnings', true), 'warnings', 'risks', NULL, true);
            $tabs[] = $this->__makeTab(__('Settings', true), 'warnings', 'settings');
            return $tabs;
        }
        $tabs[] = $this->__makeTab(__('General Settings', true), 'index', 'risks', NULL, true);
        $tabs[] = $this->__makeTab(__('Sports', true), 'sports', 'risks');
        $tabs[] = $this->__makeTab(__('Leagues', true), 'leagues', 'risks');
        return $tabs;
    }

}

?>