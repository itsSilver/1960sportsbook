<?php
$this->groupid = $this->Session->read('Auth.User.group_id');
$status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = $jackpotnumber = $matchticketnumber = $winticketnumber = '';
?>

<div id="" class="index">
    
    <?php echo $this->Session->flash(); ?>

    <?php echo $this->element('lottery_tab'); ?>

    <table class="default-table">
	   
	   <tr>				
		
		<th><?php echo __('User ID'); ?></th>	
		<th><?php echo __('Lottery Logo'); ?></th>
		<th><?php echo __('Lottery Name'); ?></th>	
		<th><?php echo __('Lottery Fee'); ?></th>
		<th><?php echo __('Action');?></th>
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
			   <tr>
			     <td><?php echo $user_id; ?></td> 
			     <td>
				<img style="width:40px;height:40px;border:2px solid #000000;" alt="<?php echo $logo;?>" src="/img/lottery/<?php echo $logo;?>" />
			     </td> 
			     <td><?php echo $name; ?></td> 
			     <td><?php echo $lottery_fee; ?></td>
			     <td>
				<?php echo $this->MyHtml->spanLink(__('Proceed to Ticket Generation'), array('action' => 'random_ticket',$lottery_id,$ticket_id), array('style' => '')); ?>
			    </td>
			</tr>
		    <?php } ?>

		    <tr style="background:#fff;">
			<td colspan="4">
			     <?php echo $this->element('paginator'); ?>
			</td>
		    </tr>

            <?php } else { ?>
	
		   <tr>
		       <td colspan="5">
			   <?php echo __('There are no records'); ?>
		       </td>   
		   </tr>  

	   <?php } ?>

    </table>
    
</div>