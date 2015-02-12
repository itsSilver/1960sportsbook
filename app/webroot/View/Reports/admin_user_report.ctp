<div id="reports">

    <?php echo $this->element('admin/reports_form'); ?>

    <?php if (!empty($data)): ?>
        <table class="items">
            <tr>
                <th><?php echo __('User ID'); ?></th>
                <th><?php echo __('User name'); ?></th>
                <th><?php echo __('Tickets created'); ?></th>
                <th><?php echo __('Total'); ?></th>
                <th><?php echo __('Total payout'); ?></th>                
                <th><?php echo __('Profit'); ?></th>                
            </tr>
            <tr>
                <td><?php echo $data['userId']; ?></td>
                <td><?php echo $data['username']; ?></td>
                <td><?php echo $data['ticketsCount']; ?></td>
                <td><?php echo $data['total']; ?></td>
                <td><?php echo $data['won']; ?></td>
                <td><?php echo $data['profit']; ?></td>
            </tr>
        </table>
        <?php echo $this->Form->create('Download'); ?>
        <?php echo $this->Form->input('download', array('value' => '1', 'type' => 'hidden')); ?>
        <?php echo $this->Form->input('from', array('type' => 'hidden')); ?>
        <?php echo $this->Form->input('to', array('type' => 'hidden')); ?>
        <?php echo $this->Form->submit(__('Download', true), array('class' => 'button', 'div' => false)); ?>
        <?php echo $this->Form->end(); ?>
    <?php elseif (isset($data)): ?>
        <?php echo __('No data in this period'); ?>
    <?php endif; ?>
</div>
