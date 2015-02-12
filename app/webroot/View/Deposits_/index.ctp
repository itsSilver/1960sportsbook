<div id="deposits" class="index">
    <h3><?php echo __('Deposit'); ?></h3>

    <?php echo $this->Session->flash(); ?>

    <?php if (!empty($data)): ?>

        <table class="default-table">

            <tr>
                <th><?php echo $this->Paginator->sort('Deposit.date', __('Date')); ?></th>
                <th><?php echo $this->Paginator->sort('Deposit.type', __('Type')); ?></th>
                <th><?php echo $this->Paginator->sort('Deposit.amount', __('Amount')); ?></th>
                <th><?php echo $this->Paginator->sort('Deposit.status', __('Status')); ?></th>
                <th><?php echo $this->Paginator->sort('Deposit.deposit_id', __('Deposit ID')); ?></th>
            </tr>

            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo $row['Deposit']['date']; ?></td>
                    <td><?php echo $row['Deposit']['type']; ?></td>
                    <td><?php echo $row['Deposit']['amount']; ?></td>
                    <td><?php echo $row['Deposit']['status']; ?></td>
                    <td><?php echo $row['Deposit']['deposit_id']; ?></td>
                </tr>  
            <?php endforeach; ?>

        </table>

        <?php echo $this->element('paginator'); ?>  

    <?php endif; ?>

    <div class="centered">
        <?php echo $this->MyHtml->spanLink(__('Make Deposit'), array('action' => 'choose'), array('class' => 'button-blue')); ?>
    </div>
</div>