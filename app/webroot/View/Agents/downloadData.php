<page backtop="8mm" backbottom="8mm" backleft="2mm" backright="20mm">
    <page_header>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;width: 33%"></td>
                <td style="text-align: center;width: 34%"></td>
                <td style="text-align: right;width: 33%"><?php //echo date('d/m/Y'); ?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table style="width: 100%;">
            <tr>
                 <td style="text-align: left;width: 33%"></td>
				 <td style="text-align: center;color: #2E6D8D;width:34%">WWW.1960SPORTSBOOK.COM</td>
                <td style="text-align: right;width: 33%">page [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
    </page_footer>

	<?php
	$file_name ='league_schudule';
	$downloadDetail      = array();
	$downloadDetailArray = $this->Event->allleagueEvents($leagueid ,$type);

	if(!empty($downloadDetailArray)){			
		foreach ($downloadDetailArray as $downloadkey => $downloads) {
			$downloadDetail[$downloads['l']['league_name']][] = $downloads['e'];
		}	
	}

	//echo '<pre>';print_r($downloadDetail);echo '</pre>';die;

	if(!empty($downloadDetail)){			
		list($file_name_raw) = array_keys($downloadDetail);
		$file_name           = trim(strtoupper($file_name_raw));
		?>
		
		<table  border="1" style="word-wrap: break-word;font-family:arial;color:#000; border-collapse:collapse; background-color: #ffffff;">
		
		<?php foreach ($downloadDetail as $downloadDetailkey => $download) {?>	
				
				<tr>
					<td  colspan="2" style="font-weight:bold;font-size:12pt;background-color:#002433;color:#FFFFFF;width:300pt;padding:8pt 5pt;text-align:left;border:0pt none;"><?php echo strtoupper($downloadDetailkey);?></td>
					<td  colspan="2" style="font-weight:bold;font-size:10pt;background-color:#002433;color:#fff;width:150pt;padding:8pt 5pt;text-align:right;border:0pt none;" ></td>
				</tr>
				<tr>
					<td style="width:100pt;padding:8pt;background-color:#000000;color:#FFFFFF;">EVENT ID</td>
					<td style="width:200pt;padding:8pt 5pt;background-color:#000000;color:#FFFFFF;">EVENT NAME</td>
					<td style="width:100pt;padding:8pt 5pt;background-color:#000000;color:#FFFFFF;">EVENT TIME</td>
					<td style="width:50pt;padding:8pt 5pt;background-color:#000000;color:#FFFFFF;">RESULT</td>						
				</tr>
		

			<?php if(!empty($download)) { ?>				
				
					<?php
					$color = 1;
					foreach ($download as $downloadinnerkey => $downloadinnerData) {

						if($color % 2 ==0){
							$colorClass='background-color:#E1F4FD;';
						} else {
							$colorClass='';
						}

						$eventArray=explode(' ',$downloadinnerData['name']);
						$eventname  = trim($downloadinnerData['name']);

						if(isset($eventArray[0]) && isset($eventArray[1])) {
							$team_first  = trim($eventArray[0]);
							$team_second = trim($eventArray[1]);
						} else {
							$team_first   = $downloadinnerData['name'];
							$team_second  = 'Comming';
						}
						if(!empty($downloadinnerData['date'])) {						
							$time = date('d M Y , h:m',strtotime($downloadinnerData['date']));
						} else {
							$time = '--';
						}

						if(!empty($downloadinnerData['result'])) {						
							$result = $downloadinnerData['result'];
						} else {
							$result = '--';
						}							
						?>					
						 <tr style="font-weight:bold;<?php echo $colorClass;?>">
							<td style="border-right:0px;width:100pt;padding:8pt;color:#000000;"><?php echo $downloadinnerData['id'];?></td>
							<td style="border-right:0px;width:200pt;padding:8pt 5pt;color:#000000;"><?php echo strtoupper($eventname);?></td>
							<td style="border-right:0px;width:100pt;padding:8pt 5pt;color:#000000;"><?php echo $time;?></td>
							<td style="width:50pt;padding:8pt 5pt;color:#000000;"><?php echo strtoupper($result);?></td>						
						</tr>						

				<?php 
				$color ++;
				}
				?>

			<?php } ?>

		<?php } ?>

	  </table>

	<?php } ?>
		
</page>