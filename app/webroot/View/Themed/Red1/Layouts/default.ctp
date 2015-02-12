<!DOCTYPE HTML>
<html>
    <head>
        <?php echo $this->element('head'); ?>              
    </head>
    <body>
        <div class="top">
            <div class="header">

                <?php
                if (!$this->Session->check('Auth.User')) {
                    echo $this->element('menu_user');
                } else {
                    echo $this->element('menu_user_logedin');
                }
                ?>


                <?php echo $this->Html->image('logo.gif', array('alt' => 'sbs logo', 'class' => 'logo', 'url' => array('controller' => '/'))) ?>
                <div class="clear"></div>

                <?php echo $this->element('menu_header'); ?>
                <?php echo $this->element('login'); ?>
                <div class="clear"></div>
            </div>
        </div>
        <div class="content">
            <div class="side">
                <?php echo $this->element('sports'); ?>
                <?php echo $this->element('search_box'); ?>
            </div>
            <div class="main">
                <div class="white">
                    <?php echo $content_for_layout ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <?php echo $this->element('footer'); ?>

    </body>
</html>