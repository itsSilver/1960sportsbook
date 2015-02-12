<div id="user-info" style="margin-bottom:20px;">
 
    <div class="search">
        <table>

            <tr>

                <td>
                    <?php echo $this->Form->create('Ticket', array('action' => 'search')); ?>
                    <?php echo __('Ticket ID'); ?>
                </td>
                <td>
                    <?php echo $this->Form->input('id', array('type' => 'text', 'label' => false)); ?>                
                </td>
                <td class="va0">
                    <input type="submit" onClick="showTicket(); return false" value="Search" />
                    <?php echo $this->Form->end(); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <?php echo $this->Form->create('Event', array('action' => 'view')); ?>
                    <?php echo __('Event ID'); ?>
                </td>
                <td><?php echo $this->Form->input('id', array('type' => 'text', 'label' => false)); ?></td>
                <td class="va0">
                    <input type="submit" value="Search" />
                    <?php echo $this->Form->end(); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <?php echo $this->Form->create('Event', array('action' => 'admin_list')); ?>
                    <?php echo __('Event name'); ?>
                </td>
                <td><?php echo $this->Form->input('name', array('type' => 'text', 'label' => false)); ?></td>
                <td class="va0">
                    <input type="submit" value="Search" />
                    <?php echo $this->Form->end(); ?>
                </td>
            </tr>

	    <tr>
                <td>
                    <?php echo $this->Form->create('LotteryTicket', array('action' => 'admin_search')); ?>
                    <?php echo __('Lottery Ticket ID'); ?>
                </td>
                <td><?php echo $this->Form->input('ticket_id', array('type' => 'text', 'label' => false)); ?></td>
                <td class="va0">
                    <input type="submit" value="Search" />
                    <?php echo $this->Form->end(); ?>
                </td>
            </tr>

        </table>

    </div>

    <div class="info">
        <table>
            <tr>
                <td><?php echo __('User:'); ?></td>
                <td><?php echo $this->Session->read('Auth.User.username'); ?></td>
            </tr>
            <tr>
                <td><?php echo __('Level:'); ?></td>
                <td><?php echo $this->Session->read('Auth.User.group'); ?></td>
            </tr>
            <tr>
                <td><?php echo __('Current Time:'); ?></td>
                <td><span class="jclock"></span></td>
            </tr>
            <tr>
                <td><?php echo __('Current Language:'); ?></td>
                <td><?php echo $this->Session->read('Auth.User.language'); ?></td>
            </tr>
            <tr>
                <td class="actions" colspan="2">
                    <?php echo $this->Html->link(__('Front Page', true), '/' ,array('target' => '_blank')); ?>
                    |
                    <?php echo $this->Html->link(__('Settings', true), array('controller' => 'users', 'action' => 'settings')); ?>
                    |
                    <?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?>
                </td>
            </tr>

        </table>

    </div>

</div>
<br class="clear" />