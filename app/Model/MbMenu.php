<?php

class MbMenu extends AppModel {

    public $name = 'MbMenu';
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
        return __('Footer menu', true);
    }

    function getPluralName() {
        return __('Footer menu', true);
    }

    function getMenuItems() {
        $this->locale = Configure::read('Admin.defaultLanguage');
        $options['conditions'] = array(
            'MbMenu.active' => 1
        );
        $options['order'] = 'MbMenu.order ASC';
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

}

?>
