<div id="deposit" class="deposit">
    <h3><?php echo __('Make Deposit'); ?></h3>

    <?php echo $this->element('deposits/preview'); ?>

    <form method='POST' action='https://voguepay.com/pay/'>

        <input type='hidden' name='v_merchant_id' value='<?php echo Configure::read('Settings.D_VoguePayMerchantId'); ?>' />
        <input type='hidden' name='merchant_ref' value='<?php echo $this->Session->read('Auth.User.id'); ?>|||<?php echo $bonusCode; ?>' />
        <input type='hidden' name='memo' value='Deposit' />

        <input type='hidden' name='notify_url' value='<?php echo $this->Html->url(array('controller' => 'deposits', 'action' => 'voguepayCallback'), true); ?>' />
        <input type='hidden' name='success_url' value='<?php echo $this->Html->url(array('controller' => 'deposits', 'action' => 'success'), true); ?>' />
        <input type='hidden' name='fail_url' value='<?php echo $this->Html->url(array('controller' => 'deposits', 'action' => 'voguepayFailed'), true); ?>' />

        <input type='hidden' name='total' value='<?php echo $amount; ?>' />

        <div class="lefted">      
            <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>
        </div>

    </form>
</div>