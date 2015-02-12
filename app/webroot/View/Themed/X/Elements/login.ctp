<div id="login">
    <?php
    if (!$this->Session->check('Auth.User')) {
        if (Configure::read('Settings.login') == 1) {
            echo $this->Form->create('User', array('action' => 'login', 'inputDefaults' => array('label' => false, 'div' => false)));
            ?>
            <div style="float:left">
                <?php echo __('Username:'); ?>
                <?php echo $this->Form->input('username', array('id' => 'username_login', 'label' => false, 'class' => 'log', 'error' => false, 'value' => __('Username'))); ?>
                <?php echo __('Password:'); ?>
                <?php echo $this->Form->input('password', array('id' => 'password_login', 'label' => false, 'class' => 'log', 'error' => false, 'value' => __('Password'))); ?>
            </div>
            <div class="blue_btn" style="margin:2px 0 0 10px; width:60px;" onClick="$('#UserLoginForm').submit();">Login</div>

            <?php
            echo $this->Form->end();
        }
        ?>
        <div class="clear"></div>
        <div style="text-align:right; color:#666666;">
            <?php
            if (Configure::read('Settings.registration') == 1) {
                echo $this->Html->link(__('Register now!', true), array('controller' => 'users', 'action' => 'register'), array('id' => 'register_link'));
            }
            ?>
            | 
            <?php
            if (Configure::read('Settings.passwordReset') == 1) {
                echo $this->Html->link(__('Forgotten your password?', true), array('controller' => 'users', 'action' => 'reset'), array('class' => 'white_link'));
            }
            ?>
        </div>
        <?php
    } else {
        echo '<span class="welcome">' . __('Welcome', true) . ' ' . $this->Session->read('Auth.User.username') . ' </span>';
        echo '<span class="welcome">' . __('Balance', true) . ' ' . $this->Session->read('Auth.User.balance');
        echo ' ' . Configure::read('Settings.currency') . ' </span>';
        echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));
    }
    ?>    

</div>