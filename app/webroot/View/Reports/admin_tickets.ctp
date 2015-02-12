<div id="reports">

    <?php echo $this->element('admin/reports_form'); ?>

    <?php if (!empty($data)): ?>
        <table class="items">
            <tr>
                <?php foreach ($header as $title): ?>
                    <th><?php echo $title; ?></th>
                <?php endforeach; ?>                
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo $row['Ticket']['id']; ?></td>
                    <td><?php echo $row['Ticket']['user_id']; ?></td>
                    <td><?php echo $row['Ticket']['date']; ?></td>
                    <td><?php echo $row['Ticket']['type']; ?></td>
                    <td><?php echo $row['Ticket']['events_count']; ?></td>
                    <td><?php echo $row['Ticket']['amount']; ?></td>
                    <td><?php echo $row['Ticket']['odd']; ?></td>
                    <td><?php echo $row['Ticket']['return']; ?></td>
                    <td><?php echo $this->Beth->getStatus($row['Ticket']['status']); ?></td>
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
