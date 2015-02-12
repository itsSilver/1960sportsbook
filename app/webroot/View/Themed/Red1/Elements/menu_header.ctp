    <ul class="submenu">
        <?php
        $menu = $this->requestAction('mh_menus/getmenu/');
        foreach ($menu as $menuItem):
            ?>
            <li>
                <?php echo $this->Html->link($menuItem['MhMenu']['title'], array('controller' => 'pages', 'action' => $menuItem['MhMenu']['url'])); ?>
            </li>
        <?php endforeach; ?>
    </ul>
