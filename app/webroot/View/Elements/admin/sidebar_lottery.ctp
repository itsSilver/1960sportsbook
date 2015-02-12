<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

<div id="menu">
   
    <ul> 
        
	<li>
            <h3><?php echo __('Content Management'); ?></h3>
            <ul>                
                 <li><?php echo $this->MyHtml->link(__('Slider management'), array('controller' => 'slides', 'action' => 'index')); ?></li>      
		 <li><?php echo $this->MyHtml->link(__('Header menu management'), array('controller' => 'mh_menus', 'action' => 'index')); ?></li>
		 <li><?php echo $this->MyHtml->link(__('Promotional sidebar settings'), array('controller' => 'settings', 'action' => 'promo')); ?></li>
            </ul> 
        </li> 

        <li>
            <h3 class="headitem" href="#"><?php echo __('Lottery Type Management'); ?></h3>
            <ul id="LotteryTypes"> 
                <li><?php echo $this->MyHtml->link(__('Create Lottery Type'), array('controller' => 'LotteryTypes','action' => 'type')); ?></li>
		<li><?php echo $this->MyHtml->link(__('List Lottery Type'), array('controller' => 'LotteryTypes','action' => 'types')); ?></li>					
            </ul> 
        </li>

	<li>
            <h3 class="headitem" href="#"><?php echo __('Lottery Management'); ?></h3>
            <ul id="lotterys"> 
		<li><?php echo $this->MyHtml->link(__('Create Lottery Game'), array('controller' => 'lotterys','action' => 'create')); ?></li>
		<li><?php echo $this->MyHtml->link(__('List Lottery Game'), array('controller' => 'lotterys','action' => 'list')); ?></li>
		<!-- <li><?php echo $this->MyHtml->link(__('Lottery Draw Date Settings'), array('controller' => 'lotterys', 'action' => 'admin_drawdate')); ?></li> -->
		<li><?php echo $this->MyHtml->link(__('Lottery System Settings'), array('controller' => 'lotterys', 'action' => 'admin_system')); ?></li>
		<li><?php echo $this->MyHtml->link(__('Prize Level Settings'), array('controller' => 'lotterys', 'action' => 'admin_levels')); ?></li> 
		<li><?php echo $this->MyHtml->link(__('Prize Amount Settings'), array('controller' => 'lotterys', 'action' => 'admin_amount')); ?></li>
		<li><?php echo $this->MyHtml->link(__('Jackpot Prize Settings'), array('controller' => 'lotterys', 'action' => 'admin_jackpotset')); ?></li>
		<li><?php echo $this->MyHtml->link(__('Agent Percent Settings'), array('controller' => 'users', 'action' => 'admin_agent_list')); ?></li>
            </ul> 
        </li>

	<li>
            <h3 class="headitem" href="#"><?php echo __('Ticket Management'); ?></h3>
            <ul id="lottery_tickets"> 
                <li><?php echo $this->MyHtml->link(__('List Tickets'), array('controller' => 'lottery_tickets','action' => 'admin_tickets')); ?></li>
		<!-- <li><?php echo $this->MyHtml->link(__('All Tickets Request'), array('controller' => 'lottery_tickets','action' => 'admin_ticketrequest')); ?></li> -->
		<li><?php echo $this->MyHtml->link(__('Ticket Approval Request List'), array('controller' => 'lottery_tickets','action' => 'admin_ticketapprove')); ?></li>
		<li><?php echo $this->MyHtml->link(__('Ticket Approval List'), array('controller' => 'lottery_tickets','action' => 'admin_tktreqlist')); ?></li>
		<?php if(isset($this->groupid) && ($this->groupid =='8')) { ?>
		<li><?php echo $this->MyHtml->link(__('Ticket Generation by User ID'), array('controller' => 'lottery_tickets','action' => 'admin_generateticket')); ?></li>
		<?php } ?>
            </ul>
	    
        </li>

	<!-- <li>
            <h3 class="headitem" href="#"><?php echo __('Result Management'); ?></h3>
            <ul> 
                <li><?php echo $this->MyHtml->link(__('Result History'), array('controller' => '')); ?></li> 
            </ul> 
        </li>-->

	<li>
            <h3 class="headitem" href="#"><?php echo __('Draw Management'); ?></h3>
            <ul> 
                <li><?php echo $this->MyHtml->link(__('Lottery Ticket Draw List'), array('controller' => 'LotteryTickets', 'action' => 'admin_drawlist')); ?></li>
		<!-- <li><?php echo $this->MyHtml->link(__('Jackpot Ticket Draw'), array('controller' => 'LotteryTickets', 'action' => 'admin_jackpotdraw')); ?></li> -->
            </ul>
	</li> 

    </ul> 

    <?php 
    $controller = $this->params['controller'];
    $url = $this->MyHtml->url(array('controller' => $this->params['controller'], 'action' => $this->params['action'])); ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
	    $('#<?php echo $controller; ?>').show();
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