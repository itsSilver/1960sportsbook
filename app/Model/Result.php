<?php

class Result extends AppModel {

    public $name = 'Result';
    public $useTable = false;

    function getTabs($params) {
        $tabs = array();
        $tabs[] = $this->__makeTab(__('Results', true), 'index', 'results', NULL, false);
        $tabs[] = $this->__makeTab(__('Finished events in tickets', true), 'allSports', 'results', NULL, false);
        $tabs[] = $this->__makeTab(__('Pending events in tickets', true), 'pendingSports', 'results', NULL, false);
        $tabs[] = $this->__makeTab(__('All finished events', true), 'allAll', 'results', NULL, false);
        if (($params['action'] == 'admin_allSports') || ($params['action'] == 'admin_allLeagues') || ($params['action'] == 'admin_allEvents')) {
            $tabs[1]['active'] = true;
        } else if (($params['action'] == 'admin_pendingSports') || ($params['action'] == 'admin_pendingLeagues') || ($params['action'] == 'admin_pendingEvents')) {
            $tabs[2]['active'] = true;
        } else if (($params['action'] == 'admin_allAll')) {
            $tabs[3]['active'] = true;
        } else {
            $tabs[0]['active'] = true;
        }
        return $tabs;
    }

}

?>