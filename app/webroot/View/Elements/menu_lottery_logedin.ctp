<div id="menu-header-submenu">
    <ul> 
        <li><?php echo $this->Html->link(__('Tickets', true), array('controller' => 'LotteryTickets', 'action' => 'ticketlists')); ?></li>
        <li><?php echo $this->Html->link(__('Profile', true), array('controller' => 'users', 'action' => 'account')); ?></li> 
        <li><?php echo $this->Html->link(__('Settings', true), array('controller' => 'users', 'action' => 'settings')); ?></li>        
    </ul>
</div>
