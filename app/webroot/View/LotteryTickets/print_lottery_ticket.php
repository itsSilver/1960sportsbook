<page backtop="8mm" backbottom="8mm" backleft="2mm" backright="20mm">
    <page_header>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;width: 33%"></td>
                <td style="text-align: center;color: #2E6D8D;width:34%">WWW.1960SPORTSBOOK.COM</td>
                <td style="text-align: right;width: 33%;color: #2E6D8D;"><?php echo date('d/m/Y'); ?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table style="width: 100%;">
            <tr>
                 <td style="text-align: left;width: 33%"></td>
				 <td style="text-align: center;color: #2E6D8D;width:34%">WWW.1960SPORTSBOOK.COM</td>
                <td style="text-align: right;width: 33%;color: #2E6D8D;">page [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
    </page_footer>

	<?php
	$file_name = $win_ticket = $win_date = $draw_date = $player = $status = $ticket_number = $stuff_ball = $is_active = $is_stuff = $logo = $ticket_id = $lottery_id = $name   = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = '';	
	$download = $this->Session->read('printedData');
	if(!empty($download)){		
		$lottery_id			= $download['Lottery']['id'];
		$name				= $download['Lottery']['name'];
		$num_lott_ball		= $download['Lottery']['num_lott_ball'];
		$lottery_fee		= $download['Lottery']['lottery_fee'];
		$draw_time			= $download['Lottery']['draw_time'];
		$prize_level		= $download['Lottery']['prize_level'];
		$logo				= $download['Lottery']['logo'];
		$currency		    = $download['Lottery']['currency'];
		$ticket_id			= $download['LotteryTicket']['id'];
		$ticket_number		= $download['LotteryTicket']['ticket_id'];
		$stuff_ball			= $download['LotteryTicket']['stuff_ball'];
		$status				= $download['LotteryTicket']['status'];
		$lottery_type_name  = $download['Lottery']['lottery_type_name'];
		$file_name			= strtolower($name.'_ticketid_'.$ticket_id);
		$win_ticket			= $download['winData']['win_ticket'];
		$win_date			= $download['winData']['win_date'];
	    $draw_date			= $download['winData']['draw_date'];
		$player		        = $download['User']['username'];
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
		
		<table style="word-wrap:break-word;font-family:arial;color:#000;background-color:#E4E5E6;border:1pt solid #000;margin:auto;">

				<tr style="font-weight:bold;">
					<td style="border-right:0px;width:250pt;padding:5pt;color:#000000;"><img style="width:80px;height:80px;border:2px solid #000000;" alt="<?php echo $logo;?>" src="<?php echo WWW_ROOT;?>/img/lottery/<?php echo $logo;?>" /></td>
					<td style="border-right:0px;width:250pt;padding:5pt 5pt;color:#000000;">
					
						<div style="font-weight:bold;font-size:10pt;background-color:#003366;color:#FFFFFF;width:250pt;padding:4pt 3pt;text-align:left;border:0pt none;">TICKET ID&nbsp;:&nbsp;<?php echo strtoupper($ticket_id);?></div>
						<br/ >
						<div style="font-weight:bold;font-size:10pt;background-color:#003366;color:#FFFFFF;width:250pt;padding:4pt 3pt;text-align:left;border:0pt none;"><?php echo strtoupper($lottery_type_name);?></div>
						<br/ >
						<div style="font-weight:bold;font-size:10pt;background-color:#003366;color:#FFFFFF;width:250pt;padding:4pt 3pt;text-align:left;border:0pt none;"><?php echo strtoupper($name);?></div>
					</td>		
				</tr>

				<!-- *********************** User Lottery Detail ************************************** -->

				<tr style="font-weight:bold;">
					<td style="font-weight:bold;font-size:9pt;background-color:#003366;color:#FFFFFF;width:250pt;padding:2pt 2pt;text-align:left;border:0pt none;" colspan="2">
						User Lottery Detail
					</td>
				</tr>

				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">Lottery Player</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;"><?php if(isset($player)) { echo $player;} ?></td>		
				</tr>	

				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">Lottery Number</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;"><?php if(isset($ticket_number)) { echo implode('-',explode(',',$ticket_number));} ?></td>		
				</tr>				

				<?php if(isset($stuff_ball) && $stuff_ball!='') {?>
				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;"><?php echo $lottery_type_name;?> Ball</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;"><?php if(isset($stuff_ball)) { echo $stuff_ball;} else { echo 'None';} ?></td>		
				</tr>
				<?php } ?>

				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">Lottery Fee</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;"><?php echo $lottery_fee; ?>
					<?php //echo $lottery_fee.' '.$currency; ?></td>		
				</tr>

				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">Lottery Status</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;">
					    <?php 
						if(isset($status) && $status==0){
							echo 'Not drawn';
						} else if(isset($status) && ($status==1 || $status==2)){
							echo 'Drawn';
						} else if(isset($status) && $status==3){
							echo 'Cancel';
						} else if(isset($status) && $status==4){
							echo 'Deleted';
						}
					    ?>
					</td>		
				</tr>

			   <?php if(isset($download['winData']['win_ticket']) && $download['winData']['win_ticket']!='') { ?>

				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">
						Lottery Number Status
					</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;">						
						<?php
						if($matchticketnumber==''){
							echo '<span class="">Not drawn</span>';
						} else if($ticket_number == $matchticketnumber) {	
							echo '<span class="colorgreen">Winner</span>';
						} else if($ticket_number != $matchticketnumber) {
							echo '<span class="colorred">Lost</span>';
						}
						?>
					</td>
				</tr>

				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">
						STUFF Ball Status
					</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;">
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
					</td>
				</tr>

			<?php } ?>

			<!-- *********************** /User Lottery Detail ************************************** -->

			<!-- *********************** Drawn Lottery Detail ************************************** -->

			<?php if(isset($download['winData']['win_ticket']) && $download['winData']['win_ticket']!='') { ?>
				<tr style="font-weight:bold;">
					<td style="font-weight:bold;font-size:9pt;background-color:#003366;color:#FFFFFF;width:250pt;padding:2pt 2pt;text-align:left;border:0pt none;" colspan="2">Winning Lottery Detail</td>
				</tr>

				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">Drawn Date
					</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">
						<?php echo date('d M Y h:i',strtotime($draw_date)); ?>
					</td>
				</tr>

				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">
						Lottery Number
					</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;">
						<?php echo $winticketnumber; ?>
					</td>
				</tr>

				<?php if(isset($jackpotnumber) && $jackpotnumber!=''){?>
				<tr style="font-weight:bold;">
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt;color:#000000;">
						<?php echo $lottery_type_name.' Ball'; ?>
					</td>
					<td style="font-size:8pt;border-right:0px;width:200pt;padding:5pt 5pt;color:#000000;">
						<?php echo $jackpotnumber; ?>
					</td>
				</tr>

				<?php } ?>

			<?php } ?>

			<!-- *********************** /Drawn Lottery Detail ************************************** -->

	  </table>

	<?php } ?>
		
</page>