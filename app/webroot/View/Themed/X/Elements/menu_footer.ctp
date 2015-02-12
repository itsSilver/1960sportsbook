<div id="footer_menu">
    <div class="foot_menu_cont">
        <span>SPORTS</span><br /><br />

        <?php
        $menu = $this->requestAction('mb_menus/getmenu/');
        if (!empty($menu))
            foreach ($menu as $menuItem):

                echo $this->MyHtml->customLink($menuItem['MbMenu']['title'], $menuItem['MbMenu']['url']);
                ?> 
                <br />
            <?php endforeach; ?>

    </div>

    <div class="clear"></div>

</div>        
