<?php
class DepositCredit extends AppModel {

    public $name = 'DepositCredit';
	public $useTable = 'credits_transfered';

	 public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
		'Agent' => array(
            'className' => 'User',
            'foreignKey' => 'agent_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) 
    );


}

?>