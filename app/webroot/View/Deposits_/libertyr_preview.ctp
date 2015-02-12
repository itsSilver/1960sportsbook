
<h3><?php echo __('Make Deposit'); ?></h3>

<?php echo $this->element('deposits/preview'); ?>
<form method="post" action="https://sci.libertyreserve.com">
<input type="hidden" name="lr_acc" value="<?php echo Configure::read('Settings.lr_merchantAccountNumber'); ?>">
      <input type="hidden" name="lr_amnt" value="<?php echo $amount; ?>">
      <input type="hidden" name="lr_currency" value="LRUSD">
      <!-- urls -->
      <input type="hidden" name="lr_success_url" value="<?php echo $this->Html->url(array('action' => 'success'), true); ?>">
      <input type="hidden" name="lr_fail_url" value="<?php echo $this->Html->url(array('action' => 'libertyr'), true); ?>">
      <!-- baggage fields -->      
      <input type="hidden" name="item_name" value="Deposit" />
      <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>
</form>
      