<?php

class News extends AppModel {

    public $name = 'News';
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'summary' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'content' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

    function getNews() {
        $options['limit'] = 4;
        $options['order'] = 'News.created DESC';
        $data = $this->find('all', $options);
        return $data;
    }

    function getIndex() {
        $options['fields'] = array(
            'News.id',
            'News.title',
            'News.summary',
            'News.content'
        );
        return $options;
    }

    function getEdit() {
        $fields = array(
            'News.id',
            'News.title',
            'News.summary',
            'News.content'
        );
        return $fields;
    }

    function getAdd() {
        $fields = array(
            'News.title',
            'News.summary',
            'News.content'
        );
        return $fields;
    }

    function getSearch() {
        $fields = array(
            'News.id',
            'News.title',
            'News.summary' => array('type' => 'text'),
            'News.content' => array('type' => 'text')
        );
        return $fields;
    }

}

?>