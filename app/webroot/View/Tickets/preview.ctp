<div id="tickets" class="preview">
    <h3><?php echo __('Ticket Preview'); ?></h3>

    <?php echo $this->Session->flash(); ?>

    <?php if (isset($tickets)): ?>

        <?php foreach ($tickets as $bets): ?>
            <div class="ticket-print">
                <h1><?php echo Configure::read('Settings.websiteName'); ?></h1>
                <h2><?php __('Ticket'); ?></h2>

                <table class="bets">
                    <tr>
                        <th><?php echo __('Event ID / Date'); ?></th>
                        <th><?php echo __('Event'); ?></th>
                        <th><?php echo __('Pick'); ?></th>
                        <th><?php echo __('Odd'); ?></th>
                    </tr>
                    <?php foreach ($bets['Bets'] as $ticketPart): ?>
                        <tr>
                            <td>
                                <?php echo __('ID:'); ?> <?php echo $ticketPart['Bet']['id']; ?>
                                <br />
                                <?php echo $ticketPart['Bet']['date']; ?>
                            </td>
                            <td><?php echo $ticketPart['Bet']['name']; ?></td>
                            <td><?php echo $ticketPart['BetPart']['name']; ?></td>
                            <td class="no-padding"><?php echo $ticketPart['BetPart']['odd']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <table class="odds">
                    <tr>
                        <td></td>
                        <td class="align-right"><?php echo __('Total:'); ?> <?php echo $this->Beth->convertOdd($bets['Ticket']['odd']); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="align-right"><?php echo __('Amount:'); ?> <?php echo $bets['Ticket']['stake']; ?> <?php echo $currency; ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="align-right">
                            <?php echo __('Return:'); ?> 
                            <?php
                            if ($type != 3) {
                                echo $this->Beth->convertCurrency($bets['Ticket']['winning']) . ' ' . $currency;
                            } else {                                
                                echo __('JACKPOT');
                            }
                            ?>
                        </td>
                        <tr>
                        <td></td>
                        <td class="align-right">
                            <?php echo __('Jacpot:'); ?> 
                            <?php
                            if ($type != 3) {
                                echo $this->Beth->convertCurrency($bets['Ticket']['stake']*(float)Configure::read('Settings.jackpotPercent')) . ' ' . $currency;
                            } else {                                
                                echo __('JACKPOT');
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>

        <div class="centered">     
	
            <?php echo $this->MyHtml->spanLink(__('Delete Ticket', true), array('action' => 'delete'), array('class' => 'button-blue'), __('Please confirm ticket cancellation', true)); ?>
	    
	    <?php $this->groupid = $this->Session->read('Auth.User.group_id');?>
	    <?php if(isset($this->groupid) && $this->groupid=='8'){?>
	       
	       <?php echo $this->MyHtml->spanLink(__('Submit Ticket', true), array('action' => 'agent_place', 1), array('class' => 'button-blue')); ?>
	     
	    <?php } else { ?>

               <?php echo $this->MyHtml->spanLink(__('Submit Ticket', true), array('action' => 'place', 1), array('class' => 'button-blue')); ?>

	    <?php } ?>

        </div>

    <?php else: ?>

        <p class="warning">
            <?php echo __('No bets selected'); ?>
        </p>

    <?php endif; ?>
</div>