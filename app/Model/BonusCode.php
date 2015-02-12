<?php

class BonusCode extends AppModel {

    public $name = 'BonusCode';      
    public $hasMany = array('BonusCodesUser');

    function findBonusCode($code) {
        $options['conditions'] = array(
            'BonusCode.code' => $code,
            'BonusCode.times >' => 0,
            'BonusCode.expires >' => $this->getSqlDate()
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);
        return $bonusCode;
    }
    
    function useCode($id) {
        $options['conditions'] = array(
            'BonusCode.id' => $id
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);
        $bonusCode['BonusCode']['times'] = $bonusCode['BonusCode']['times'] - 1;
        $this->save($bonusCode);
    }
    
    
}

?>