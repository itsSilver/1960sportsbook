<?php
$this->groupid      = $this->Session->read('Auth.User.group_id');
$this->user_id      = $this->Session->read('Auth.User.id');
$this->user_balance = $this->Session->read('Auth.User.balance');

$selectedTabAgentTicketHistory = $selectedAgentTabTicketRequest = $selectedTabrequest = $selectedTabchance = $selectedTabhistory = $selectedTababout = $selectedTabplay = $selectedTabticket = '';

if (isset($this->params['pass'][0]) && isset($this->params['pass'][1])) {
    $action_type = $this->params['pass'][1];
    $lottery_id  = $this->params['pass'][0];
}
?>

<div id="lotterytype" class="">
    <script type="text/javascript">
        function fungetLotterytype() {
            jQuery('#lotterytype').load('<?php echo $this->Html->url(array('controller' => 'LotteryTickets', 'action' => 'lotteryheader/'.$lottery_id.'')); ?>', function() {});
        }
        fungetLotterytype();
    </script>
</div>

<div class="itemsTab">
    <?php
    if (isset($action_type) && isset($lottery_id)) {       
       if(trim($action_type) == 'about'){
	  $selectedTababout  = 'itemactive';
       } else if(trim($action_type) == 'play'){
	  $selectedTabplay   = 'itemactive';
       } else if(trim($action_type) == 'ticket'){
	 $selectedTabticket = 'itemactive';
       } else if(trim($action_type) == 'history'){
	 $selectedTabhistory = 'itemactive';
       } else if(trim($action_type) == 'chance'){
	 $selectedTabchance = 'itemactive';
       } else if(trim($action_type) == 'request'){
	 $selectedTabrequest = 'itemactive';
       } else if(trim($action_type) == 'arequest'){
	 $selectedAgentTabTicketRequest = 'itemactive';
       } else if(trim($action_type) == 'ahistory'){
	 $selectedTabAgentTicketHistory = 'itemactive';
       }
    }
    ?>

    <ul>

	<li>
	    <?php echo $this->MyHtml->spanLink(__('About'), array('controller' => 'lotterys' , 'action' => 'view',$lottery_id,'about'), array('class' => ''.$selectedTababout.'')); ?>
	</li>

	<li>	
	    <?php echo $this->MyHtml->spanLink(__('Chance of winning'), array('controller' => 'lotterys' ,'action' => 'chance',$lottery_id,'chance'), array('class' => ''.$selectedTabchance.'')); ?>
	</li>

	<?php if(isset($this->user_id)) { ?>
	
	<li>		
	    <?php echo $this->MyHtml->spanLink(__('Get Ticket'), array('controller' => 'LotteryTickets' ,'action' => 'ticket',$lottery_id,'ticket'), array('class' => ''.$selectedTabticket.'')); ?>
	</li>
	
	<?php if(isset($this->groupid) && ($this->groupid =='1')) { ?>
	
	<li> 
	    <?php echo $this->MyHtml->spanLink(__('Send Ticket Request'), array('controller' => 'LotteryTickets' ,'action' => 'request_ticket',$lottery_id,'request'), array('class' => ''.$selectedTabrequest.'')); ?>
	</li>

	<?php } ?>
	
	<li>
	    <?php echo $this->MyHtml->spanLink(__('Get History'), array('controller' => 'LotteryTickets' ,'action' => 'tickets',$lottery_id,'history'), array('class' => ''.$selectedTabhistory.'')); ?>
	</li>
	           
	<?php if(isset($this->groupid) && $this->groupid =='8') { ?>
	<li>
	    <?php echo $this->MyHtml->spanLink(__('Request Ticket History'), array('controller' => 'LotteryTickets' ,'action' => 'tktreqlist',$lottery_id,'ahistory'), array('class' => ''.$selectedTabAgentTicketHistory.'')); ?>
	</li>
	<?php } ?>

	<?php } ?>

     </ul>

</div>