<?php
App::uses('AppController', 'Controller');
/**
 * Jackpot Controller
 *
 */
class JackpotController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;
	public $use = array('Jackpot','JackpotWinning');

	public function MonthJackpoters(){
	    $data = $this->JackpotWinning->getMonthTop(50);
	    $this->set(compact('data'));
	}
}
