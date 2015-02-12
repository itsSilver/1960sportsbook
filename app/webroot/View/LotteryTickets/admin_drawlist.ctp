<table style="margin-bottom:15px" class="items">
   <tr>				
      <th class="fontbld font14">LOTTERY NUMBER DRAW WINNER LIST</th>
      <?php if(!isset($user_id)){?>
      <th><?php echo $this->MyHtml->spanLink(__('Click for Today draw '), array('action' => 'admin_lotterydraw'), array('class' => 'linkbutton')); ?></th>
      <?php } ?>
   </tr>
</table>

<?php if(!empty($winnerData)) { ?>
    
    <?php foreach ($winnerData as $windatekey => $winnersAll) { ?>
	   
	   <?php if(isset($windatekey) && !is_numeric($windatekey)){?>

	    <table class="items">
		<tr>				
		   <th class="fontbld font14"><?php if(isset($windatekey) && $windatekey!='') { echo date('d M Y H:i',strtotime($windatekey));} ?> </th>
		</tr>
	    </table>

	    <div id="items" class="pB10">

		  <?php echo $this->Form->create();?>

		     <table class="items">
	    
			    <tr>						
				<!-- <th><?php echo __('TICKET ID'); ?></th> -->
				<th><?php echo __('LOTTERY TYPE'); ?></th>
				<th><?php echo __('LOGO'); ?></th>
				<th><?php echo __('NAME'); ?></th>		
				<th><?php echo __('NUMBER OF BALL'); ?></th>	
				<!-- <th><?php echo __('DRAWN TIME'); ?></th> -->
				<th><?php echo __('LOTTERY NUMBER'); ?></th>
				<th><?php echo __('JACKPOT NUMBER'); ?></th>
				<th><?php echo __('ACTION'); ?></th> 
			    </tr>
	    
			    <?php foreach ($winnersAll as $lotteryidkey => $winners) { ?>
		
			      <?php if(!empty($winners)) {
			           $status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = '';

				   $lottery_id		= $winners['Lottery']['id'];
				   $lottery_name	= $winners['Lottery']['name'];
				   $num_lott_ball	= $winners['Lottery']['num_lott_ball'];
				   $draw_time		= $winners['Lottery']['draw_time'];
				   $logo		= $winners['Lottery']['logo'];
				   $ticket_id		= $winners['LotteryTicket']['id'];
				   $ticket_number	= $winners['LotteryTicket']['ticket_id'];
				   $stuff_ball		= $winners['LotteryTicket']['stuff_ball'];
				   $lottery_type_name   = $winners['Lottery']['lottery_type_name'];
				   $win_ticket		= $winners['LotteryTicket']['win_ticket'];
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
				<tr>
				    <!-- <td><?php echo $ticket_id; ?></td> -->
				    <td><?php echo $lottery_type_name; ?></td>
				    <td>
					<?php
					$imagepath= '/img/lottery/'.$logo;
					echo $this->MyHtml->image(''.$imagepath.'', array('alt' => ''.$logo.'','class'=>'logosmall')); ?>
				    </td>
				    <td><?php echo $lottery_name; ?></td>		    
				    <td><?php echo $num_lott_ball; ?></td>
				    <!-- <td><?php echo $draw_time; ?></td> -->
				    <td><?php if(isset($winticketnumber) && $winticketnumber!='') { echo $winticketnumber;} ?></td>
				    <td><?php if(isset($jackpotnumber) && $jackpotnumber!='') { echo $jackpotnumber;} else { echo '-';} ?></td>    
				    <td>
					<?php echo $this->MyHtml->spanLink(__('View'), array('controller' => 'LotteryTickets' , 'action' => 'admin_winnner',$lottery_id,$ticket_id), array('class' => '')); ?>			
				    </td>
				</tr>  
		            <?php } ?>
			    
		         </table>

	           <?php echo $this->Form->end(); ?>

	       </div>
		  
	    <?php } ?>

    <?php } ?>

    <?php if(isset($totalitemsPage) && isset($itemsPerPage) && $totalitemsPage > $itemsPerPage){?>
    <table>
	    <tr>
		<td colspan="8">
		    <?php echo $this->element('paginator'); ?>  
		</td>
	    </tr>
    </table> 
    <?php } ?>     

<?php } else { ?>
	<table><tr><td>&nbsp;</td><td>No history.</td></tr></table>
<?php } ?>