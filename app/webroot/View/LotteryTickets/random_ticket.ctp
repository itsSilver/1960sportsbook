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
   $ticket_id		= $data['LotteryTicket']['id'];
   $ticket_number	= $data['LotteryTicket']['ticket_id'];
   $stuff_ball		= $data['LotteryTicket']['stuff_ball'];
   $status		= $data['LotteryTicket']['status'];
   $lottery_type_name   = $data['Lottery']['lottery_type_name'];
   $win_ticket		= $data['winData']['win_ticket'];
   $draw_date		= $data['winData']['draw_date'];
   $player		= $data['User']['username'];
   $user_id		= $data['User']['id'];
   if($stuff_ball=='') {
	$jackpotnumber     = '';
	$winticketnumber   = implode('-',explode(',',$win_ticket));
	$matchticketnumber = $win_ticket;			
   } else if(isset($win_ticket) && $win_ticket!='') {				     
       $jackpotnumber      = end(explode(',',$win_ticket));
       $winticketnumberArr = explode(',',$win_ticket);
       if(isset($winticketnumberArr)){
       unset($winticketnumberArr[count($winticketnumberArr)-1]);
       $winticketnumber    = implode('-',$winticketnumberArr);
       $matchticketnumber  = implode(',',$winticketnumberArr);
     }
   }
}
?>
<?php echo $this->Session->Flash(); ?>

<?php echo $this->element('lottery_tab'); ?>

<div class="lotteryNewsDetail">

	<?php echo $this->Form->create(); ?>
    
	    <h2>Create automatic Ticket</h2>
	    
	    <div class="lotteryNewsDetail-content">
		
		<p class="sporttxt"><?php echo $SITE_URL;?></p>
		
		<div class="customerTable sporttable">
		    
		    <table>
			<tbody>
				<tr>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('USER ID'); ?></th>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('LOTTERY Type'); ?></th>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('LOTTERY LOGO'); ?></th>
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('LOTTERY NAME'); ?></th>	
					<th style="text-align:center;padding:7px;color:#FFF;"><?php echo __('LOTTERY FEE'); ?></th>
				</tr>

				<tr style="background:#fff;">
					<td style="text-align:center;padding:4px;"><?php echo $user_id; ?></td>
					<td style="text-align:center;padding:4px;"><?php echo $lottery_type_name; ?></td>
					<td style="text-align:center;padding:4px;">
					<img style="width:40px;height:40px;border:2px solid #000000;" alt="<?php echo $logo;?>" src="/img/lottery/<?php echo $logo;?>" />
					</td> 
					<td style="text-align:center;padding:4px;"><?php echo $name; ?></td> 
					<td style="text-align:center;padding:4px;"><?php echo $lottery_fee; ?></td>
					
				</td>

				<tr>
					<td style="text-align:right;" colspan="4"><strong>Total</strong></td>
					<td style="text-align:center;"><?php echo $lottery_fee; ?> $</td>
				</tr>
				
			</tbody>

		     </table>

		</div>

		<p class="inputUserID pT30">
			<strong>Enter User ID<strong>&nbsp;&nbsp;&nbsp;
			<?php echo $this->Form->input('user_id', array('div' => false,'label' => false,'type' => 'text','placeholder' => 'Enter user ID here','value'=>'','id'=> 'user_id'));  ?>
			<?php echo $this->Form->hidden('ticket_id', array('type' => 'text','value' => ''.$ticket_id.''));?>
			<?php echo $this->Form->hidden('lottery_id', array('type' => 'text','value' => ''.$lottery_id.''));?>
			<?php echo $this->Form->hidden('num_lott_ball', array('type' => 'text','value' => ''.$num_lott_ball.''));?>
			<?php echo $this->Form->hidden('lottery_fee', array('type' => 'text','value' => ''.$lottery_fee.''));?>
			<?php echo $this->Form->hidden('is_stuff', array('type' => 'text','value' => ''.$is_stuff.''));?>
		</strong></strong>
		</p>

		<strong></strong>
		<p>
			<?php echo $this->Form->submit('Delete Ticket',array('div'=> false,'id'=>'delete_automatic_button','type'=>'submit','label'=>false,'class'=>'button','name'=>'data[LotteryTicket][deleteTicket]'));?>
			<?php echo $this->Form->submit('Submit Ticket',array('div'=> false,'id'=>'generate_automatic_button','label'=>false,'class'=>'button','name'=>'data[LotteryTicket][submitTicket]'));?>
		</p>

		</strong></strong>

	    </div>

	    <strong><strong></strong></strong>

	    <script type="text/javascript">
	    jQuery(document).ready(function(){
	     jQuery('#delete_automatic_button').click(function(){
		if(confirm("Do you really want to delete this ticket request.Click OK to continue and Cancel to return.")){
		  return true;
		} else {
		  return false;
		}
	     });

	     jQuery('#generate_automatic_button').click(function(){	       
	       var user_id_field  = jQuery('#user_id').val();
	       var user_id_match  = '<?php echo $user_id;?>';
	       if(user_id_field == ''){
	          jQuery('#user_id').css('border','3px solid red');
		  return false;		  
	       } else if(user_id_field != user_id_match){
		 jQuery('#user_id').css('border','3px solid red');
		 jQuery("#user_id").attr("value", "");
		 jQuery("#user_id").attr("placeholder", "user ID not found");
		 return false;		  
	       } else {
	          jQuery('#user_id').css('border','');
	          if(confirm("It will generate a Lottery Ticket.Click OK to Contine and Cancel to Return.")){
	              jQuery('#LotteryTicketRandomTicketForm').submit();
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