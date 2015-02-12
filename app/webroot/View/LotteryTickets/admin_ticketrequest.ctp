<?php
$this->groupid = $this->Session->read('Auth.User.group_id');
$status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = $jackpotnumber = $matchticketnumber = $winticketnumber = '';
?>

<div id="" class="index">
    
    <?php echo $this->Session->flash(); ?>

    <h3 class="fontbld font15">All Ticket Request</h3><br>

    <table class="items">
	   
	   <tr style="background:#2B2B2B;color:#FFF;font-weight:bold;">				
		
		<th style="text-align:center;padding:7px;color:#000;"><?php echo __('USER ID'); ?></th>	
		<th style="text-align:center;padding:7px;color:#000;"><?php echo __('LOTTERY LOGO'); ?></th>
		<th style="text-align:center;padding:7px;color:#000;"><?php echo __('LOTTERY NAME'); ?></th>	
		<th style="text-align:center;padding:7px;color:#000;"><?php echo __('LOTTERY FEE'); ?></th>
		<th style="text-align:center;padding:7px;color:#000;"><?php echo __('ACTION');?></th>
            </tr>

	    <?php if (!empty($data)){ ?>

		    <?php foreach ($data as $row){	    
			   $lottery_id		   = $row['Lottery']['id'];
			   $ticket_id		   = $row['LotteryTicket']['id'];
			   $user_id		   = $row['LotteryTicket']['user_id'];
			   $agent_id		   = $row['LotteryTicket']['agent_id'];
			   $logo		   = $row['Lottery']['logo'];
			   $name		   = $row['Lottery']['name'];	   
			   $lottery_fee		   = $row['Lottery']['lottery_fee'];	   
			   ?>
			   <tr style="background:#fff;">
			     <td style="text-align:center;padding:4px;"><?php echo $user_id; ?></td> 
			     <td style="text-align:center;padding:4px;">
				<img class="logo" alt="<?php echo $logo;?>" src="/img/lottery/<?php echo $logo;?>" />
			     </td> 
			     <td style="text-align:center;padding:4px;"><?php echo $name; ?></td> 
			     <td style="text-align:center;padding:4px;"><?php echo $lottery_fee; ?></td>
			     <td style="text-align:center;padding:4px;">
				<?php echo $this->MyHtml->spanLink(__('Ticket Generation by selecting ball'), array('action' => 'admin_ticket',$lottery_id,$ticket_id), array('style' => '')); ?>&nbsp;|&nbsp;
				<?php echo $this->MyHtml->spanLink(__('Ticket Generation by selecting user ID'), array('action' => 'admin_random_ticket',$lottery_id,$ticket_id), array('style' => '')); ?>&nbsp;|&nbsp;
				<?php echo $this->MyHtml->spanLink(__('Delete'), array('action' => 'admin_ticketrequest',$ticket_id,delete),'','Do you really want to delete this ticket request.Click OK to continue and Cancel to return.'); ?>
			    </td>
			</tr>
		    <?php } ?>

		    <?php echo $this->element('paginator'); ?>  

            <?php } else { ?>
	
		   <tr>
		       <td colspan="5" style="text-align:center;padding:4px;">
			   <?php echo __('There are no records'); ?>
		       </td>   
		   </tr>  

	   <?php } ?>

    </table>
    
</div>