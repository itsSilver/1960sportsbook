<div id="tickets" class="view">
    <h3><?php echo __('Ticket'); ?></h3>

    <?php if (isset($ticket)): ?>

        <table class="default-table">
            <tr>
                <th><?php echo __('Event'); ?></th>
                <th><?php echo __('Result'); ?></th>
                <th><?php echo __('Pick'); ?></th>
                <th><?php echo __('Odd'); ?></th>
                <th><?php echo __('Correct'); ?></th>
                <th><?php echo __('Status'); ?></th>
            </tr>
            <?php foreach ($ticket['TicketPart'] as $ticketPart): ?>        
                <tr>
                    <td><?php echo $ticketPart['Bet']['name']; ?></td>
                    <td><?php echo $ticketPart['Event']['result']; ?></td>
                    <td><?php echo $ticketPart['BetPart']['name']; ?></td>
                    <td><?php echo $this->Beth->convertOdd($ticketPart['odd']); ?></td>
                    <td><?php echo $ticketPart['Bet']['outcome']; ?></td>
                    <td><?php echo $this->Beth->getStatus($ticketPart['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="centered">
            <?php echo $this->Html->link(__('Back'), array('action' => 'index'), array('class' => 'button')); ?>
            <?php if ((Configure::read('Settings.printing')) && ($ticket['Ticket']['printed'] == 0)): ?>                                                                
                <?php echo $this->Html->link(__('Print', true), array('action' => 'printTicket', $ticket['Ticket']['id']), array('target' => '_blank', 'class' => 'button')) ?>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <p class="warning">
            <?php echo __('Invalid ticket Id'); ?>
        </p>
        <?php echo $this->Html->link(__('Back'), array('action' => 'index'), array('class' => 'button')); ?>
    <?php endif; ?>

</div>