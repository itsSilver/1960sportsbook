<?php
class TicketPart extends AppModel {

    public $name = 'TicketPart';
    public $belongsTo = array('Ticket');

    public $actsAs = array('Containable');
    
    function setStatus($id, $status) {
        $options['conditions'] = array(
            'TicketPart.id' => $id
        );
        $options['recursive'] = -1;
        $data = $this->find('first', $options);
        $data['TicketPart']['status'] = $status;
        $this->save($data);
        $this->Ticket->update($data['TicketPart']['ticket_id']);        
    }
    
    function getPendingBetParts() {
        $options['conditions'] = array(
            'TicketPart.status' => 0
        );
        $options['fields'] = array(
            'TicketPart.id',
            'TicketPart.bet_part_id'
        );
        $options['group'] = 'TicketPart.bet_part_id';
        return $this->find('list', $options);
    }
    
}
?>
