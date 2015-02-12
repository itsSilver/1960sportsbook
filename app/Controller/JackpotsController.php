<?php

class JackpotsController extends AppController {
    
    public $name = 'Jackpots';
    public $uses = array('User', 'BonusCode', 'BonusCodesUser','Jackpot','JackpotWinning');
    
    function admin_index() {
        return 'asd';
    }
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getSize','MonthJackpoters'));
    }

    function getSize() {
    	$size = $this->Jackpot->getSize();
        return $size;
    }

    public function MonthJackpoters($limit = 20){
	    $data = $this->JackpotWinning->getMonthTop($limit);
	    $this->set(compact('data'));
    }   
    
    /**
    * Going back
    **/
    public function index(){
    return $this->redirect($this->referer());
    }
}

?>
