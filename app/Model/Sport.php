<?php

class Sport extends AppModel {

    public $name = 'Sport';
    public $actsAs = array('Containable');
    public $hasMany = array('League');

    function getActions() {
        $actions = array();
        $actions[] = array('name' => __('View', true), 'action' => 'view', 'controller' => NULL);
        $actions[] = array('name' => __('Edit', true), 'action' => 'edit', 'controller' => NULL);
        $actions[] = array('name' => __('Add League', true), 'action' => 'add', 'controller' => 'leagues');
        return $actions;
    }

    function getSports() {
        $this->contain('League');
        $options['conditions'] = array(
            'Sport.active' => 1,
            'Sport.feed_type' => Configure::read('Settings.feedType')
        );
        $options['order'] = 'Sport.name ASC';
        $this->contain();
        $sports = $this->find('all', $options);
        $list = array();
        foreach ($sports as &$sport) {
            $sport['League'] = $this->League->getActiveLeagues($sport['Sport']['id']);            
            $leagues = array();
            $count = 0;
            foreach ($sport['League'] as &$league) {
                if ($this->League->isActive($league['League'])) {
                    $count++;
                    $leagues[] = $league['League']; //add league
                }
            }
            if ($count > 0)
                $list[] = array('Sport' => $sport['Sport'], 'League' => $leagues);
        }
        return $list;
    }

    function getLeagues($id) {
        $options['conditions'] = array(
            'League.sport_id' => $id
        );
        return $this->League->find('all', $options);
    }

    function getSport($id) {
        $options['conditions'] = array(
            'Sport.id' => $id
        );
        return $this->find('first', $options);
    }

    function getIndex() {
        $options['fields'] = array(
            'Sport.id',
            'Sport.name',
            'Sport.active'
        );
        return $options;
    }

    function getEdit() {
        $fields = array(
            'Sport.id',
            'Sport.active'
        );
        return $fields;
    }
    
    function updateRisk($sports) {        
        $data = array();
        foreach ($sports['Sport'] as $key => $value) {
            $value['id'] = $key;
            $data[]['Sport'] = $value;
        }
        return $this->saveAll($data);
    }

}

?>
