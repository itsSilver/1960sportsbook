<h3><?php echo __('Make Deposit'); ?></h3>

<?php echo $this->element('deposits/preview'); ?>

<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp">  
  <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?php echo $amount; ?>">
  <input type="hidden" name="LMI_PAYMENT_DESC" value="deposit">  
  <input type="hidden" name="LMI_PAYEE_PURSE" value="<?php echo Configure::read('Settings.wm_pursue'); ?>">
  <input type="hidden" name="LMI_SIM_MODE" value="0">
  <div class="lefted">      
        <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>
  </div>
</form>