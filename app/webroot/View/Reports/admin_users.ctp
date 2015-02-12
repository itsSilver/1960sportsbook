<div id="reports">

    <?php echo $this->element('admin/reports_form'); ?>

    <?php if (!empty($data)): ?>
        <table class="items">
            <tr>
                <th><?php echo __('User ID'); ?></th>
                <th><?php echo __('Date of registration'); ?></th>
                <th><?php echo __('Username'); ?></th>
                <th><?php echo __('Current balance'); ?></th>                
                <th><?php echo __('First name'); ?></th>                
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo $row['User']['id']; ?></td>
                    <td><?php echo $row['User']['registration_date']; ?></td>
                    <td><?php echo $row['User']['username']; ?></td>
                    <td><?php echo $row['User']['balance']; ?></td>                    
                    <td><?php echo $row['User']['first_name']; ?></td>                    
                </tr>
            <?php endforeach; ?>
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
