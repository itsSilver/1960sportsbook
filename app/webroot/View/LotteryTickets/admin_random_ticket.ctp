<?php
$file_name = $win_ticket = $win_date = $draw_date = $player = $status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_active = $prize_level = $jackpotnumber = $matchticketnumber = $winticketnumber = '';

if(!empty($data)) {
   $lottery_id		= $data['Lottery']['id'];
   $name		= $data['Lottery']['name'];
   $num_lott_ball	= $data['Lottery']['num_lott_ball'];
   $is_stuff		= $data['Lottery']['is_stuff'];
   $lottery_fee		= $data['Lottery']['lottery_fee'];
   $draw_time		= $data['Lottery']['draw_time'];
   $prize_level		= $data['Lottery']['prize_level'];
   $logo		= $data['Lottery']['logo'];
   $lottery_type_name   = $data['Lottery']['lottery_type_name'];
}
?>
<?php echo $this->Session->Flash(); ?>

<div id="lotterytype" class="">
  <script type="text/javascript">
     function fungetLotterytype() {
      jQuery('#lotterytype').load('<?php echo $this->Html->url(array('controller' => 'LotteryTickets', 'action' => '/lotteryheader/'.$lottery_id.'')); ?>', function() {});
     }
     fungetLotterytype();
  </script>
</div>

<div class="lotteryNewsDetail">

	<?php echo $this->Form->create(); ?>
    
	    <h2>CREATE LOTTERY TICKET BY USER ID</h2>
	    
	    <div class="lotteryNewsDetail-content">
		
		<p class="sporttxt"><?php echo $SITE_URL;?></p>
		
		<div class="customerTable sporttable">
		    
		    <table>
			<tbody>
				<tr>					
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('LOTTERY LOGO'); ?></th>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('LOTTERY NAME'); ?></th>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('LOTTERY TYPE'); ?></th>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('NUMBER OF BALL'); ?></th>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('STUFF BALL'); ?></th>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('LOTTERY FEE'); ?></th>
				</tr>

				<tr>					
					<td style="text-align:center;padding:4px;">
					<img class="logo" alt="<?php echo $logo;?>" src="/img/lottery/<?php echo $logo;?>" />
					</td> 
					<td style="text-align:center;padding:4px;"><?php echo $name; ?></td> 
					<td style="text-align:center;padding:4px;"><?php echo $lottery_type_name; ?></td>
					<td style="text-align:center;padding:4px;"><?php echo $num_lott_ball; ?></td>
					<td style="text-align:center;padding:4px;">		
					    <?php if(isset($is_stuff) && $is_stuff==1) { echo 'Yes'; } else { echo 'No';} ?>
				        </td>
					<td style="text-align:center;padding:4px;"><?php echo $lottery_fee.' '.$currency; ?></td>
					
				</td>

				<tr>
					<td style="text-align:right;" colspan="5"><strong>Total</strong></td>
					<td style="text-align:center;"><?php echo $lottery_fee.' '.$currency; ?></td>
				</tr>
				
			</tbody>

		     </table>

		</div>

		<p class="inputUserID pT30 pB20">
			<strong>Enter User ID<strong>&nbsp;&nbsp;&nbsp;
			<?php echo $this->Form->input('user_id', array('div' => false,'label' => false,'type' => 'text','placeholder' => 'Enter user ID here','value'=>'','id'=> 'user_id'));  ?>
			<?php echo $this->Form->hidden('lottery_id', array('type' => 'text','value' => ''.$lottery_id.''));?>
			<?php echo $this->Form->hidden('num_lott_ball', array('type' => 'text','value' => ''.$num_lott_ball.''));?>
			<?php echo $this->Form->hidden('lottery_fee', array('type' => 'text','value' => ''.$lottery_fee.''));?>
			<?php echo $this->Form->hidden('is_stuff', array('type' => 'text','value' => ''.$is_stuff.''));?>
		</strong></strong>
		</p>

		<p>
			<?php echo $this->MyHtml->spanLink(__('Replan Ticket'), array('controller' => 'lottery_tickets', 'action' => 'admin_generateticket'), array('class' => 'button')); ?> 
			<?php echo $this->Form->submit('Submit Ticket',array('div'=> false,'id'=>'generate_automatic_button','label'=>false,'class'=>'button','name'=>'data[LotteryTicket][submitTicket]'));?>			
		</strong></strong>
		</p>

	    </div>

	    <strong><strong></strong></strong>

	    <script type="text/javascript">
	    jQuery(document).ready(function(){
	     jQuery('#delete_automatic_button').click(function(){
		if(confirm("It will take a lottery fee from your account in generating a Ticket.Click OK to Contine and Cancel to Return.")){		      
		  return true;
		} else {
		  return false;
		}
	     });

	     jQuery('#generate_automatic_button').click(function(){	       
	       var user_id_field  = jQuery('#user_id').val();
	       if(user_id_field == ''){
	          jQuery('#user_id').css('border','1px solid red');
		  return false;		  
	       } else {
	          jQuery('#user_id').css('border','');
	          if(confirm("It will take a lottery fee from your account in generating a Ticket.Click OK to Contine and Cancel to Return.")){
	              jQuery('#LotteryTicketAdminRandomTicketForm').submit();
		      return true;
		  } else {
		      return false;
		  }
	       }
	     });
	   });
	 </script>

      <?php echo $this->Form->end(); ?>

</div>