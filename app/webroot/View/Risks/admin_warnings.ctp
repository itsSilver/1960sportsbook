<?php if (!empty($bigOddTickets)): ?>
    <h4><?php echo __('Warning Odds'); ?></h4>
    <table class="items">
        <tr>
            <th><?php echo __('Id'); ?></th>
            <th><?php echo __('Date'); ?></th>
            <th><?php echo __('User'); ?></th>
            <th><?php echo __('Stake'); ?></th>
            <th><?php echo __('Odd'); ?></th>
            <th><?php echo __('Winning'); ?></th>
        </tr>
        <?php foreach ($bigOddTickets as $ticket): ?>
            <tr>
                <td><?php echo $ticket['Ticket']['id']; ?></td>
                <td><?php echo $ticket['Ticket']['date']; ?></td>
                <td><?php echo $ticket['User']['username']; ?></td>            
                <td><?php echo $ticket['Ticket']['amount']; ?></td>
                <td><?php echo $ticket['Ticket']['odd']; ?></td>
                <td><?php echo $ticket['Ticket']['return']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<?php endif; ?>

<?php if (!empty($bigStakeTickets)): ?>
    <h4><?php echo __('Warning Stakes'); ?></h4>
    <table class="items">
        <tr>
            <th><?php echo __('Id'); ?></th>
            <th><?php echo __('Date'); ?></th>
            <th><?php echo __('User'); ?></th>
            <th><?php echo __('Stake'); ?></th>
            <th><?php echo __('Odd'); ?></th>
            <th><?php echo __('Winning'); ?></th>
        </tr>
        <?php foreach ($bigStakeTickets as $ticket): ?>
            <tr>
                <td><?php echo $ticket['Ticket']['id']; ?></td>
                <td><?php echo $ticket['Ticket']['date']; ?></td>
                <td><?php echo $ticket['User']['username']; ?></td>            
                <td><?php echo $ticket['Ticket']['amount']; ?></td>
                <td><?php echo $ticket['Ticket']['odd']; ?></td>
                <td><?php echo $ticket['Ticket']['return']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<?php endif; ?>

<?php if (!empty($bigWinningTickets)): ?>
    <h4><?php echo __('Warning Winnings'); ?></h4>
    <table class="items">
        <tr>
            <th><?php echo __('Id'); ?></th>
            <th><?php echo __('Date'); ?></th>
            <th><?php echo __('User'); ?></th>
            <th><?php echo __('Stake'); ?></th>
            <th><?php echo __('Odd'); ?></th>
            <th><?php echo __('Winning'); ?></th>
        </tr>
        <?php foreach ($bigWinningTickets as $ticket): ?>
            <tr>
                <td><?php echo $ticket['Ticket']['id']; ?></td>
                <td><?php echo $ticket['Ticket']['date']; ?></td>
                <td><?php echo $ticket['User']['username']; ?></td>            
                <td><?php echo $ticket['Ticket']['amount']; ?></td>
                <td><?php echo $ticket['Ticket']['odd']; ?></td>
                <td><?php echo $ticket['Ticket']['return']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<?php endif; ?>

<?php if (!empty($bigDeposits)): ?>
    <h4><?php echo __('Warning Deposits'); ?></h4>
    <table class="items">
        <tr>
            <th><?php echo __('Id'); ?></th>
            <th><?php echo __('Date'); ?></th>
            <th><?php echo __('User'); ?></th>
            <th><?php echo __('amount'); ?></th>            
        </tr>
        <?php foreach ($bigDeposits as $deposit): ?>
            <tr>
                <td><?php echo $deposit['Deposit']['id']; ?></td>
                <td><?php echo $deposit['Deposit']['date']; ?></td>
                <td><?php echo $deposit['User']['username']; ?></td>            
                <td><?php echo $deposit['Deposit']['amount']; ?></td>                
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<?php endif; ?>

<?php if (!empty($bigWithdraws)): ?>
    <h4><?php echo __('Warning Withdraws'); ?></h4>
    <table class="items">
        <tr>
            <th><?php echo __('Id'); ?></th>
            <th><?php echo __('Date'); ?></th>
            <th><?php echo __('User'); ?></th>
            <th><?php echo __('amount'); ?></th>            
        </tr>
        <?php foreach ($bigWithdraws as $deposit): ?>
            <tr>
                <td><?php echo $deposit['Withdraw']['id']; ?></td>
                <td><?php echo $deposit['Withdraw']['date']; ?></td>
                <td><?php echo $deposit['User']['username']; ?></td>            
                <td><?php echo $deposit['Withdraw']['amount']; ?></td>                
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<?php endif; ?>