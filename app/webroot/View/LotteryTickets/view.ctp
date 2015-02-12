<?php
$this->groupid = $this->Session->read('Auth.User.group_id');
$win_ticket = $win_date = $draw_date = $player = $status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = $jackpotnumber = $matchticketnumber = $winticketnumber = '';
?>

<div id="items">

        <?php echo $this->Form->create();?>

        <?php if(isset($this->params['pass'][1]) && $this->params['pass'][1]=='search'){?>.
	<h3>
	    Search Result
	    <?php echo $this->MyHtml->spanLink(__('Back'), array('controller' => '/'), array('class' => 'fontbld right')); ?>
	</h3>
	<?php } else { ?>
	<h3>
	    Ticket Detail
	    <?php echo $this->MyHtml->spanLink(__('Back'), array('controller' => 'lotterys', 'action' => 'view',$lottery_id,'about'), array('class' => 'right')); ?>
	</h3>
	<?php } ?>
	<br />

	<table class="marginTable default-table">

	<?php if(!empty($data)) {
		
		$lottery_id		= $data['Lottery']['id'];
		$name			= $data['Lottery']['name'];
		$num_lott_ball		= $data['Lottery']['num_lott_ball'];
		$lottery_fee		= $data['Lottery']['lottery_fee'];
		$draw_time		= $data['Lottery']['draw_time'];
		$prize_level		= $data['Lottery']['prize_level'];
		$logo			= $data['Lottery']['logo'];
		$ticket_id		= $data['LotteryTicket']['id'];
		$ticket_number		= $data['LotteryTicket']['ticket_id'];
		$stuff_ball		= $data['LotteryTicket']['stuff_ball'];
		$status			= $data['LotteryTicket']['status'];
		$lottery_type_name	= $data['Lottery']['lottery_type_name'];
		$win_ticket		= $data['winData']['win_ticket'];
		$draw_date		= $data['winData']['draw_date'];
		$player			= $data['User']['username'];
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
		?>
	
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
		
		<!-- *********************** User Lottery Detail ************************************** -->
		
		<tr>
		   <td colspan="2">
			 <label class="itemsTab"><?php echo __('User Lottery Detail'); ?></label>
		   </td>
		</tr>	
		
		<tr>
		   <td>
			 <label><?php echo __('Lottery Player'); ?></label>
		   </td>
		   <td>
			<label><?php if(isset($player)) { echo $player; } ?></label>
		   </td>
		</tr>	
		
		<tr>
		   <td>
			 <label><?php echo __('Lottery Number'); ?></label>
		   </td>
		   <td>
			<label><?php if(isset($ticket_number)) { echo implode('-',explode(',',$ticket_number));} ?></label>
		   </td>
		</tr>	
		
		<?php if(isset($stuff_ball) && $stuff_ball!=''){?>
		<tr>
		    <td>
			<label><?php echo __(''.$lottery_type_name.' Ball'); ?></label>
		   </td>
		   <td>
			<label><?php echo $stuff_ball; ?></label>
		   </td>
		</tr>	
		<?php } ?>
		
		<tr>
		    <td>
			<label><?php echo __('Lottery Fee'); ?></label>
		   </td>
		   <td>
			<label><?php echo $lottery_fee.' '.$currency; ?></label>
		   </td>
		</tr>	
		
		<tr>
		    <td>
			<label> Status</label>
		   </td>
		   <td>
		       <label>
		       <?php 
		       if(isset($status) && $status==0){
			  echo '<span class="fontbld">Not drawn</span>';
		       } else if(isset($status) && $status==1){
			  echo 'Drawn';
		       } else if(isset($status) && $status==2){
			  echo 'Drawn';
		       } else if(isset($status) && $status==3){
			  echo 'Cancel';
		       } else if(isset($status) && $status==4){
			  echo 'Deleted';
		       }
		       ?>
		       </label>
		    </td>
		</tr>	
		
		<?php if(isset($data['winData']['win_ticket']) && $data['winData']['win_ticket']!='') { ?>		
		<tr>
		    <td>
			<label><?php echo __('Lottery Number Status'); ?></label>
		   </td>
		   <td>
			<label>
			<?php
			if($matchticketnumber==''){
				echo '<span class="">Not drawn</span>';
			} else if($ticket_number == $matchticketnumber) {	
				echo '<span class="colorgreen">Winner</span>';
			} else if($ticket_number != $matchticketnumber) {
				echo '<span class="colorred">Lost</span>';
			}
			?>
			</label>
		   </td>
		</tr>	
		
		<tr>
		    <td>
			<label><?php echo __('STUFF Ball Status'); ?></label>
		   </td>
		   <td>
			<label>
			<?php
			if($stuff_ball=='') {
			   echo 'Not available';
			} else if($jackpotnumber==''){
			    echo '<span class="">Not drawn</span>';
			} else if($stuff_ball == $jackpotnumber) {
			   echo '<span class="colorgreen">Winner</span>';
			} else if($stuff_ball != $jackpotnumber) {
			    echo '<span class="colorred">Lost</span>';
			}
			?>
			</label>
		   </td>
		</tr>
		<?php } ?>	
		<!-- *********************** /User Lottery Detail ************************************** -->

		<!-- *********************** Drawn Lottery Detail ************************************** -->
		<?php if(isset($data['winData']['win_ticket']) && $data['winData']['win_ticket']!='') { ?>
		<tr>
		   <td colspan="2">
			 <label class="itemsTab"><?php echo __('Winning Lottery Detail'); ?></label>
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
		
		<?php } ?>

		<!-- ************ /Drawn Lottery Detail ************************************** -->
		
		<tr>
		    <td>&nbsp;</td>
		    <td>
		       <?php echo $this->MyHtml->spanLink(__('Print'), array('action' => 'ticket_print',$ticket_id,'print'), array('class' => 'button')); ?>
		       <?php echo $this->MyHtml->spanLink(__('Back'), array('action' => 'tickets',$lottery_id,'history'), array('class' => 'button')); ?>
		    </td>
		</tr>
			
		<?php } else { ?>

		<tr style="background:#FFF;"><td colspan="2"></td></tr>
	        
		<tr>
		    <td colspan="2">No record found</td>
		</tr>

	   <?php } ?>	
	
	</table>

     <?php echo $this->Form->end(); ?>

</div>