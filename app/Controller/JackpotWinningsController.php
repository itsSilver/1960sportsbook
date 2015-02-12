<?php
class JackpotWinningsController extends AppController {

	/**
	 * Scaffold
 	 *
 	* @var mixed
 	*/
	public $scaffold;
	
	public $use = array('JackpotWinning');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('getMonthTop'));
	}
	
	/**
	 * Get top jackpoters of current month
	 * @param $howmany int limit
	 */
	public function getMonthTop($howmany = 50){
		$howmany = (int)$howmany;
		$ret = $this->JackpotWinning->getMonthTop($howmany);
		return $ret;
	}

}