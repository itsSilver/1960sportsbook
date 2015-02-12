<?php

class Staff extends AppModel {

    public $name = 'Staff';
    public $actsAs = array('Containable');
    public $useTable = 'users';
    public $belongsTo = array('Group');
    public $validate = array(
        'username' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'allowEmpty' => false,
                'message' => 'Alphabets and numbers only'
            ),
            'between' => array(
                'rule' => array('between', 5, 15),
                'message' => 'Between 5 to 15 characters'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This username has already been taken.'
            )
        ),
        'password_raw' => array(
            'rule' => array('minLength', '5'),
            'message' => 'Mimimum 5 characters long'
        )
    );

    function getIndex() {
        $options['fields'] = array(
            'Staff.id',
            'Staff.username',
            'Staff.group_id'
        );
        $options['conditions'] = array(
            'Staff.group_id <>' => 1
        );
        return $options;
    }

	//ADDED BY PRAVEEN SINGH ON 23-09-2013
	function getView($id) {
        $options['fields'] = array(
            'Staff.id',
            'Staff.username',
            'Staff.email',
            'Staff.balance',
            'Staff.first_name',
            'Staff.last_name',
            'Staff.address1',
            'Staff.address2',
            'Staff.zip_code',
            'Staff.city',
            'Staff.country',
            'Staff.date_of_birth',
            'Staff.mobile_number',
            'Staff.last_visit',
            'Staff.bank_name',
            'Staff.account_number'
        );
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Staff.id' => $id,
            'Staff.group_id <>' => 1
        );

        $data = $this->find('first', $options);
        return $data;
    }

    /*
	function getView($id) {
        $options['fields'] = array(
            'Staff.id',
            'Staff.username',
            'Staff.group_id'
        );
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Staff.id' => $id,
            'Staff.group_id <>' => 1
        );

        $data = $this->find('first', $options);
        return $data;
    }
	*/

	//ADDED BY PRAVEEN SINGH ON 23-09-2013
    function getEdit() {
        $group_id_field = array('type' => 'select', 'options' => $this->Group->getAdminGroups());
        $fields = array(
            'Staff.id',
            'Staff.username',
            'Staff.password',
			'Staff.email',
            'Staff.first_name',
            'Staff.last_name',
            'Staff.address1',
            'Staff.address2',
            'Staff.zip_code',
            'Staff.city',
            'Staff.country',
            'Staff.date_of_birth',
            'Staff.mobile_number',
            'Staff.group_id' => $group_id_field
        );
        return $fields;
    }

    function getSearch() {
        $group_id_field = array('type' => 'select', 'options' => $this->Group->getAdminGroups());
        
		$fields = array(
            'Staff.username',
            'Staff.email',
            'Staff.balance',
            'Staff.first_name',
            'Staff.last_name',
            'Staff.address1',
            'Staff.address2',
            'Staff.zip_code',
            'Staff.city',
            'Staff.country',
            'Staff.date_of_birth',
            'Staff.mobile_number',
			'Staff.group_id' => $group_id_field
        );

        return $fields;
    }

    function getAdd() {
        $group_id_field = array('type' => 'select', 'options' => $this->Group->getAdminGroups());
        $fields = array(
            'Staff.username',
            'Staff.password_raw' => array('type' => 'password', 'label' => __('Password')),
            'Staff.email',
            'Staff.first_name',
            'Staff.last_name',
            'Staff.address1',
            'Staff.address2',
            'Staff.zip_code',
            'Staff.city',
            'Staff.country',
            'Staff.date_of_birth',
            'Staff.mobile_number',
			'Staff.group_id' => $group_id_field
        );

        return $fields;
    }
    
     function getActions() {
        $actions = array();
        $actions[] = array('name' => __('Edit', true), 'action' => 'edit', 'controller' => NULL);
        $actions[] = array('name' => __('Delete', true), 'action' => 'delete', 'controller' => NULL);
        $actions[] = array('name' => __('Add balance', true), 'action' => 'addBalance', 'controller' => 'users');
        $actions[] = array('name' => __('Depost bonus history',true),'action' => 'deposit_bonus_history', 'controller' => NULL);
        $actions[] = array('name' => __('Report', true), 'action' => 'userReport', 'controller' => 'reports');
        return $actions;
    }

}

?>
