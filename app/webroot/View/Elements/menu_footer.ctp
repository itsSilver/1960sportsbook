<div id="menu-bottom"> 
    <?php
    $menu = $this->requestAction('mb_menus/getmenu/');
    if (!empty($menu))
        foreach ($menu as $menuItem):
            
            echo $this->MyHtml->customLink($menuItem['MbMenu']['title'], $menuItem['MbMenu']['url']);
            
            ?>

    <?php endforeach; ?>


</div>        
