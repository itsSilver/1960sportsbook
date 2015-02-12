<?php if (isset($ticket)): ?>
    <?php echo $this->Session->flash(); ?>
    <h4><?php __('Ticket number #%d', $ticket['Ticket']['id']); ?></h4>
    <table class="items" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo __('ID'); ?></th>
            <th><?php echo __('Date'); ?></th>
            <th><?php echo __('User'); ?></th>
            <th><?php echo __('Type'); ?></th>
            <th><?php echo __('Odd'); ?></th>
            <th><?php echo __('Stake'); ?></th>
            <th><?php echo __('Winning'); ?></th>
            <th><?php echo __('Status'); ?></th>
            <th><?php echo __('Actions'); ?></th>
        </tr>       
        <tr>
            <td><?php echo $ticket['Ticket']['id']; ?></td>
            <td><?php echo $ticket['Ticket']['date']; ?></td>
            <td><?php echo $this->Html->link($ticket['User']['username'], array('controller' => 'users', 'action' => 'view', $ticket['User']['id'])); ?></td>
            <td><?php echo $ticket['Ticket']['type']; ?></td>
            <td><?php echo $this->Beth->convertOdd($ticket['Ticket']['odd']); ?></td>
            <td><?php echo $ticket['Ticket']['amount']; ?></td>
            <td><?php echo $ticket['Ticket']['return']; ?></td>
            <td><?php echo $this->Beth->getStatus($ticket['Ticket']['status']); ?></td>
            <td>
                <?php echo $this->Html->link(__('Print', true), array('action' => 'printTicket', $ticket['Ticket']['id']), array('target' => '_blank')); ?>

		<?php
		$this->groupid = $this->Session->read('Auth.User.group_id');
		if(isset($this->groupid) && ($this->groupid !='8')) {?>
			|
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $ticket['Ticket']['id']), NULL, __('Ticket will be deleted. Are you sure you want to delete?')); ?>
			<?php if ($ticket['Ticket']['status'] != -2){ ?>
			|
			<?php echo $this->Html->link(__('Cancel', true), array('action' => 'cancel', $ticket['Ticket']['id']), NULL, __('Ticket will be canceled. Are you sure you want to cancel?')); ?>
			<?php } ?>
		 <?php } ?>
                
            </td> 
        </tr>        
    </table>

    <h4><?php echo __('Ticket details'); ?></h4>
    <table class="items" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo __('Date'); ?></th>
            <th><?php echo __('Event'); ?></th>
            <th><?php echo __('Result'); ?></th>
            <th><?php echo __('Pick'); ?></th>
            <th><?php echo __('Odd'); ?></th>
            <th><?php echo __('Correct'); ?></th>
            <th><?php echo __('Status'); ?></th>            
        </tr>
        <?php foreach ($ticket['TicketPart'] as $ticketPart): ?>        
            <tr>
                <td><?php echo $ticketPart['Event']['date']; ?></td>
                <td><?php echo $ticketPart['Bet']['name']; ?></td>
                <td>
                    <?php
                    if (!empty($ticketPart['Event']['result']))
                        echo $ticketPart['Event']['result'];
                    else
                        echo $this->Html->link(__('Add result', true), array('controller' => 'results', 'action' => 'event', $ticketPart['Event']['id']));
                    ?>
                </td>
                <td><?php echo $ticketPart['BetPart']['name']; ?></td>
                <td><?php echo $this->Beth->convertOdd($ticketPart['odd']); ?></td>
                <td><?php echo $ticketPart['Bet']['outcome']; ?></td>
                <td><?php echo $this->Beth->getStatus($ticketPart['status']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>    
<?php else: ?>



<?php endif; ?>