<?php

class Slide extends AppModel {

    public $name = 'Slide';
    public $actsAs = array(
        'Translate' => array(
            'title' => 'translations',
            'description'
        )
    );
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'description' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'image' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

    function getSlides($layout='') {
        $this->locale = Configure::read('Admin.defaultLanguage');
        $options['conditions'] = array(
            'Slide.active' => 1
        );
		if ($layout == 'default') {
			$options['conditions'] = array('Slide.type' => 0);
		} else {
			$options['conditions'] = array('Slide.type' => 1);
		}
        $options['order'] = 'Slide.order ASC';
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
            'Slide.title',
            'Slide.description',
            'Slide.url',
            'Slide.image' => array('type' => 'file'),
			'Slide.type' => array('label' => 'Slider Type', 'type' => 'select', 'options' => array('0'=>'Sports Slider','1'=>'Lottery Slider')),
			'Slide.active'
        );
        return $fields;
    }

    function getEdit() {
        $fields = array(
            'Slide.title',
            'Slide.description',
            'Slide.url',
            'Slide.image' => array('type' => 'file'),            
			'Slide.type' => array('label' => 'Slider Type', 'type' => 'select', 'options' => array('0'=>'Sports Slider','1'=>'Lottery Slider')),
			'Slide.active'
        );
        return $fields;
    }

}

?>
