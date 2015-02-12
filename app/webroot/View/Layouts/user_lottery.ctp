<!DOCTYPE HTML>
<html>
    <head>
        <?php echo $this->element('head'); ?>
        <?php echo $this->element('clock'); ?>
        <?php echo $this->element('google-analytics'); ?> <!-- Added on 12/2/2012-->
    </head>
    <body>
        <div class="backgroundBox"><?php echo $this->Html->image('bet/bck.jpg'); ?></div>
        <div id="layout-wraper">
		
	   <?php $this->groupid = $this->Session->read('Auth.User.group_id');?>
	   <?php $this->userid = $this->Session->read('Auth.User.id'); ?>

           <div id="layout-header" class="group">

                <?php echo $this->element('login'); ?>

                <div id="header-first" class="group">     
                    <a href="/" id="logo"></a>
                </div>

                <table style="float:right; color:white; font-weight:bold;width: auto !important;">
                       <tr>
                           <td><?php echo __('Current Time:&nbsp;'); ?></td>
                           <td><span class="jclock"></span></td>
                       </tr>
                </table>
		
                <div id="header-second" class="group">
		    <?php if(isset($this->groupid) && isset($this->userid) && ($this->groupid =='2' || $this->userid =='743' || $this->userid =='744' || $this->userid =='746')) {?>
		    <?php echo $this->element('dashboard'); ?>
		    <?php } ?>
		    <?php echo $this->element('menu_lottery_header'); ?>
                </div>

                <div id="header-third" class="group">

                </div>
            </div>
            <!-- here -->
            <div id="layout-main" class="main group">
                <div id="sidebar"> 
                    <?php echo $this->element('lotterys'); ?>
                    <?php echo $this->element('search_lottery_box'); ?>
                    <?php //echo $this->element('com100'); ?>
                    <?php echo $this->element('left_promo'); ?>                    

                </div>

                <div id="aside-second">
                    <?php //echo $this->element('betslip'); ?>
                    <?php //echo $this->element('jackpot'); ?> 
		    <?php echo $this->element('right_promo'); ?>
                </div>

                <div id="layout-content">
                    <?php echo $content_for_layout ?>
                </div>

                <?php echo $this->element('bottom_promo'); ?>
            </div>

            <div id="footer-pusher"></div>

        </div>

        <div id="layout-footer" class="group">
            <?php echo $this->element('bottom_images'); ?>
            <?php echo $this->element('menu_footer'); ?>
            <?php //echo $this->element('footer'); ?>
            <br class="clear" />
        </div>

    </body>
</html>