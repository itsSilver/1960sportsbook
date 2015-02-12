<?php

class MarketingController extends AppController {

    public $name = 'Marketing';
    public $uses = array('BonusCode');

    function admin_bonusCodes() {
        $this->admin_index();
    }

}

?>
