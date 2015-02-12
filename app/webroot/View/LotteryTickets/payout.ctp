<?php
$this->groupid = $this->Session->read('Auth.User.group_id');
$win_date = $status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = $jackpotnumber = $matchticketnumber = $winticketnumber = '';

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
   $win_date		= $data['winData']['win_date'];
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

<div class="sliders">
    <?php echo $this->element('slider'); ?>
</div>

<div class="lotteryNewsDetail" style="width:500px;">
     
     <h2>Lottery News</h2>

     <div class="lotteryNewsDetail-content">

	<div class="title">
		Drawing Result
		<?php echo $this->MyHtml->spanLink(__('Back to all games'), array('controller' => '/'), array('class' => 'back')); ?>
	</div>

	<div class="content100">
		<div class="chnglotteryName">
		   <?php echo $this->MyHtml->spanLink(__($name), array('controller' => 'lotterys','action' => 'view',$lottery_id,'about'), array('style' => 'color:#FFF;font-size:12px;')); ?>
		</div>
		<div class="chnglotteryName">
			<img style="width:80px;height:80px;border:2px solid #000000;" alt="<?php echo $logo;?>" src="/img/lottery/<?php echo $logo;?>" />
		</div>
		<br class="clear">
		<p>
		    <strong>Web Site</strong>:
		    <?php echo $this->MyHtml->spanLink(__('1960sportsbook.com'), array('controller' => '/'), array('style' => 'color:#FFF;font-size:14px;')); ?>
		</p>
		<br />
		<p>
		   <?php echo date('M d, Y',strtotime($win_date)); ?><br />
		   <?php echo __($name); ?> &nbsp;Winning Number : <?php echo $winticketnumber; ?>
		   <br />
		   <?php
		   if(isset($lottery_type_name)) {
		   $stuffballArray = explode(' ',$lottery_type_name);
		   if(isset($stuffballArray[0])){ echo $stuffballArray[0];}		   
		   }
		   ?>
		   Winning Number : 
		   <?php
		   if($jackpotnumber!='')  { echo $jackpotnumber;
		   } else { echo 'no ball'; } ?>
		</p>
	</div>

	<div class="customerTable">

		<table>
			<tbody>
				<tr>
					<th>PRIZE LEVEL</th>
					<th>NUMBERS MATCHED</th>
					<th>PRIZE AMOUNT</th>
					<th>WINNERS</th>
				</tr>

				<?php if(!empty($data['prize_count'])) { ?>

				     <?php foreach($data['prize_count'] as $keydata => $valueData){ ?>
				     
					<tr>
					     <td><?php echo $keydata;?></td>	
					     <td><?php echo $valueData['level_number'];?></td>
					     <td><?php echo $valueData['prize_amount'];?></td>
					     <td><?php echo $valueData['ticket_count'];?></td>
					</tr>				  

				    <?php } ?>

				<?php } ?>
				
		        <tbody>
	     </table>

	</div>

    </div>

</div>