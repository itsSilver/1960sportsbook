<?php

class APIController extends AppController {

    public $name = 'API';    
    
    public $components = array( 'RequestHandler', 'Auth' => array(    'authorize' => 'controller',    'allowedActions' => array('index','listGroups','ping', 'login', 'logout')  ));
    public $uses = array('User');

    
 /*
 * API/index 
 */    
    function index() {

    }
/*
 * API/listGroups 
 */     function listGroups() {
        $groups = $this->User->Group->getAdminGroups();
        $this->set(compact('groups'));
    }   
/*
 * API/Ping 
 */
     function ping() {

        $ping = 'pong';
        $this->set(compact('ping'));
    }   
/*
 * API/login
 */    
    function login() {
        
        $status = $this->Auth->login();
        $this->set(compact('status'));
    }
/*
 * API/logout 
 */    
    function logout() {
        
        $this->Auth->logout();
        //$this->set('OK');
    }
}

?>