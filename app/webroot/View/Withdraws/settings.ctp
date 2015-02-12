<div id="withdraws" class="settings">
    <h3><?php echo __('Bank info'); ?></h3>
    <h4><?php echo __('Please provide your bank info'); ?></h4>

    <?php echo $this->Session->Flash(); ?>
    <?php
    echo $this->Form->create('User', array(
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class' => 'regi',
            # define error defaults for the form    
            'error' => array(
                'wrap' => 'span',
                'class' => 'my-error-class'
            )
        )
    ));
    ?>

    <table class="default-table">

        <tr>
            <td><label><?php echo __('Bank name'); ?></label></td>
            <td><?php echo $this->Form->input('bank_name', array('value' => $user['bank_name'])); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Account number'); ?></label></td>
            <td><?php echo $this->Form->input('account_number', array('value' => $user['account_number'])); ?></td>
        </tr>

    </table>

    <div class="centered">
        <?php echo $this->Form->submit(__('Confirm changes'), array('class' => 'button')); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>