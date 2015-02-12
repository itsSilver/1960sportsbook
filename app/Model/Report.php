<?php

class Report extends AppModel {

    public $name = 'Report';
    public $useTable = false;
    
    function getTabs($params) {        
        $tabs = array();        
        $tabs['admin_users'] = $this->__makeTab(__('Users', true), 'users', 'reports');
        $tabs['admin_tickets'] = $this->__makeTab(__('Tickets', true), 'tickets', 'reports');
        $tabs['admin_deposits'] = $this->__makeTab(__('Deposits', true), 'deposits', 'reports');
        $tabs['admin_withdraws'] = $this->__makeTab(__('Withdraws', true), 'withdraws', 'reports');
        $tabs['admin_jackpot_winning'] = $this->__makeTab(__('Jackpot', true), 'jackpot_winning', 'reports');
        $tabs['admin_jackpot_size'] = $this->__makeTab(__('Jackpot size', true), 'jackpot_size', 'reports');
        $tabs[$params['action']]['active'] = true;
        return $tabs;
    }

}

?>