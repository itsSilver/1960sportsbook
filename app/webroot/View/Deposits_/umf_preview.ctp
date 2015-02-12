
<h3><?php echo __('Make Deposit'); ?></h3>

<?php echo $this->element('deposits/preview'); ?>

<FORM ACTION="https://usemyfunds.usemyservices.com/paymentpost.asp" METHOD="POST">

    <INPUT TYPE="HIDDEN" NAME="Seller" VALUE="<?php echo Configure::read('Settings.D_UmfSeller'); ?>" />

    <INPUT TYPE="HIDDEN" NAME="ItemDescription" VALUE="<?php echo $itemDescription; ?>" />
    <INPUT TYPE="HIDDEN" NAME="ItemID" VALUE="<?php echo $depositId; ?>" />  
    <INPUT TYPE="HIDDEN" NAME="ItemPrice" VALUE="<?php echo $amount; ?>" />

    <INPUT TYPE="HIDDEN" NAME="ReturnURL" VALUE="<?php echo $this->Html->url(array('action' => 'success'), true); ?>" />
    <INPUT TYPE="HIDDEN" NAME="FailedReturnURL" VALUE="<?php echo $this->Html->url(array('action' => 'umf'), true); ?>" />
    <INPUT TYPE="HIDDEN" NAME="HTTPPostURL" VALUE="<?php echo $this->Html->url(array('action' => 'umfCallback'), true); ?>" />

    <div class="lefted">      
        <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>
    </div>
    
</FORM>