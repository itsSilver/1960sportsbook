<?php

class MyI18n extends AppModel {

    public $name = 'MyI18n';        
    public $useTable = 'i18n';
    
    function deleteAll($conditions, $cascade = true, $callbacks = false) {
        $conditions = array(
            'MyI18n.locale' => $conditions
        );
        parent::deleteAll($conditions, $cascade, $callbacks);
    }

}

?>
