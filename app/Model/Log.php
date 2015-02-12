<?php

class Log extends AppModel {

    public $useTable = 'logs';

    public function write($userId, $message) {
        $data['Log']['user_id'] = $userId;
        $data['Log']['message'] = $message;
        return $this->save($data);
    }

    public function getItemActions() {
        return array();
    }
    
}

?>
