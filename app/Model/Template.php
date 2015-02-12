<?php

class Template extends AppModel {

    public $name = 'Template';
    public $validate = array(
        'subject' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'content' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

    function getActions() {
        $actions = parent::getActions();
        unset($actions[2]);
        return $actions;
    }

    function getIndex() {
        $options['fields'] = array(
            'Template.id',
            'Template.subject',
            'Template.content'
        );
        return $options;
    }

    function getEdit() {
        $fields = array(
            'Template.id',
            'Template.subject',
            'Template.content'
        );
        return $fields;
    }

    function getView($id) {
        $options['fields'] = array(
            'Template.subject',
            'Template.content'
        );
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Template.id' => $id
        );

        $data = $this->find('first', $options);
        return $data;
    }

    function getTabs($params) {
        $tabs = parent::getTabs($params);
        unset($tabs['templatesadmin_add']);
        return $tabs;
    }

}

?>
