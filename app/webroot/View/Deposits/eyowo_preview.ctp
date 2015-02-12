<div id="deposit" class="deposit">
    <h3><?php echo __('Make Deposit'); ?></h3>

    <?php echo $this->element('deposits/preview'); ?>

    <form method="post" action="https://www.eyowo.com/gateway/pay">
        <input type="hidden" name="eyw_walletcode" value="<?php echo Configure::read('Settings.D_EyowoWalletCode'); ?>" />
        <input type="hidden" name="eyw_transactionref" value="<?php echo $depositId; ?>" />

        <input type="hidden" name="eyw_item_name_1" value="<?php echo $this->Session->read('Auth.User.id'); ?>" />
        <input type="hidden" name="eyw_item_description_1" value="Deposit" />
        <input type="hidden" name="eyw_item_price_1" value="<?php echo $eyowoAmount; ?>" class="regi" />

        <div class="lefted">      
            <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>
        </div>

    </form>
</div>