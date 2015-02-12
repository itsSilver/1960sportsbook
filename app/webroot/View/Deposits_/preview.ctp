<div class="deposits" class="preview">

    <h4><?php echo __('Deposit information:'); ?></h4>  

    <?php echo $this->element('deposits/preview'); ?>

    <?php echo $this->Form->create('Deposit', array('action' => 'result')); ?>

    <?php echo $this->Form->input('amount', array('type' => 'hidden', 'value' => $amount)); ?>

    <div class="centered">
        <?php echo $this->MyHtml->spanLink(__('Make test deposit', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#DepositResultForm').submit()")); ?>
    </div>

    <?php echo $this->Form->end(); ?>
    
</div>