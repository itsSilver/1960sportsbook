<div id="deposit" class="deposit">
    <h3><?php echo __('Make Deposit'); ?></h3>
    <?php echo $this->Session->flash(); ?>    

    <?php echo $this->Form->create('Deposit', array('action' => 'eyowoPreview')); ?>

    <?php echo $this->Form->input('amount', array('class' => 'regi', 'type' => 'text', 'value' => Configure::read('Settings.minDeposit'))); ?>

    <?php echo $this->element('deposits/bonus_code'); ?>
    
    <div class="lefted">
        <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>    
    </div>

    <?php echo $this->Form->end(); ?>
</div>