<?php

class Currency extends appModel {
    public $name = 'Currency';
    
    function getCode($id) {
        $options['conditions'] = array(
            'Currency.id' => $id
        );
        $currency = $this->find('first', $options);
        return $currency['Currency']['code'];
    }
    
    function getList() {        
        $list = $this->find('list');
        return $list;
    }
    
    function getCodesList() {
        $options['fields'] = array(
            'Currency.id',
            'Currency.code'
        );
        $list = $this->find('list', $options);
        return $list;
    }
    
}

?>
