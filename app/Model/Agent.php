<?php
/******************************************
* @Created on Sept 18, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class Agent extends AppModel {

    public $name      = 'Agent';
	public $belongsTo = array(
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
		'Receiver' => array(
            'className' => 'User',
            'foreignKey' => 'recevier_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
}

?>
