<?php

class Mail extends appModel {

    public $name = 'Mail';
    public $useTable = false;
    public $validate = array(
        'email' => array(
            'rule' => 'email',
            'message' => 'Please enter valid email address'
        ),
    	/* validation bug fix */    	
        'subject' => array(
            'rule' => array('maxLength', '100'),
            'allowEmpty' => false,
            'message' => 'Please enter subject shorter than 100 length'
        ),
        'content' => array(
            'rule' => array('minLength', '1'),
            'allowEmpty' => false,
            'message' => 'Please enter message'
        )
    );
     
    function getTabs($params) {
        $tabs = array();
        $tabs[] = $this->__makeTab(__('Send Mail', true), 'index', 'mails', NULL, false);
        $tabs[] = $this->__makeTab(__('Send to All', true), 'all', 'mails', NULL, false);
        if ($params['action'] == 'admin_index')
            $tabs[0]['active'] = true;
        if ($params['action'] == 'admin_all')
            $tabs[1]['active'] = true;
        return $tabs;
    }
    
}

?>
