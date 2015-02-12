<?php

class MhMenu extends AppModel {

    public $name = 'MhMenu';
    public $actsAs = array(
        'Translate' => array(
            'title' => 'translations'
        )
    );
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'url' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );
    function getName() {
        return __('Header menu', true);
    }

    function getPluralName() {
        return __('Header menu', true);
    }

	function getMenuItems() {
        $this->locale = Configure::read('Admin.defaultLanguage');
        $options['conditions'] = array(
            'MhMenu.active' => 1,
			'MhMenu.type' => 0
        );
        $options['order'] = 'MhMenu.order ASC';
        $data = $this->find('all', $options);
        foreach ($data as &$menuItem) {
            foreach ($menuItem['translations'] as $translation) {
                if ($translation['locale'] == Configure::read('Config.language')) {
                    $menuItem['MhMenu']['title'] = $translation['content'];
                    
                }
            }
        }
        return $data;
    }

	function getmenuitemsLottery() {
        $this->locale = Configure::read('Admin.defaultLanguage');
        $options['conditions'] = array(
            'MhMenu.active' => 1,
			'MhMenu.type' => 1
        );
        $options['order'] = 'MhMenu.order ASC';
        $data = $this->find('all', $options);
        foreach ($data as &$menuItem) {
            foreach ($menuItem['translations'] as $translation) {
                if ($translation['locale'] == Configure::read('Config.language')) {
                    $menuItem['MhMenu']['title'] = $translation['content'];
                    
                }
            }
        }
        return $data;
    }

	function getAdd() {
		$option = array('0' => 'Sports','1' =>'Lottery');

        $fields = array(
            'MhMenu.title',
            'MhMenu.url',            
			'MhMenu.type' => array('label' => 'Slider Type', 'type' => 'select', 'options' => array('0'=>'Sports Slider','1'=>'Lottery Slider')),
			'MhMenu.active'
        );
        return $fields;
    }

    function getEdit() {
        $fields = array(
            'MhMenu.title',
            'MhMenu.url',            
			'MhMenu.type' => array('label' => 'Slider Type', 'type' => 'select', 'options' => array('0'=>'Sports Slider','1'=>'Lottery Slider')),
			'MhMenu.active'
        );
        return $fields;
    }

}

?>
