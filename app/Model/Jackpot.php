<?php

class Jackpot extends AppModel {

    public $name = 'Jackpot';
    /**
     * 
     * @var unknown_type
     */
    public $useTable = 'jackpot';
    
    /**
     * 
     */
    public function getSize(){
    	$sql = 'SELECT SUM(amount) AS pot FROM '.$this->useTable.' WHERE YEAR(datetime) = YEAR(NOW()) AND MONTH(datetime) = MONTH(NOW())';
    	return $this->query($sql);
    }
    
    
    /**
     * SELECT SUM(amount) FROM jacpot WHERE `datetime` BETWEEN [data1] AND [data2]
     * @param date $from
     * @param date $to
     * @param int $userId
     * @param int $limit
     * @return mixed
     */
    public function getReport($from, $to, $userId = null, $limit = NULL) {
    
   
    	$options['conditions'] = array(
    			'Jackpot.datetime BETWEEN ? AND ?' => array($from, $to)
    	);
    
    	$options['fields'] = array (
    			'SUM(Jackpot.amount) as result'
    	);
    	
    	$data = $this->find('all', $options);
    	$data['header'] = array(
    			'jackpot_size',
   
    	);
    	return $data;
    }
    

}

?>
