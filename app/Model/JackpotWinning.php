<?php
class JackpotWinning extends AppModel {
	
	public $belongsTo = array('User','BetPart','Ticket');
	
	/**
	 * Update lucky player
	 * log lucky guess to system
	 * @param array $betpart
	 */
	public function updateLucky($betpart){
		$data= array();
		$data['JackpotWinning']['ticket_id'] =$betpart['Ticket']['id'];
		$data['JackpotWinning']['bet_part_id'] =$betpart['TicketPart']['bet_part_id'];
		$data['JackpotWinning']['user_id'] = $betpart['Ticket']['user_id'];
		$data['JackpotWinning']['odds'] = $betpart['TicketPart']['odd'];
		$data['JackpotWinning']['tickettime'] = $betpart['Ticket']['date'];
		

		return $this->save($data);
	}
	
	
	public function getActions(){
		$act = parent::getActions();
		unset($act[2]);
		unset($act[1]);
		return $act;
	}
	
	public function getTabs($params){
		$tabs = parent::getTabs($params);
		unset($tabs['jackpot_winningsadmin_add']);
		unset($tabs['jackpot_winningsadmin_search']);
		return $tabs;
	}
	
	/**
	 * Generate cake php top perfomers query
	 * @warning this function, unset some references to tables
	 * @return array generated cake query options
	 */
	protected  function generateTopsQuery($userId = null, $limit = NULL){
	
		if ($userId != NULL)
			$options['conditions']['Deposit.user_id'] = $userId;
		if ($limit != NULL)
			$options['limit'] = $limit;
		
		$options['group'] = array('user_id');
		
		unset($this->belongsTo['BetPart']);
		unset($this->belongsTo['Ticket']);
		
		$options['recursive'] = 1;
		
		$options['fields'] = array('JackpotWinning.user_id','User.username', 'COUNT(JackpotWinning.user_id) as guesses');
		
		$options['order'] = array('COUNT(JackpotWinning.user_id) DESC');
		
		return $options;
	}
	
	/**
	 * Return top perfomers list
	 * @param int $limit limit
	 */
	public function getMonthTop($limit){
		$options = $this->generateTopsQuery(null,$limit);
		$options['conditions'] = array(
				'MONTH(JackpotWinning.tickettime) = MONTH(now())',
				'YEAR(JackpotWinning.tickettime)  = YEAR(NOW())'
				);
		
		$options['limit'] = $limit;
		
		return $this->find('all',$options);
	}
	
	public function getReport($from, $to, $userId = null, $limit = NULL) {
		
		
		$options = $this->generateTopsQuery($userId,$limit);
		
		$options['conditions'] = array(
				'JackpotWinning.tickettime BETWEEN ? AND ?' => array($from, $to)
		);
		
		$data = $this->find('all', $options);
		$data['header'] = array(
				'User ID',
				'UserName',
				'Guesses'
		);
		return $data;
	}
	
}