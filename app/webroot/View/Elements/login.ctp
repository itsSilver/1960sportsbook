 <?php 
 if (!$this->Session->check('Auth.User')):?>
	<div id="upperMenu">
	    <ul>
		<li>
		    <?php
		    if (Configure::read('Settings.passwordReset') == 1) {
		       echo $this->Html->link(__('Forgot password?', true), array('controller' => 'users', 'action' => 'reset'));
		    }
		    ?>
		</li>

		<li><?php echo $this->Html->link(__('Contact us', true), array('controller' => 'mails', 'action' => 'contact')) ?></li>
		<li>
		    <a href="#" class="registerLink" onclick="registrationForm()"><?php echo __('Open account!', true); ?></a>
	        <?php
	        /*
		if (Configure::read('Settings.registration') == 1) {
		    echo $this->Html->link(__('Open account!', true), array('controller' => 'users', 'action' => 'register'), array('class' => 'registerLink') );
		}
	        */
	        ?>
		</li>
	    </ul>
	</div>
<?php else: ?>
	<div id="upperMenu">
		<?php 
		echo '<span class="welcome1">' .$this->Session->read('Auth.User.username').'</span>';
		echo '<span class="welcome2">' . $this->Session->read('Auth.User.balance');
		echo ' ' . Configure::read('Settings.currency') . ' </span>';
		echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'), array('class'=>'rightP10'));?>
	</div>
<?php endif; ?>
<div id="login">
    <?php
    if (!$this->Session->check('Auth.User')) {
        echo $this->Form->create('User', array('action' => 'login', 'inputDefaults' => array('label' => false, 'div' => false)));
        if (Configure::read('Settings.login') == 1) {
            echo $this->Form->input('username', array('label' => false, 'class' => 'log', 'error' => false, 'value' => __('Username', true)));
            echo $this->Form->input('password', array('label' => false, 'class' => 'log', 'error' => false, 'value' => __('Password', true)));
            echo $this->Form->submit(__('Login', true), array('div' => false, 'class' => 'button'));
        }
        echo $this->Form->end();
    } else {
//        echo '<span class="welcome">' . __('Welcome', true) . ' ' . $this->Session->read('Auth.User.username') . ' </span>';
//        echo '<span class="welcome">' . __('Balance', true) . ' ' . $this->Session->read('Auth.User.balance');
//        echo ' ' . Configure::read('Settings.currency') . ' </span>';
//        echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));
    

    }
    
    
    ?> 
<div id="menu-main"><br /></div>
</div>

