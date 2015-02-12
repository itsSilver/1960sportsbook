<?php

class DepositMeta extends AppModel {

    public function saveDeposit($userId, $amount, $depositId, $meta) {
      $data['DepositMeta']['user_id'] = $userId;
      $data['DepositMeta']['amount'] = $amount;
      $data['DepositMeta']['deposit_id'] = $depositId;
      $data['DepositMeta']['meta'] = $meta;
      $this->save($data);
    }
    
    public function getDeposit($userId, $amount, $depositId){
      $f['conditions'] = array (
        'DepositMeta.deposit_id' => $depositId
      );
      $get = $this->find('first',$f);
      $this->delete($get['DepositMeta']['id']);
      if (isset($get['DepositMeta'])){
        return $get['DepositMeta']['meta'];
      } else {
        return NULL;
      }
    }
}

