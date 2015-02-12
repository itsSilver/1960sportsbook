<?php

class Page extends AppModel {

    public $name = 'Page';
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'url' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'content' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );
    public $actsAs = array(
        'Translate' => array(
            'title' => 'translations',
            'content',
            'keywords',
            'description'
        )
    );

    function getIndex() {
        $fields = array(
            'Page.id',
            'Page.title',
            'Page.url',
            'Page.content'
        );
        //return $fields;
    }

    function getUrls() {
        $options['fields'] = array(
            'Page.url'
        );
        $data = $this->find('all', $options);
        $urls = array();
        foreach ($data as $page)
            $urls[$page['Page']['url']] = $page['Page']['url'];
        return $urls;
    }

}

?>
