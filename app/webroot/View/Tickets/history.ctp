<div id="tickets" class="history">
    <h3><?php echo __('Tickets'); ?></h3>
    <?php if (!empty($tickets)): ?>
        <h4><?php echo __('Please see your betting history below:'); ?></h4>
        <div id="tickets">
            <table class="default-table">
                <th><?php echo $this->Paginator->sort('Ticket.date', __('Date')); ?></th>
                <th><?php echo $this->Paginator->sort('Ticket.odd', __('Odd')); ?></th>
                <th><?php echo $this->Paginator->sort('Ticket.amount', __('Amount')); ?></th>
                <th><?php echo $this->Paginator->sort('Ticket.return', __('Winning')); ?></th>
                <th><?php echo $this->Paginator->sort('Ticket.status', __('Status')); ?></th>            
                <th></th>
                <?php foreach ($tickets as $ticket) { ?>
                    <?php $ticket = $ticket['Ticket']; ?>
                    <tr>
                        <td>
                            <?php echo $this->Beth->convertTime($ticket['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->Beth->convertOdd($ticket['odd']); ?>
                        </td>
                        <td>
                            <?php echo $ticket['amount']; ?>
                        </td>
                        <td>
                            <?php
                            if ($ticket['type'] != 3)
                                echo $ticket['return'];
                            else
                                echo __('JACKPOT');
                            ?>
                        </td>
                        <td>
                            <?php echo $this->Beth->getStatus($ticket['status']); ?>
                        </td>
                        <td>
                            <?php echo $this->Html->link(__('View', true), array('action' => 'view', 'ticketId' => $ticket['id'])) ?>
                            <?php if ((Configure::read('Settings.printing')) && ($ticket['printed'] == 0)): ?>                                                                
                                |
                                <?php echo $this->Html->link(__('Print', true), array('action' => 'printTicket', $ticket['id']), array('target' => '_blank')) ?>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php } ?>
            </table>
        </div>
        <?php echo $this->element('paginator'); ?>          
    <?php else: ?>
        <h4><?php echo __('Your tickets list is empty at this moment.'); ?></h4>
    <?php endif; ?>
   <div class="lefted">
        <?php echo $this->Html->link(__('Pending tickets', true), array('controller' => 'tickets', 'action' => 'index'), array('class' => 'button-blue')); ?>
    </div>
</div>
