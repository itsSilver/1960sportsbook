<?php

class BottomImage extends AppModel {

    public $name = 'BottomImage';
    public $validate = array(
        'name' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'url' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'img' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

     function getName() {
        return __('Bottom Image', true);
    }

    function getPluralName() {
        return __('Bottom Images', true);
    }
    
    function getImages() {
        $options['conditions'] = array(
            'BottomImage.active' => 1
        );
        $options['order'] = 'BottomImage.order ASC';
        $data = $this->find('all', $options);

        return $data;
    }

    function getAdd() {
        $fields = array(
            'BottomImage.url',
            'BottomImage.name',
            'BottomImage.image' => array('type' => 'file'),
            'BottomImage.active'
        );
        return $fields;
    }
    
    function getEdit() {
        $fields = array(
            'BottomImage.url',
            'BottomImage.name',
            'BottomImage.image' => array('type' => 'file'),
            'BottomImage.active'
        );
        return $fields;
    }

}

?>
