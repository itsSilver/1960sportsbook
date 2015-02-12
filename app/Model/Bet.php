<?php

class Bet extends AppModel {

    public $name      = 'Bet';
    public $actsAs    = array('Containable');
    public $belongsTo = array('Event');
    public $hasMany   = array('BetPart');
    private $__insertQuery;
    private $__findQuery;
    private $__connection;

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->__prepareQueries();
    }

    public function getBets($eventId, $betType = null) {
        $options['conditions'] = array(
            'Bet.event_id' => $eventId
        );
        if (isset($betType)) {
            $betType = str_replace('_', '/', $betType);
            $betType = str_replace('-', ' ', $betType);
            $options['conditions']['Bet.type'] = array($betType);
        }
        $options['recursive'] = -1;
        $data = $this->find('all', $options);
        return $data;
    }

    public function getBetTypes($leagueId = 0, $sportId = null) {
        $options['recursive'] = -1;
        $options['fields'] = array(
            'Bet.type'
        );
        $options['joins'] = array(
            array(
                'table' => 'events',
                'alias' => 'Event',
                'type' => 'INNER',
                'conditions' => array(
                    'Bet.event_id = Event.id'
                )
            ),
            array(
                'table' => 'leagues',
                'alias' => 'League',
                'type' => 'INNER',
                'conditions' => array(
                    'Event.league_id = League.id'
                )
            )
        );
        //join sports table if required
        if ($sportId == null) {
            $options['conditions'] = array(
                'League.id' => $leagueId
            );
        } else {
            $options['joins'][] = array(
                'table' => 'sports',
                'alias' => 'Sport',
                'type' => 'INNER',
                'conditions' => array(
                    'League.sport_id = Sport.id'
                )
            );
            $options['conditions'] = array(
                'Sport.id' => $sportId
            );
        }
        $options['group'] = 'Bet.type';
        $data = $this->find('all', $options);

        $betTypes['All'] = __('All');
        foreach ($data as $row) {
            $type = str_replace('/', '_', $row['Bet']['type']);
            $type = str_replace(' ', '-', $type);
            $betTypes[$type] = $row['Bet']['type'];
        }

        return $betTypes;
    }

    function getBetParts($id) {
        $options['conditions'] = array(
            'Bet.id' => $id
        );
        $this->contain('BetPart');
        $bet = $this->find('first', $options);
        return $bet;
    }

    function setPick($betPartId) {
        $betPart = $this->BetPart->getItem($betPartId);
        $options['conditions'] = array(
            'Bet.id' => $betPart['BetPart']['bet_id']
        );
        $data = $this->find('first', $options);
        $data['Bet']['pick'] = $betPartId;
        $this->save($data);
    }

    function hasTickets($id) {
        $options['conditions'] = array(
            'BetPart.bet_id' => $id
        );
        $betParts = $this->BetPart->getBetPartsIds($id);
        App::import('model', 'TicketPart');
        $TicketPart = new TicketPart();
        $options['conditions'] = array(
            'TicketPart.bet_part_id' => $betParts
        );
        $ticketPart = $TicketPart->find('first', $options);
        if (empty($ticketPart))
            return false;
        return true;
    }

    function setResult($id, $result = '') {
        $options['conditions'] = array(
            'Bet.id' => $id
        );
        $data = $this->find('first', $options);
        $data['Bet']['result'] = $result;
        $this->save($data);
    }

    function getAdd() {
        $fields = array(
            'Bet.name',
            'Bet.type'
        );
        return $fields;
    }

    function getItem($id, $recursive = 1) {
        $options['conditions'] = array(
            'Bet.id' => $id
        );
        $options['recursive'] = $recursive;
        $data = $this->find('first', $options);
        return $data;
    }

    function getEventsIds($betsIds) {
        $options['conditions'] = array(
            'Bet.id' => $betsIds
        );
        $options['fields'] = array(
            'Bet.id',
            'Bet.event_id'
        );
        $options['group'] = 'Bet.event_id';
        return $this->find('list', $options);
    }

    public function insertBet(&$bet) {
        if (!$this->__exists($bet['id'])) {
            $this->__insert($bet);
        }
        //no need for update
    }

    private function __insert($bet) {
        $this->__insertQuery->execute(array($bet['id'], $bet['name'], $bet['event_id'], $bet['type']));
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
        INSERT INTO {$prefix}bets
            (`id`, `name`, `event_id`, `type`)
        VALUES
            (?, ?, ?, ?)";
        $this->__insertQuery = $this->__connection->prepare($query);

        $query = "
        SELECT COUNT(*)
        FROM {$prefix}bets
        WHERE
            `id` = ?
        LIMIT 1";
        $this->__findQuery = $this->__connection->prepare($query);
    }

}

?>