<table class="marginTable default-table">
	<tr>				
	   <th class="itemsTab minwidth"><?php echo date('d M Y');?>&nbsp;LOTTERY WINNERS</th>
	   <th class="itemsTab minwidth"><?php echo $this->MyHtml->spanLink(__('Back'), array('action' => 'admin_drawlist'), array('style' => 'color:#fff;padding-left:20px;')); ?></th>
	</tr>
</table><br />

<?php if(!empty($winnerData)) { ?>     
    
    <?php foreach ($winnerData as $Key => $winners) { ?>
	
      <?php if(!empty($winners)) {

           $status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = '';

	   $lottery_id		= $winners['lt']['id'];
	   $name		= $winners['lt']['name'];
	   $num_lott_ball	= $winners['lt']['num_lott_ball'];
	   $lottery_fee		= $winners['lt']['lottery_fee'];
	   $draw_time		= $winners['lt']['draw_time'];
	   $prize_level		= $winners['lt']['prize_level'];
	   $logo		= $winners['lt']['logo'];
	   $ticket_id		= $winners['ltk']['id'];
	   $ticket_number	= $winners['ltk']['ticket_id'];
	   $stuff_ball		= $winners['ltk']['stuff_ball'];
	   $status		= $winners['ltk']['status'];
	   $lottery_type_name   = $winners['lty']['lottery_type'];
	   $win_ticket		= $winners['ltk']['win_ticket'];
	   if($stuff_ball=='') {
		$jackpotnumber   = '';
		$winticketnumber = implode('-',explode(',',$win_ticket));
	   } else if(isset($win_ticket) && $win_ticket!='') {				     
	       $jackpotnumber      = end(explode(',',$win_ticket));
	       $winticketnumberArr = explode(',',$win_ticket);
	       if(isset($winticketnumberArr)){
	       unset($winticketnumberArr[count($winticketnumberArr)-1]);
	       $winticketnumber   = implode('-',$winticketnumberArr);
	     }
	   }
	}
	?>

	<div id="items">

	     <?php echo $this->Form->create();?>

		 <table class="marginTable default-table">
		   <tr>				
		      <th class="itemsTab minwidth"><?php echo strtoupper($lottery_type_name);?>&nbsp;>>&nbsp;<?php echo strtoupper($name);?></th>
		    </tr>
		 </table>
	     
		<table class="marginTable default-table">

		<tr>
			<td>
			      <div id="" class="">
				<img class="logo" alt="<?php echo $logo;?>" src="/img/lottery/<?php echo $logo;?>" />
			      </div>
			</td>
			<td>	
				<div class="itemsTab minwidth">TICKET ID&nbsp;:&nbsp;<?php echo strtoupper($ticket_id);?></div>		
				<br/ >				
				<div class="itemsTab minwidth">STATUS&nbsp;:&nbsp;
				<?php 
			         if(isset($status) && $status==0){
				    echo 'NOT DRAWN';
			         } else if(isset($status) && $status==1){
				    echo 'WINNER';
			         } else if(isset($status) && $status==3){
				    echo 'CANCEL';
			         } else if(isset($status) && $status==4){
				    echo 'DELETED';
			         }
			         ?>				
			       </div>
			       <br/ >
			       <div class="itemsTab minwidth">
			       LOTTERY NUMBER&nbsp;:&nbsp;<?php if(isset($winticketnumber) && $winticketnumber!='') { echo $winticketnumber;} ?>
			       </div>
			       <br/ >
			       <?php if(isset($jackpotnumber) && $jackpotnumber!='') { ?>
				<div class="itemsTab minwidth">
				JACKPOT NUMBER&nbsp;:&nbsp;<?php if(isset($jackpotnumber) && $jackpotnumber!='') { echo $jackpotnumber;} ?>
			       </div>
			       <br/ >
			       <?php } ?>
			       <div class="itemsTab minwidth">LOTTERY FEE&nbsp;:&nbsp;<?php echo $lottery_fee.' '.$currency; ?>
			       </div>
			</td>		
		</tr>

		<tr>
		    <td>&nbsp;</td>
		    <td>
		       <?php echo $this->MyHtml->spanLink(__('Take Print'), array('action' => 'admin_action',$ticket_id,'print'), array('class' => 'button')); ?>
		       <?php
			 if(isset($status) && $status==0){
			   echo $this->MyHtml->spanLink(__('Cancel'), array('action' => 'admin_action',$ticket_id,'cancel'), array('class' => 'button'),'Do you really want to cancel the ticket?');
			 }
			?>  
		    </td>
		</tr>

		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		</table>

	     <?php echo $this->Form->end(); ?>

	</div>

    <?php } ?>

<?php } else { ?>
	<table><tr><td>&nbsp;</td><td>Today no lottery Ticket draw.</td></tr></table>
<?php } ?>