<h3><?php echo __('Webmoney. Make Deposit'); ?></h3>
<?php echo $this->Session->flash(); ?>    

<?php echo $this->Form->create('Deposit', array('action' => 'webmoneyPreview')); ?>

<?php echo $this->Form->input('amount', array('class' => 'regi', 'type' => 'text', 'value' => Configure::read('Settings.minDeposit'))); ?>

<div class="lefted">
    <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>    
</div>

<?php echo $this->Form->end(); ?>