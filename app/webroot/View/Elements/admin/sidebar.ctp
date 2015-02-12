<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

<div id="menu">
    <ul> 

        <li>
            <h3><?php echo __('Content'); ?></h3>
            <ul> 
                <li><?php echo $this->MyHtml->link(__('News management'), array('controller' => 'news')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Content management'), array('controller' => 'pages', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Slider management'), array('controller' => 'slides', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Header menu management'), array('controller' => 'mh_menus', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Top menu management'), array('controller' => 'mt_menus', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Bottom menu management'), array('controller' => 'mb_menus', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Bottom images management'), array('controller' => 'bottom_images', 'action' => 'index')); ?></li>
            </ul> 
        </li> 

        <?php if (false): ?>
            <li>
                <h3 class="headitem" href="#"><?php echo __('Plugins'); ?></h3> 
                <ul> 
                    <li><?php echo $this->MyHtml->link(__('c'), array('controller' => 'plugins', 'action' => 'index')); ?></li> 
                    <li><?php echo $this->MyHtml->link(__('l'), array('controller' => 'plugins', 'action' => 'index')); ?></li>                
                </ul> 
            </li> 
        <?php endif; ?>

        <li><h3 class="headitem" href="#"><?php echo __('Users management'); ?></h3> 
            <ul> 
		<?php if(isset($this->groupid) && $this->groupid !='8') { ?>
                <li><?php echo $this->MyHtml->link(__('List users'), array('controller' => 'users', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Create user'), array('controller' => 'users', 'action' => 'add')); ?></li> 
		<?php } ?>
                <li><?php echo $this->MyHtml->link(__('Search user'), array('controller' => 'users', 'action' => 'search')); ?></li> 
            </ul> 
        </li> 

	<li><h3 class="headitem" href="#"><?php echo __('Staff management'); ?></h3> 
            <ul> 
                <li><?php echo $this->MyHtml->link(__('List staff'), array('controller' => 'staffs', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Create staff'), array('controller' => 'staffs', 'action' => 'add')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Search staff'), array('controller' => 'staffs', 'action' => 'search')); ?></li>                 
            </ul> 
        </li> 

        <li><h3 class="headitem" href="#"><?php echo __('Events management'); ?></h3> 
            <ul> 
                <li><?php echo $this->MyHtml->link(__('Sports management'), array('controller' => 'sports', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Leagues management'), array('controller' => 'leagues', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Events management'), array('controller' => 'events', 'action' => 'index')); ?></li> 
            </ul> 
        </li> 


        <li><h3><?php echo $this->MyHtml->link(__('Results'), array('controller' => 'results', 'action' => 'index'), array('class' => 'headitem')); ?></h3></li> 
	<?php if(isset($this->groupid) && ($this->groupid =='8')) { ?>
        <li><h3><?php echo $this->MyHtml->link(__('Tickets'), array('controller' => 'tickets', 'action' => 'search'), array('class' => 'headitem')); ?></h3></li>  
	<?php } else { ?>
	<li><h3><?php echo $this->MyHtml->link(__('Tickets'), array('controller' => 'tickets', 'action' => 'index'), array('class' => 'headitem')); ?></h3></li>  
	<?php } ?>

        <li><h3 class="headitem" href="#"><?php echo __('Jackpot'); ?></h3> 
            <ul>

                <li><?php echo $this->MyHtml->link(__('List all guesses'), array('controller' => 'jackpot_winnings', 'action' => 'index'), array('class' => 'headitem')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Jackpot report'), array('controller' => 'reports', 'action' => 'jackpot_winning'), array('class' => 'headitem')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Jackpot size report'), array('controller' => 'reports', 'action' => 'jackpot_size'), array('class' => 'headitem')); ?></li>

            </ul>
        </li>


        <li><h3 class="headitem" href="#"><?php echo __('Deposits'); ?></h3> 
            <ul>
                <li><?php echo $this->MyHtml->link(__('List deposits'), array('controller' => 'deposits', 'action' => 'index'), array('class' => 'headitem')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Deposit options'), array('controller' => 'settings', 'action' => 'deposits'), array('class' => 'headitem')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Deposit risk management'), array('controller' => 'settings', 'action' => 'depositsRisks'), array('class' => 'headitem')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Download deposits report'), array('controller' => 'reports', 'action' => 'deposits'), array('class' => 'headitem')); ?></li></ul>
        </li>

		
	<?php if(isset($this->groupid) && ($this->groupid =='8' || $this->groupid =='2')) { ?>
	<!-- Added by Praveen Singh on 30-08-2013 -->
	<li>
	    <h3 class="headitem" href="#"><?php echo __('Agent credit management'); ?></h3> 
            <ul> 		
                <li><?php echo $this->MyHtml->link(__('List Credit Request'), array('controller' => 'agents', 'action' => 'list')); ?></li> 
                <?php if(isset($this->groupid) && $this->groupid =='8') { ?>
		<li><?php echo $this->MyHtml->link(__('Send Credit Request'), array('controller' => 'agents', 'action' => 'request')); ?></li>                
		<?php } ?>
            </ul> 
        </li> 
	<!-- Added by Praveen Singh on 30-08-2013 -->
	<?php } ?>
	

	<li><h3 class="headitem" href="#"><?php echo __('Agents credit players '); ?></h3> 
            <ul>
		 <li><?php echo $this->MyHtml->link(__('Credit Players Account'), array('controller' => 'deposit_credits', 'action' => 'index'), array('class' => 'headitem')); ?></li>
		</ul>
        </li>

        <li><h3 class="headitem" href="#"><?php echo __('Withdraws'); ?></h3> 
            <ul><li><?php echo $this->MyHtml->link(__('List requests'), array('controller' => 'withdraws', 'action' => 'index'), array('class' => 'headitem')); ?></li>
            <li><?php echo $this->MyHtml->link(__('Withdraw options'), array('controller' => 'settings', 'action' => 'withdraws'), array('class' => 'headitem')); ?></li>
            <li><?php echo $this->MyHtml->link(__('Withdraw risk management'), array('controller' => 'settings', 'action' => 'withdrawsRisks'), array('class' => 'headitem')); ?></li>
            <li><?php echo $this->MyHtml->link(__('Download withdraws report'), array('controller' => 'reports', 'action' => 'withdraws'), array('class' => 'headitem')); ?></li></ul>
        </li>

        <li><h3 class="headitem" href="#"><?php echo __('Referrals'); ?></h3> 
            <ul>
                <li><?php echo $this->MyHtml->link(__('List requests'), array('controller' => 'referrals', 'action' => 'index'), array('class' => 'headitem')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Referral settings'), array('controller' => 'settings', 'action' => 'referral')); ?></li>
            </ul>      
        </li>


        <li><h3 class="headitem" href="#"><?php echo __('Settings'); ?></h3> 
            <ul> 
                <li><?php echo $this->MyHtml->link(__('General settings'), array('controller' => 'settings', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Ticket settings'), array('controller' => 'settings', 'action' => 'tickets')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Languages'), array('controller' => 'languages', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Currencies'), array('controller' => 'currencies', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('SEO settings'), array('controller' => 'settings', 'action' => 'seo')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Email templates'), array('controller' => 'templates', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Jackpot settings'), array('controller' => 'settings', 'action' => 'jackpot')); ?></li>               

                <li><?php echo $this->MyHtml->link(__('Promotional sidebar settings'), array('controller' => 'settings', 'action' => 'promo')); ?></li>              
            </ul> 
        </li> 


        <li><h3 class="headitem" href="#"><?php echo __('Risk management'); ?></h3> 
            <ul> 
                <li><?php echo $this->MyHtml->link(__('General Settings'), array('controller' => 'risks', 'action' => 'index')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Limits for Sports'), array('controller' => 'risks', 'action' => 'sports')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Limits for Leagues'), array('controller' => 'risks', 'action' => 'leagues')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Warnings'), array('controller' => 'risks', 'action' => 'warnings')); ?></li>
            </ul> 
        </li> 

        <li><h3 class="headitem" href="#"><?php echo __('Marketing'); ?></h3> 
            <ul> 
                <li><?php echo $this->MyHtml->link(__('Create bonus code'), array('controller' => 'bonus_codes', 'action' => 'index')); ?></li>                 
                <li><?php echo $this->MyHtml->link(__('Promotion letter'), array('controller' => 'mails', 'action' => 'index')); ?></li>
                <li><?php echo $this->MyHtml->link(__('Deposit bonus'), array('controller' => 'payment_bonus_groups', action => 'index')) ?></li>
            </ul> 
        </li> 

        <li><h3 class="headitem" href="#"><?php echo __('Promotional Sidebars'); ?></h3> 
            <ul> 
                <li><?php echo $this->MyHtml->link(__('Sidebars'), array('controller' => 'settings', 'action' => 'promo')); ?></li>                

            </ul> 
        </li> 

        <li><h3><?php echo $this->MyHtml->link(__('Feed Source'), array('controller' => 'feeds', 'action' => 'index'), array('class' => 'headitem')); ?></h3></li> 

        <li><h3 class="headitem" href="#"><?php echo __('Reports'); ?></h3> 
            <ul>
		<?php if(isset($this->groupid) && ($this->groupid =='8' || $this->groupid =='2')) { ?>
		<li><?php echo $this->MyHtml->link(__('Download Leagues Schedule'), array('controller' => 'agents', 'action' => 'print_leagues')); ?></li> 
		<?php } ?>
                <li><?php echo $this->MyHtml->link(__('Download my report'), array('controller' => 'reports', 'action' => 'userReport', $this->Session->read('Auth.User.id'))); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download admins profit report'), array('controller' => 'reports', 'action' => 'adminsReport')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download operators profit report'), array('controller' => 'reports', 'action' => 'operatorsReport')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download users profit report'), array('controller' => 'reports', 'action' => 'usersReport')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download users report'), array('controller' => 'reports', 'action' => 'users')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download betting report'), array('controller' => 'reports', 'action' => 'tickets')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download deposits report'), array('controller' => 'reports', 'action' => 'deposits')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download withdraws report'), array('controller' => 'reports', 'action' => 'withdraws')); ?></li> 
                <!--
                <li><?php echo $this->MyHtml->link(__('Download transaction history report'), array('controller' => 'reports', 'action' => 'index')); ?></li>                 
                <li><?php echo $this->MyHtml->link(__('Download security report'), array('controller' => 'reports', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download events log/report'), array('controller' => 'reports', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Download results log/report'), array('controller' => 'reports', 'action' => 'index')); ?></li> 
                <li><?php echo $this->MyHtml->link(__('Google analytics report'), array('controller' => 'reports', 'action' => 'index')); ?></li> 
                -->
            </ul> 
        </li> 

        <!--
        <li><h3 class="headitem" href="#"><?php echo __('Submit promotions'); ?></h3> 
            <ul> 
                <li><?php echo $this->MyHtml->link(__('Manage promotions'), array('controller' => 'promotions', 'action' => 'index')); ?></li> 
            </ul> 
        </li> 
        --->

    </ul> 

    <?php $url = $this->MyHtml->url(array('controller' => $this->params['controller'], 'action' => $this->params['action'])); ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('a[href$="<?php echo $url; ?>"]').parent().addClass('current');
            $('a[href$="<?php echo $url; ?>"]').parent().parent().removeClass('closed').show();
            $('#menu>ul>li>h3').each(function() {                                                
                if ($(this).html() == '') {                    
                    $(this).parent().toggle();
                }
            });
            $('#menu>ul>li>ul').each(function() {                
                var a = $(this).children('li').text();
                if (a == '') {
                    $(this).parent().toggle();
                }
            });
        });
    </script>

</div>