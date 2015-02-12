<?php

class Plugin extends AppModel {

    public $name = 'Plugin';

    function getPlugins($position) {
        $options['conditions'] = array(
            'Plugin.position' => $position,
            'Plugin.active' => 1
        );
        return $this->find('all', $options);
    }

    function getEdit() {

        $fields = array(
            'Plugin.name',
            'Plugin.position' => array('type' => 'select', 'options' => array(1 => 'Left', 2 => 'Right')),
            'Plugin.content' => array('class' => 'mceNoEditor'),
            'Plugin.active'
        );
        return $fields;
    }

}

?>
