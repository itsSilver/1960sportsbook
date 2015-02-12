<div id="users" class="reset1">
    <h3><?php echo __('Password reset'); ?></h3> 
    <h4><?php echo __('Enter your new password'); ?></h4>
    <?php
    echo $this->Session->flash();
    if (!isset($success)) {
        echo $this->Form->create('User', array('action' => 'reset'));
        echo $this->Form->input('code', array('value' => $code, 'type' => 'hidden'));
        echo $this->Form->input('password', array('label' => __('Password', true), 'class' => 'regi'));

        echo $this->Form->input('password_confirm', array('label' => __('Confirm password', true), 'type' => 'password', 'class' => 'regi'));
        ?>
        <div class="lefted">
            <?php echo $this->MyHtml->spanLink(__('Change password', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#UserResetForm').submit()")); ?>
        </div>
        <?php
        echo $this->Form->end();
    }
    ?>
</div>