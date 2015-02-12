<div id="users" class="reset">
    <h3><?php echo __('Password reset'); ?></h3>
    <h4><?php echo __('Fill in email details below. Password reset link sent to your entered email. You will be able to create a new password which you can use to login.'); ?></h4>
    <?php
    echo $this->Session->flash();
    if (!isset($success)) {
        echo $this->Form->create('User', array('action' => 'reset'));

        echo $this->Form->input('email', array('label' => __('E-mail', true), 'class' => 'regi'));
        ?>
        <div class="lefted">
            <?php echo $this->MyHtml->spanLink(__('Send', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#UserResetForm').submit()")); ?>
        </div>
        <?php
        echo $this->Form->end();
    }
    ?>
</div>

