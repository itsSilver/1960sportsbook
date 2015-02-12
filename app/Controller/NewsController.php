<?php

class NewsController extends AppController {
    public $name = 'News';
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('view'));
    }
    
    function view($id) {
        $new = $this->News->getItem($id);        
        $this->set('new', $new);
    }
    
}

?>
