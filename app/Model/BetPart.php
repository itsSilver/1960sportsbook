<?php

class BetPart extends AppModel {

    public $name = 'BetPart';
    public $belongsTo = array('Bet');
    public $actsAs = array('Containable');

    private $__insertQuery;
    private $__updateQuery;
    private $__findQuery;
    private $__connection;
    
   
    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->__prepareQueries();
    }
    
    public function getBetParts($betId) {
        $options['conditions'] = array(
            'BetPart.bet_id' => $betId,
            'BetPart.odd >' => 1
        );
        $options['recursive'] = -1;
        $options['order'] = 'BetPart.odd ASC';
        $data = $this->find('all', $options);
        return $data;
    }

    function getBetsIds($betPartsIds) {
        $options['conditions'] = array(
            'BetPart.id' => $betPartsIds
        );
        $options['fields'] = array(
            'BetPart.id',
            'BetPart.bet_id'
        );
        $options['group'] = 'BetPart.bet_id';
        return $this->find('list', $options);
    }

    function getBetPartsIds($betId) {
        $options['conditions'] = array(
            'BetPart.bet_id' => $betId
        );
        $options['fields'] = array(
            'BetPart.id',
            'BetPart.id'
        );
        return $this->find('list', $options);
    }

    
   
    
    //a
    
    public function insertBetPart(&$betPart) {
        if ($this->__exists($betPart['id'])) {            
            $this->__update($betPart);
        } else {            
            $this->__insert($betPart);
        }
    }
    
    private function __insert($betPart) {        
        $this->__insertQuery->execute(array($betPart['id'], $betPart['name'], $betPart['bet_id'], $betPart['odd']));
    }

    private function __update($betPart) {        
        $this->__updateQuery->execute(array($betPart['odd'], $betPart['id']));
    }

    private function __exists($id) {
        $this->__findQuery->execute(array($id));
        if ($this->__findQuery->fetchColumn() > 0) {
            $this->__findQuery->closeCursor();
            return true;
        } else {
            $this->__findQuery->closeCursor();
            return false;
        }
    }
    
     private function __prepareQueries() {
        $prefix = ConnectionManager::$config->default['prefix'];   
        $dataSource = $this->getDataSource();
        $this->__connection = $dataSource->getConnection();   
        
        $query = "
        INSERT INTO {$prefix}bet_parts
            (`id`, `name`, `bet_id`, `odd`)
        VALUES
            (?, ?, ?, ?)";
        $this->__insertQuery = $this->__connection->prepare($query);  
        
        $query = "
        UPDATE {$prefix}bet_parts            
        SET
            `odd` = ?
        WHERE
            `id` = ?";
        $this->__updateQuery = $this->__connection->prepare($query);  
        
        $query = "
        SELECT COUNT(*)
        FROM {$prefix}bet_parts
        WHERE
            `id` = ?
        LIMIT 1";
        $this->__findQuery = $this->__connection->prepare($query);  
    }
    
}

?>