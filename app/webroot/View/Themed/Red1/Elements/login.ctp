<div class="login">
    <?php
    if (Configure::read('Settings.login') == 1) {
        if (!$this->Session->check('Auth.User')) {

            if (Configure::read('Settings.registration') == 1) {
                echo $this->Html->link(__('Forgotten your password?', true), array('controller' => 'users', 'action' => 'reset'));
                echo $this->Html->link(__('Register now!', true), array('controller' => 'users', 'action' => 'register'));
            }

            echo $this->Form->create('User', array('action' => 'login', 'inputDefaults' => array('label' => false, 'div' => false)));

            echo $this->Form->submit('', array('div' => false, 'class' => 'lbtn'));
            echo $this->Form->input('password', array('label' => false, 'class' => 'txtp', 'error' => false, 'value' => __('Password', true)));
            echo $this->Form->input('username', array('label' => false, 'class' => 'txti', 'error' => false, 'value' => __('Username', true)));

            echo $this->Form->end();
        } else {
            echo  __('Welcome', true) . ' ' . $this->Session->read('Auth.User.username') . ' ';
            echo  __('Balance', true) . ' ' . $this->Session->read('Auth.User.balance');
            echo ' ' . Configure::read('Settings.currency');
            echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));
        }
    }
    ?>    
    <div class="clear"></div>
</div>