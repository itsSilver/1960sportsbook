<div id="menu-main">
    <ul> 
        <?php
        $menu = $this->requestAction('mt_menus/getmenu/');
        foreach ($menu as $menuItem):
            ?>
            <li>
                <?php echo $this->Html->link($menuItem['MtMenu']['title'], array('controller' => 'pages', 'action' => $menuItem['MtMenu']['url'])); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>