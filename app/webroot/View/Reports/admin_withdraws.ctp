<div id="reports">

    <?php echo $this->element('admin/reports_form'); ?>

    <?php if (!empty($data)): ?>

        <h3><?php echo __('Withdraw report'); ?></h3>

        <table class="items">
            <tr>
                <?php foreach ($header as $title): ?>
                    <th><?php echo $title; ?></th>
                <?php endforeach; ?>                
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo $row['Withdraw']['id']; ?></td>
                    <td><?php echo $row['Withdraw']['user_id']; ?></td>
                    <td><?php echo $row['User']['username']; ?></td>
                    <td><?php echo $row['User']['first_name'] . ' ' . $row['User']['last_name'] ?></td>
                    <td><?php echo $row['User']['bank_name'] ?></td>
                    <td><?php echo $row['User']['bank_code'] ?></td>
                    <td><?php echo $row['User']['account_number'] ?></td>
                    <td><?php echo $row['Withdraw']['date']; ?></td>
                    <td><?php echo $row['Withdraw']['type']; ?></td>
                    <td><?php echo $row['Withdraw']['amount']; ?></td>                                   
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
