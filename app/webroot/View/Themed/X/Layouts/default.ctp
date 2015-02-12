<!DOCTYPE HTML>
<html>
    <head>
        <?php echo $this->element('head'); ?>
        
    </head>
    <body>

        <div id="content">


            <div id="header">

                <?php echo $this->element('logo'); ?>
                <?php echo $this->element('login'); ?>

            </div>

            <div id="main">

                <?php
                if (!$this->Session->check('Auth.User')) {
                    echo $this->element('menu_user');
                } else {
                    echo $this->element('menu_user_logedin');
                }
                ?>

                <div id="left">
                    <?php echo $this->element('sports'); ?>
                    <?php echo $this->element('search_box'); ?>
                </div>

                <div id="mid">
                    <?php echo $content_for_layout ?>
                </div>

                <div id="right">
                    <?php echo $this->element('betslip'); ?>

                    <div id="sponsors"></div>

                    <div id="satisfaction"></div>

                </div>
                
                <div class="clear"></div>
                <br /><br />
            </div>

            <div id="footer-pusher"></div>

            <div id="footer">
                <?php echo $this->element('menu_footer'); ?>
                <?php echo $this->element('footer'); ?>
            </div>

        </div>
    </div>

</body>
</html>