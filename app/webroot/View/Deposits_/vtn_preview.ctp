<h3><?php echo __('Make Deposit'); ?></h3>

<?php echo $this->element('deposits/preview'); ?>

<form name="frmsendpaymentrequest" method="post" action="https://www.virtualterminalnetwork.com/Merchant/ConfirmPayment.asp"> 

    <input type="hidden" name="merchant_email_id" value="<?php echo Configure::read('Settings.D_VtnMerchantEmailId'); ?>" />
    <input type="hidden" name="callback_id" value="<?php echo Configure::read('Settings.D_VtnCallbackId'); ?>" /> 

    <input type="hidden" name="amount" value="<?php echo $amount; ?>" class="regi" /> 
    <input type="hidden" name="item_name" value="<?php echo $this->Session->read('Auth.User.id'); ?>"> 
    <input type="hidden" name="return_url" value="<?php echo $this->Html->url(array('action' => 'success'), true); ?>"> 
    <input type="hidden" name="cancel_url" value="<?php echo $this->Html->url(array('action' => 'vtn'), true); ?>">         
    <input type="hidden" name="currency_id" value="NGN"> 

    <div class="lefted">        
        <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>
    </div>

</form> 