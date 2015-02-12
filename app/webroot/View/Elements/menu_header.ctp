<div  id="menu-header">
    <ul>
        <?php
        $menu = $this->requestAction('mh_menus/getmenu/');
        foreach ($menu as $menuItem):
            ?>
            <li>
                <?php echo $this->Html->link($menuItem['MhMenu']['title'], array('controller' => 'pages', 'action' => $menuItem['MhMenu']['url'])); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php if ($this->Session->check('Auth.User')) echo $this->element('menu_user_logedin');?>   