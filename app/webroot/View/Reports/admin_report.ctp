<div id="reports">

    <?php echo $this->element('admin/reports_form'); ?>

    <?php if (!empty($data)): ?>
        <?php foreach ($data as $report): ?>
            <table class="items">
                <tr>
                    <?php foreach ($report['header'] as $title): ?>
                        <th><?php echo $title; ?></th>
                    <?php endforeach; ?>                
                </tr>
                <?php foreach ($report['data'] as $row): ?>
                    <tr>
                        <?php foreach ($row as $field): ?>
                            <td><?php echo $field; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>

        <?php endforeach; ?>

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
