<?php

class MtMenu extends AppModel {

    public $name = 'MtMenu';

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
        return __('Top menu', true);
    }
    function getPluralName() {
        return __('Top menu', true);
    }
    
    function getMenuItems() {
        $this->locale = Configure::read('Admin.defaultLanguage');
        $options['conditions'] = array(
            'MtMenu.active' => 1
        );
        $options['order'] = 'MtMenu.order ASC';
        $data = $this->find('all', $options);
        foreach ($data as &$menuItem) {
            foreach ($menuItem['translations'] as $translation) {
                if ($translation['locale'] == Configure::read('Config.language')) {
                    $menuItem['MtMenu']['title'] = $translation['content'];
                    
                }
            }
        }
        return $data;
    }

}

?>
