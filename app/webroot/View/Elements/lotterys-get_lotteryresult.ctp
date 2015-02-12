<?php $mainDataArray = $this->requestAction('lotterys/get_lotteryresult/');?>
<?php if(!empty($mainDataArray)){ ?>
	<!-- Home Table winner -->				
	<div class="HomeTablewinner">	
		<h1>Hey, you never know. <span>&reg;</span> </h1>
		
		<p class="subtitle">Check the winning numbers below:
		  <?php 
		  $past_var_anchor = "Past winning Numbers<span>&reg;</span>";
		  echo $this->MyHtml->spanLink(__($past_var_anchor), array('controller' => 'LotteryTickets', 'action' => 'drawlist'), array('style' => '')); ?>
		</p>

		<?php if(!empty($mainDataArray)){ ?>

		   <?php foreach($mainDataArray as $lotteryidkey => $mainData){ 
			   
			   $win_ticket = $win_date = $draw_date = $player = $status = $ticket_number = $stuff_ball =  $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $prize_level = $jackpotnumber = $matchticketnumber = $winticketnumber = '';

			   $lottery_id		= $mainData['Lottery']['id'];
			   $name		= $mainData['Lottery']['name'];
			   $num_lott_ball	= $mainData['Lottery']['num_lott_ball'];
			   $lottery_fee		= $mainData['Lottery']['lottery_fee'];
			   $draw_time		= $mainData['Lottery']['draw_time'];
			   $prize_level		= $mainData['Lottery']['prize_level'];
			   $logo		= $mainData['Lottery']['logo'];
			   $lottery_type_name   = $mainData['Lottery']['lottery_type_name'];
			   $ticket_id		= $mainData['LotteryTicket']['id'];
			   $ticket_number	= $mainData['LotteryTicket']['ticket_id'];
			   $stuff_ball		= $mainData['LotteryTicket']['stuff_ball'];
			   $status		= $mainData['LotteryTicket']['status'];			   
			   $win_ticket		= $mainData['LotteryTicket']['win_ticket'];
			   $draw_date		= $mainData['LotteryTicket']['draw_date'];
			   $win_date		= $mainData['LotteryTicket']['win_date'];
			   $player		= $mainData['User']['username'];
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

			<!-- REPEATING DIV -->
			<div class="MegaMillionbox">
			      <div class="leftSidearrow">
			            <?php
				    $payout= 'payout >';
				    echo $this->MyHtml->spanLink(__($payout), array('controller' => 'LotteryTickets','action' => 'payout',$ticket_id), array('class' => 'payout')); ?>
				    <?php if(isset($logo) && $logo!='') {?>
					<img class="logomedium" src="/img/lottery/<?php echo $logo;?>" />
				    <?php } else { ?>
					<img class="logomedium" src="img/logoMega.png" />
				    <?php } ?>
			      </div>
			      <div class="centerDiv">
				   <div class="drawings_title"><?php echo date('M d Y',strtotime($win_date));?>, Winning Numbers</div>
				   <div class="mm_numbers">
					<?php				
					if(isset($winticketnumber)) { echo $winticketnumber;} ?>
					<?php if(isset($stuff_ball) && $stuff_ball!='') {?>
					<div class="mm_powerball">
					   <?php				   
					   if(isset($name)) {
					      $stuffballArray = explode(' ',$name);
					      if(isset($stuffballArray[0])){ echo $stuffballArray[0];}		   
					   } 
					   ?>: <span><?php if(isset($jackpotnumber)) { echo $jackpotnumber;}?></span>		   
					</div>
					<?php } ?>
				   </div>
				   <div class="mm_nextdrawing">
					<span>NEXT DRAWING:</span> <?php if(isset($draw_time)) { echo date('d M Y',strtotime($draw_time)); } ?> 
					<!-- <span>NEXT JACKPOT:</span> $122 MILLION -->
				   </div>
			      </div>
			</div>
			<!-- /REPEATING DIV -->

		   <?php } ?>

		<?php } else { ?>
		       <div class="MegaMillionbox">No records yet.</div>
		<?php } ?>

	</div>
	<!-- Home Table winner -->
<?php } ?>
