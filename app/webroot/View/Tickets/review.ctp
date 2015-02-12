<div id="tickets" class="review">
    <h3><?php echo __('Ticket placed'); ?></h3>
    <?php if (isset($tickets)): ?>

        <?php foreach ($tickets as $ticket): ?>

            <div class="ticket-print">

                <h1><?php echo Configure::read('Settings.websiteName'); ?></h1>
                <h2><?php __('Ticket'); ?></h2>

                <table>
                    <tr>
                        <td><?php echo __('Date and time'); ?></td>
                        <td class="align-right"><?php echo $ticket['Ticket']['date']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Ticket id'); ?></td>
                        <td class="align-right"><?php printf('%1$07d', $ticket['Ticket']['id']); ?></td>
                    </tr>
                </table>

                <table class="bets">
                    <tr>
                        <th><?php echo __('Event ID / Date'); ?></th>
                        <th><?php echo __('Event'); ?></th>
                        <th><?php echo __('Pick'); ?></th>
                        <th><?php echo __('Odd'); ?></th>
                    </tr>
                    <?php foreach ($ticket['TicketPart'] as $ticketPart): ?>
                        <tr>
                            <td>
                                <?php echo __('ID:'); ?> <?php echo $ticketPart['Bet']['id']; ?>
                                <br />
                                <?php echo $ticketPart['Event']['date']; ?>
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
                        <td class="align-right"><?php echo __('Total:'); ?> <?php echo $this->Beth->convertOdd($ticket['Ticket']['odd']); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="align-right"><?php echo __('Amount:'); ?> <?php echo $ticket['Ticket']['amount']; ?> <?php echo $currency; ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="align-right">
                            <?php echo __('Return:'); ?> 
                            <?php
                            if ($ticket['Ticket']['type'] != 3) {
                                echo $this->Beth->convertCurrency($ticket['Ticket']['return']) . ' ' . $currency;
                            } else {                                
                                echo __('JACKPOT');
                            }
                            ?>
                        </td>
                    </tr>
                                            <tr>
                        <td></td>
                        <td class="align-right">
                            <?php echo __('Jackpot:'); ?> 
                            <?php
                            if ($type != 3) {
                                echo $this->Beth->convertCurrency($ticket['Ticket']['amount']*(float)Configure::read('Settings.jackpotPercent')) . ' ' . $currency;
                            } else {                                
                                echo __('JACKPOT');
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="centered">     
                <p>
                    <?php echo __('Your ticket is created successfully'); ?>
                    <br />
                    <?php echo __('Your ticket number is'); ?> <?php echo $ticket['Ticket']['id']; ?>
                </p>
                <?php if (Configure::read('Settings.printing')): ?>
                    <?php echo $this->MyHtml->spanLink(__('Print', true), array('action' => 'printTicket', $ticket['Ticket']['id'] . '.pdf'), array('class' => 'button-blue', 'target' => '_blank')); ?>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>

    <?php else: ?>
    <?php endif; ?>

</div>