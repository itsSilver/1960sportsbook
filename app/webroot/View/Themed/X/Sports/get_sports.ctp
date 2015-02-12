<div class="left_box">
    <div class="left_box_title_div"><span class="left_box_title">Sport Categories</span></div><div class="clear"></div><br />
    <ul class="bet_classes" id="sport_categories_menu">
        <?php
        $i = 0;
        foreach ($sports as $sport) {
            $link = $this->Html->link(__("All tomorrow's events"), array('controller' => 'sports', 'action' => 'tomorow', $sport['Sport']['id']));
            ?>
            <li id="<?php echo $sport['Sport']['id']; ?>">
                <a href="#">
                    <div class="sporticon" style="background-position:-76px 0;"></div>
                    <div class="bet_classes_text"><?php echo $sport['Sport']['name']; ?></div>
                    <div class="fav_a_catt"></div>
                    <div onClick="delete_fav(this);" class='delete_fav'></div>
                    <div class="clear"></div>
                </a>
            </li>
        <?php }
        ?>
    </ul>
    <div class="clear"></div>
</div>
