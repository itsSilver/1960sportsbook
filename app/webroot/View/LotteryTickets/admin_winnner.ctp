<?php
$this->groupid = $this->Session->read('Auth.User.group_id');
$status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = $jackpotnumber = $matchticketnumber = $winticketnumber = '';

if(!empty($data)) {
   $lottery_id		= $data['Lottery']['id'];
   $name		= $data['Lottery']['name'];
   $num_lott_ball	= $data['Lottery']['num_lott_ball'];
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

<div id="items">

     <?php echo $this->Form->create();?>

     <table class="marginTable default-table">
           
	<tr>				
	   <th><?php echo $this->MyHtml->spanLink(__('All Tickets >> Ticket Detail'), array('action' => 'admin_drawlist'), array('style' => 'font=size:15px;')); ?></th>
	   <th style="text-align:right"><?php echo $this->MyHtml->spanLink(__('Back'), array('action' => 'admin_drawlist'), array('class' => ''));?></th>
	</tr>	

	<tr style="background:#FFF;"><td colspan="2"></td></tr>

	<tr>
		<td>
		      <img class="logo" alt="<?php echo $logo;?>" src="/img/lottery/<?php echo $logo;?>" />
		</td>
		<td>		
			<div class="itemsTab minwidth">TICKET ID&nbsp;:&nbsp;<?php echo strtoupper($ticket_id);?></div>
			<br/ >
			<div class="itemsTab minwidth"><?php echo strtoupper($lottery_type_name);?></div>
			<br/ >
			<div class="itemsTab minwidth"><?php echo strtoupper($name);?></div>
		</td>		
	</tr>

	<tr>
           <td colspan="2">
	         <label class="itemsTab pL5"><?php echo __('Drawn Lottery Detail'); ?></label>
	   </td>
        </tr>

	<tr>
            <td>
	        <label><?php echo __('Drawn Date'); ?></label>
	   </td>
	   <td>
	        <label><?php echo date('d M Y h:i',strtotime($draw_date)); ?></label>
	   </td>
        </tr>

	<tr>
            <td>
	        <label><?php echo __('Lottery Number'); ?></label>
	   </td>
	   <td>
	        <label><?php echo $winticketnumber; ?></label>
	   </td>
        </tr>

	<?php if(isset($jackpotnumber) && $jackpotnumber!=''){?>
	<tr>
            <td>
	        <label><?php echo __(''.$lottery_type_name.' Ball'); ?></label>
	   </td>
	   <td>
	        <label><?php echo $jackpotnumber; ?></label>
	   </td>
        </tr>
	
	<?php } ?>

	</table>

	<?php if(!empty($data['prize_count'])) { ?>

	<table class="marginTable default-table">
		<tbody>

			<tr>
				<td colspan="5">
				   <label class="itemsTab pL5"><?php echo __('Prize Winner Detail'); ?></label>
				</td>
			</tr>

			<tr>
				<th>PRIZE LEVEL</th>
				<th>NUMBERS MATCHED</th>
				<th>PRIZE AMOUNT</th>
				<th>WINNERS</th>
				<th>ACTION</th>
			</tr>

			<?php foreach($data['prize_count'] as $keydata => $valueData){ ?>

			<tr>
				<td><label><?php echo $keydata;?></label></td>	
				<td><label><?php echo $valueData['level_number'];?></label></td>
				<td><label><?php echo $valueData['prize_amount'];?></label></td>
				<td><label><?php echo $valueData['ticket_count'];?></label></td>
				<td>
				    <?php if(isset($valueData['ticket_count']) && $valueData['ticket_count']!='0' && $valueData['ticket_id']!=''){?>      
					<label>
					<?php echo $this->Form->button(__('View winner', true), array('class' => 'button','id' => 'click_ticket_id'.$keydata.'',)); ?>
					<?php echo $this->Form->input('ticket_id', array('type' => 'hidden','id' => 'ticket_id'.$keydata.'','value'=>''.$valueData['ticket_id'].''));  ?>
					</label>
					<script type="text/javascript">
					jQuery(document).ready(function(){
					   jQuery('#click_ticket_id<?php echo $keydata;?>').click(function(e){
					        e.preventDefault();
						var ticketid = jQuery("#ticket_id<?php echo $keydata;?>").val();
						jQuery("#loader<?php echo $keydata;?>").html('Please wait ...').show();	
						jQuery.ajax({
						    type: "POST",
						    data: 'detail='+ticketid,
						    url : "/admin/LotteryTickets/winview/<?php echo $keydata;?>",	
						    success: function(msg){
							jQuery("#loader<?php echo $keydata;?>").html(msg).show();			    
						     }							
						});
					    });	
					});	
				      </script>
				    <?php } else { ?>
				        <label>View winner</label>
				    <?php } ?>
				</td>
			</tr>

			<tr>
			    <td colspan="5">
			         <?php echo $this->Html->div('message closable','',array('id'=>'loader'.$keydata.'','style'=>'display:none;'));?>
		            </td>
			</tr>

			<?php } ?>
		
		<tbody>

	</table>

	<?php } ?>

      <?php echo $this->Form->end(); ?>

</div>