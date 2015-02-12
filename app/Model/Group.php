<?php

class Group extends AppModel {

    public $actsAs = array('Containable', 'Acl' => array('type' => 'requester'));
    public $hasMany = 'User'; 
    
    function parentNode() {
        return null;
    }
    
    function getAdd() {
        $fields = array(
            'Group.name'
        );
        return $fields;
    }
    
    function getGroups() {
        //$this->Contain();
        $data = $this->find('all');
        foreach ($data as &$group) {
            $groups[$group['Group']['id']] = $group['Group']['name'];
        }
        return $groups;
    }
    
    function getAdminGroups() {
        $groups = $this->getGroups();
        unset($groups['1']);
        return $groups;
    }

}

?>
