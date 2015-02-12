<?php
if(!empty($resultslotteryHeader) && !empty($resultslotteryInfo)) { ?>

  <!-- Mega Millions -->
  <div id="megaBottomDetailreturnData">

	  <div class="megaBottomDetail">
	     
	     <?php echo $this->Form->create('getpastresult'); ?>

	     <div class="topHeader">
		<h3>
		   <?php
		   if (isset($lotteryidKey)) { echo $resultslotteryHeader[$lotteryidKey];} ?>
		</h3>
		<span> 
			Change Game To:&nbsp;&nbsp;
			<select id="select_lottery_result_option" class="" name="data[lottery_id]">
			   <?php foreach($resultslotteryHeader as $key => $value){?>      
			      <option <?php if($lotteryidKey == $key){echo "selected='true'";}?> value="<?php echo $key;?>"><?php echo $value;?></option>		      
			   <?php } ?>
			</select>
			<script type="text/javascript">
				jQuery(document).ready(function(){
				   jQuery('#select_lottery_result_option').change(function(e){
					e.preventDefault();				
					var lotteryid = jQuery('#select_lottery_result_option').val();		
					if(lotteryid !='' && lotteryid !='0'){ 
						jQuery("#loader").html('Loading Please wait...').show();	
						jQuery.ajax({
						    type: "POST",
						    data: jQuery("#getpastresultGetPastlotteryresultForm").serialize(),
						    url : "/lotterys/get_pastlotteryresult",	
						    success: function(msg){	
							jQuery("#loader").html('').hide();	
							jQuery("#megaBottomDetailreturnData").html(msg);
						    }
						});
					}
				    });	
				});	
			</script>
		</span>		
	    </div>
	    
	    <div class="megacontent">
		<div class="megaNav">
			<ul>
			    <li>Winning Number</li>
			    <li id="loader" style="background:#FFF;float:right;font-weight:bold;display:none;"></li>
			</ul>
		</div>
		<div class="magalayout-content">
			<div class="subtitle">
				Get the details on the digits.
				<a href="#" class="back">back</a>
				<br />
				<p>
				  Select a time period and then enter your favrite combination to find out how many times your individual numbers have been drawn
				</p>
			</div>
			
			<div class="megaSelectRange">
				<table>
				      <tr>
					<td>
					     select Date Range:&nbsp;&nbsp; 
					     <?php echo $this->Form->input('yearFrom', array('div'=>false,'label' => false, 'type' => 'select', 'options' => $yearData, 'class' => '','id' => 'yearFrom','style'=>'','selected' => $yearFrom));?>
					     
					     <?php echo $this->Form->input('monthFrom', array('div'=>false,'label' => false, 'type' => 'select', 'options' => $monthArray, 'class' => '','id' => 'monthFrom','style'=>'','selected' => $monthFrom));?>
					</td>
					<td width="30">
						TO
					</td>
					<td>			      
					     <?php echo $this->Form->input('yearTo', array('div'=>false,'label' => false, 'type' => 'select', 'options' => $yearData, 'class' => '','id' => 'yearTo','style'=>'','selected' => $yearTo));?>
					     
					     <?php echo $this->Form->input('monthTo', array('div'=>false,'label' => false, 'type' => 'select', 'options' => $monthArray, 'class' => '','id' => 'monthTo','style'=>'','selected' => $monthTo));?>				
					     &nbsp;&nbsp;
					     <input id="select_month_year" type="submit" value="GO" class="button" >
					     
					     <script type="text/javascript">
						jQuery(document).ready(function(){
						   jQuery('#select_month_year').click(function(e){
							e.preventDefault();				
							var yearFrom  = jQuery('#yearFrom').val();
							var monthFrom = jQuery('#monthFrom').val();
							var monthTo   = jQuery('#monthTo').val();
							var yearTo    = jQuery('#yearTo').val();
							var lotteryid = jQuery('#select_lottery_result_option').val();
							if(lotteryid !='' && lotteryid !='0' && yearFrom !='00' && monthFrom !='00' && monthTo !='00' && yearTo !='00'){ 
								jQuery("#loader").html('Loading Please wait...').show();	
								jQuery.ajax({
								    type: "POST",
								    data: jQuery("#getpastresultGetPastlotteryresultForm").serialize(),
								    url : "/lotterys/get_pastlotteryresult",	
								    success: function(msg){	
									jQuery("#loader").html('').hide();	
									jQuery("#megaBottomDetailreturnData").html(msg);
								    }
								});
							}
						    });	
						});	
					     </script>

					</td>
				      </tr>
				</table>
			</div>
			<?php
			if(!empty($resultslotteryInfo)) {
			if (isset($lotteryidKey)) {
			   $stuff_ball       = $resultslotteryInfo[$lotteryidKey]['stuff_ball'];
			   if($stuff_ball=='') { 
			      $num_lott_ball = $resultslotteryInfo[$lotteryidKey]['num_lott_ball'];
			   } else {
			      $num_lott_ball = $resultslotteryInfo[$lotteryidKey]['num_lott_ball']-1;
			   }			   
			}
			?>

			<table class="megaSelectNumber">
				<tr>
					<td>
						Select Number: <br /><br />
						<?php for($ball=1;$ball <=($num_lott_ball);$ball++) { ?>
						<input type="text" name="ticket_id[]" value="">
						<?php } ?>
						
					</td>
					<td>  
						<?php if(isset($stuff_ball) && $stuff_ball!='') { ?>
					            <?php 
						    if (isset($lotteryidKey)) {
						    $stuffballArray = explode(' ',$resultslotteryHeader[$lotteryidKey]);if(isset($stuffballArray[0])){ echo $stuffballArray[0]; } }
						    ?> BALL:<br /><br />			        
						    <input id="stuff_ball" type="text" name="stuff_ball" value="">
						<?php } ?>
					</td>
					<td><br /><br />
						<input id="select_ball_stuff" type="submit" value="GO" class="button" >
						<!-- &nbsp;&nbsp;<a href="#" class="button" >+ ADD LINE</a>-->
					</td>
					<script type="text/javascript">
						jQuery(document).ready(function(){
						   jQuery('#select_ball_stuff').click(function(e){
							e.preventDefault();	
							var lotteryball = jQuery('input[name="ticket_id[]"]').val();
							var stuffball = jQuery('#stuff_ball').val();
							var lotteryid = jQuery('#select_lottery_result_option').val();
							if((lotteryid!='' && lotteryid !='0' && stuffball!='' && stuffball!='NULL') || (lotteryball!='' && lotteryid !='' && lotteryid !='0')){ 
								jQuery("#loader").html('Loading Please wait...').show();	
								jQuery.ajax({
								    type: "POST",
								    data: jQuery("#getpastresultGetPastlotteryresultForm").serialize(),
								    url : "/lotterys/get_pastlotteryresult",	
								    success: function(msg){	
									jQuery("#loader").html('').hide();	
									jQuery("#megaBottomDetailreturnData").html(msg);
								    }
								});
							}
						    });	
						});	
					     </script>
				</tr>
			</table>
			<?php } ?>
		    </div>
		    
		    <div class="megatableBottom">
			 
			 <table>					
				<thead>
				   <tr>
				     <th>DATE</th>
				     <th>WINNING NUMBERS DRAWN</th>
				     <th><?php if (isset($lotteryidKey)) { $stuffballArray = explode(' ',$resultslotteryHeader[$lotteryidKey]);if(isset($stuffballArray[0])){ echo $stuffballArray[0]; } } ?> BALL</th>
				   </tr>
				</thead>
				
				<tbody>
				   
				   <?php if(!empty($resultslotteryTable)) { ?>

				     <?php foreach($resultslotteryTable as $keydata => $valueData){ ?>
				      
				      <?php if(isset($keydata) && is_numeric($keydata)) { ?>
					<tr>
						<td>
						   <?php
						   $windate = $valueData['win_date'];
						   echo $this->MyHtml->spanLink(__(''.$windate.''), array('controller' => 'LotteryTickets','action' => 'payout',$valueData['ticket_id']), array('class' => '')); ?>					
						</td>						
						<td>
						   <?php
						   $jackpotnumber = $winticketnumber ='';
						   if($valueData['stuff_ball'] =='') {
							$win_ticket = $valueData['win_ticket'];
							$jackpotnumber     = '';
							$winticketnumber   = implode('. ',explode(',',$win_ticket));	
						   } else if($valueData['win_ticket']!='') {
							$win_ticket = $valueData['win_ticket'];
							$jackpotnumber      = end(explode(',',$win_ticket));
							$winticketnumberArr = explode(',',$win_ticket);
							if(isset($winticketnumberArr)){
							unset($winticketnumberArr[count($winticketnumberArr)-1]);
							$winticketnumber    = implode('. ',$winticketnumberArr);
							}
						   }
					
					           if(isset($winticketnumber)) { 
						        echo $winticketnumber;
						   }
						   ?>
						<td>
						     <?php if(isset($jackpotnumber) && $jackpotnumber!=''){
						       echo $jackpotnumber;
						     } else {
						       echo '-';
						     }
						     ?>
						</td>							
					</tr>

				      <?php } ?>		    

				     <?php } ?>
				 <?php } else { ?>					
					<tr>
					        <td></td>						
						<td>No records founds.</td>				
						<td></td>						
					</tr>
				 <?php }  ?>

				 <?php if(isset($totalRows) && $totalRows > 5){?>
				 <tfooter>
				   <tr>
				     <td>&nbsp;</td>
				     <td>
					<div style="text-align:center;">			
					    <ul>
						<?php
						if(isset($startpage) && $startpage != 1) 
						{?>
						     <li class="arrowL">
							  <a class="" href="javascript:;" onclick="javascript: return lotteryPagination('<?php echo $startpage-1;?>');">prev</a>
						     </li>
						<?php
						}

						if(!isset($startpage) || $startpage != $pages) 
						{?>
						    <li class="arrowR">
							<a class="" href="javascript:;" onclick="javascript: return lotteryPagination('<?php echo $startpage+1;?>');">Next</a>
						    </li>
						<?php	
						}							
						?>
					     </ul>
					     <script type="text/javascript">
						function lotteryPagination(page) {
						    jQuery("#loader").html('Loading page...').show();
						    var lottery_id = jQuery('#select_lottery_result_option').val();
						    if(lottery_id!='' && lottery_id!='0'){
							    jQuery.ajax({
								type: "POST",
								data: "page="+page+"&lottery_id="+lottery_id+jQuery("#getpastresultGetPastlotteryresultForm").serialize(),
								url : "/lotterys/get_pastlotteryresult",	
								success: function(msg){
								   jQuery("#loader").html('').hide();
								   jQuery("#megaBottomDetailreturnData").html(msg);
								}
							    });
						    } else {
						         return false;
						    }
						}
					     </script>
					</div> 
				     </td>
				     <td>&nbsp;</td>
				   </tr>
				</tfooter>
			     <?php } ?>	
			</table>

		    </div>   
		   
	      </div>

	      <?php echo $this->Form->end(); ?>

	</div>
</div>

<?php } ?>