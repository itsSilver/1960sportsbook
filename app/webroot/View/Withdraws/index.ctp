<div id="withdraws" class="index">
    <h3><?php echo __('Withdraw'); ?></h3>    

    <?php echo $this->Session->flash(); ?>
   
    <h4><?php echo __('History'); ?></h4>
    
    <?php if (!empty($data)): ?>

        <table class="default-table">

            <tr>
                <th><?php echo __('Date'); ?></th>
                <th><?php echo __('Status'); ?></th>
                <th><?php echo __('Amount'); ?></th>
            </tr>

            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo $row['Withdraw']['date']; ?></td>
                    <td><?php echo $row['Withdraw']['status']; ?></td>
                    <td><?php echo $row['Withdraw']['amount']; ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>

    <?php
    $options['inputDefaults'] = array();
    echo $this->Form->create('Withdraw');

    echo $this->Form->input('amount', array('type' => 'text', 'class' => 'regi'));
    ?>
    <div class="lefted">
        <?php echo $this->Form->submit(__('Request Manual Withdraw'), array('class' => 'button')); ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>