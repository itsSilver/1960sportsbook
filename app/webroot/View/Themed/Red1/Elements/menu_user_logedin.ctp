
    <ul class="menu"> 
        <li><?php echo $this->Html->link(__('Tickets', true), array('controller' => 'tickets', 'action' => 'index')); ?></li> 
        <li><?php echo $this->Html->link(__('Deposit', true), array('controller' => 'deposits', 'action' => 'index')); ?></li> 
        <li><?php echo $this->Html->link(__('Withdraw', true), array('controller' => 'withdraws', 'action' => 'index')); ?></li> 
        <li><?php echo $this->Html->link(__('Account information', true), array('controller' => 'users', 'action' => 'account')); ?></li> 
        <li><?php echo $this->Html->link(__('Settings', true), array('controller' => 'users', 'action' => 'settings')); ?></li> 
        <li><?php echo $this->Html->link(__('Promotions', true), array('controller' => 'users', 'action' => 'bonus')); ?></li> 
    </ul>

