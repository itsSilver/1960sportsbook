<?php

class Feed extends AppModel {

    public $name = 'Feed';

    function getActions() {
        $actions = parent::getActions();
        unset($actions[2]);
        $actions[] = array('name' => __('Update', true), 'action' => 'update', 'controller' => NULL);
        return $actions;
    }

    function getTabs($params) {
        $tabs = parent::getTabs($params);
        $tabs['feedsadmin_updateAll'] = $this->__makeTab(__('Update All', true), 'updateAll', 'feeds');
        return $tabs;
    }
    
    function getFeed($id) {
        $options['conditions'] = array('Feed.id' => $id);
        return $this->find('first', $options);
    }

    function updated($id, $date) {
        $options['conditions'] = array(
            'Feed.id' => $id
        );
        $data = $this->find('first', $options);
        $data['Feed']['last_update'] = $date;
        $this->save($data);
    }
    
    function getActiveFeeds() {
        $options['conditions'] = array(
            'active' => 1
        );
        $feeds = $this->find('all', $options); 
        return $feeds;
    }
    
    function getAdd() {
        return array(
            'Feed.name',
            'Feed.url',
            'Feed.timezone',
            'Feed.active'
        );
    }
    
    function getSearch() {
        return array(
            'Feed.name',
            'Feed.url',
            'Feed.timezone',
            'Feed.active'
        );
    }
    function getEdit() {
        return array(
            'Feed.name',
            'Feed.url',
            'Feed.timezone',
            'Feed.active'
        );
    }
    
}

?>
