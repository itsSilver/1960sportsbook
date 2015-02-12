<?php

class BonusCodesUser extends AppModel {

    public $name = 'BonusCodesUser';      
    public $belongsTo = array('BonusCode', 'User');
 
    function findBonusCode($bonusCodeId, $userId) {
        $options['conditions'] = array(
            'BonusCodesUser.bonus_code_id' => $bonusCodeId,
            'BonusCodesUser.user_id' => $userId            
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);        
        return $bonusCode;
    }
    
    function addCode($bonusCodeId, $userId) {        
        $data['BonusCodesUser']['bonus_code_id'] = $bonusCodeId;
        $data['BonusCodesUser']['user_id'] = $userId;
        $this->save($data);
    }
    
}

?>