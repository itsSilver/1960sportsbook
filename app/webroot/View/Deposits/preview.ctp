<div id="deposit" class="deposit">

    <h3><?php echo __('Deposit information:'); ?></h3>  

    <?php echo $this->element('deposits/preview'); ?>

    <?php echo $this->Form->create('Deposit', array('action' => 'result')); ?>

    <?php echo $this->Form->input('amount', array('type' => 'hidden', 'value' => $amount)); ?>

    <?php echo $this->Form->input('bonus_code', array('type' => 'hidden', 'value' => $bonusCode)); ?>
    
    <div class="centered">
        <?php echo $this->MyHtml->spanLink(__('Request Manual Deposit (Credited Upon Verification)', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#DepositResultForm').submit()")); ?>
    </div>

    <?php echo $this->Form->end(); ?>

</div>