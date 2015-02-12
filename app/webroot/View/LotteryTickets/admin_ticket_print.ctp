<?php
$stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = '';

if($this->Session->read('ticketData')) {
   $lottery_id		= $this->Session->read('ticketData.Lottery.id');
   $name		= $this->Session->read('ticketData.Lottery.name');
   $num_lott_ball	= $this->Session->read('ticketData.Lottery.num_lott_ball');
   $lottery_fee		= $this->Session->read('ticketData.Lottery.lottery_fee');
   $draw_time		= $this->Session->read('ticketData.Lottery.draw_time');
   $prize_level		= $this->Session->read('ticketData.Lottery.prize_level');
   $logo		= $this->Session->read('ticketData.Lottery.logo');
   $ticket_id		= $this->Session->read('ticketData.LotteryTicket.ticket_id');
   $stuff_ball		= $this->Session->read('ticketData.LotteryTicket.stuff_ball');
   $lottery_type_name   = $this->Session->read('ticketData.Lottery.lottery_type_name');
   $player		= $this->Session->read('ticketData.User.username');
   $currency		= Configure::read('Settings.currency');
}
if($this->Session->read('uniqidTicketId')) {
  $ticId = $this->Session->read('uniqidTicketId');
}
?>

<div id="account">

     <?php echo $this->Session->Flash(); ?>

     <div id="lotterytype" class="">
	<script type="text/javascript">
	function fungetLotterytype() {
	   jQuery('#lotterytype').load('<?php echo $this->Html->url(array('controller' => 'LotteryTickets', 'action' => '/lotteryheader/'.$lottery_id.'')); ?>', function() {});
	}
	fungetLotterytype();
	</script>
     </div>
    
     <?php echo $this->Form->create();?>

     <h3 class="fontbld font15"><?php echo __('Your created Lottery Ticket Detail.'); ?></h3><br/ >

     <table class="marginTable default-table">

	<tr>
		<td><img class="logo" alt="<?php echo $logo;?>" src="/img/lottery/<?php echo $logo;?>" /></td>
		<td>		
			<div class="itemsTab minwidth">TICKET ID&nbsp;:&nbsp;<?php if(isset($ticId)){ echo strtoupper($ticId);}?></div>
			<br/ >
			<div class="itemsTab minwidth"><?php echo strtoupper($lottery_type_name);?></div>
			<br/ >
			<div class="itemsTab minwidth"><?php echo strtoupper($name);?></div>
		</td>		
	</tr>

	<tr>
	    <td colspan="2">
		 <label class="itemsTab"><?php echo __('User Lottery Detail'); ?></label>
	    </td>
	</tr>

	<tr style="font-weight:bold;">
	     <td><label><?php echo __('Lottery Player'); ?></label></td>
	     <td><label><?php if(isset($player)) { echo $player;} ?></label></td>		
	</tr>

	<tr>
            <td>
	        <label><?php echo __('Lottery Number'); ?></label>
	   </td>
	   <td>
	       <label><?php if(isset($ticket_id)) { echo implode('-',explode(',',$ticket_id));} ?></label>
	   </td>
        </tr>

	<?php if(isset($stuff_ball) && $stuff_ball!='') {?>
	<tr>
            <td>
	        <label><?php echo __(''.$lottery_type_name.' Ball'); ?></label>
	   </td>
	   <td>
	       <label><?php if(isset($stuff_ball)) { echo $stuff_ball;} else { echo 'None';} ?></label>
	   </td>
        </tr>
	
	<?php } ?>

	<tr>
            <td>
	        <label><?php echo __('Lottery Fee'); ?></label>
	   </td>
	   <td>
	       <label><?php echo $lottery_fee.' '.$currency;?></label>
	   </td>
        </tr>

	<?php if(isset($ticId)){?>
	<tr>
            <td>
	       <?php echo $this->MyHtml->spanLink(__('Print'), array('action' => 'admin_ticket_print',$ticId,'print'), array('class' => 'button')); ?>
	       <?php echo $this->MyHtml->spanLink(__('Go to Tickets'), array('controller' => 'lottery_tickets', 'action' => 'admin_tickets'), array('class' => 'button')); ?>
	       <?php echo $this->MyHtml->spanLink(__('Generate Ticket Again'), array('controller' => 'lottery_tickets', 'action' => 'admin_generateticket'), array('class' => 'button')); ?>       
	    </td>
        </tr>
	<?php } ?>      

	</table>

     <?php echo $this->Form->end(); ?>

</div>