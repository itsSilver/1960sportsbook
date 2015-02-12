<?php

class Menu extends AppModel {

    public $name = 'Menu'; 
    
    function getMenuItems($group = NULL){
        if (isset($gruop)) {
            $options['conditions'] = array(
                'Menu.group' => $group
            );            
            return $this->find('all', $options);
        }
        return NULL;
    }
    
}

?>
