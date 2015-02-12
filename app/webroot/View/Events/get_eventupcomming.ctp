<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

<?php if (!empty($eventData)) {?>

   <div class="box">
	
	<?php foreach ($eventData as $eventDataKey => $eventDataDetail) { ?>
		<?php if (isset($eventDataKey) && $eventDataKey == 'navigation') { ?>
			
		     <?php if (!empty($eventDataDetail)) { ?>
			<div class="eventHead clearfix">
				<div class="headTitle">
					<div id="nextEventsDiv">
					   <div style="text-align: right;">
					     <span class="tableHeadTitle">Upcoming events</span>

					      <ul class="sportTabs">
						  <?php foreach ($eventDataDetail as $navigationKey => $navigationName) { 
						  list($firstDiv) = array_keys($eventDataDetail);
						  ?>
						      <li>
							<a id="addactiveTab_<?php echo $navigationKey;?>" href="javascript: selectNavigation('<?php echo $navigationKey;?>')" class="removedtab"><span style="font-size: 9px;"><?php echo $navigationName;?></span></a>
						      
							<script type="text/javascript">
							 jQuery(document).ready(function(){	  
							   jQuery("#eventBody_<?php echo $firstDiv;?>").show();
							   jQuery("#addactiveTab_<?php echo $firstDiv;?>").addClass('active');
							 });
							</script>
						     </li>						      
						   <?php } ?>
					      </ul>
				       </div>
				   </div>
				</div>
			  </div>
		    <?php } ?>

		<?php } ?>

		<?php if (isset($eventDataKey) && $eventDataKey == 'events') { ?>

			<?php if (!empty($eventData['events'])) { ?>			
		
			     <?php foreach ($eventData['events'] as $eventDataKey => $eventDataDetail) { ?>

				   <div id="eventBody_<?php echo $eventDataKey;?>" class="alleventdivclass block" style="display:none;">
					<center>
					<table cellspacing="0" cellpadding="0" border="0" align="center">

					   <tbody>
						
					       <?php foreach ($eventDataDetail as $Key => $eventallUp) { ?>

					       <tr style=" background-color:#6396C9;">

						<td align="center" style="border-bottom: solid 2px #FEFEFE;width:23%;">
						    <center><span><?php echo date('d/m/Y, h:m A',strtotime($eventallUp['event_date']));?></span></center>
						</td>					

						<td height="28" style="width:53%;padding-top:10px;color:#ffff !important;border-bottom: solid 2px #FEFEFE;text-align:left;padding-left:0.6em;">
						   <?php echo $this->Html->link(ucwords(strtolower($eventallUp['event_name'])), array('controller' => 'events', 'action' => 'view', $eventallUp['event_id']),array('style'=> 'color:#fff')); ?>
						</td>

						<?php if (!empty($eventallUp['bet_part_odd_up'])) { ?>	

						   <?php						   
						   $eventallUpOut = array_unique($eventallUp['bet_part_odd_up']);
						   asort($eventallUpOut);				   
						   $min_value = min($eventallUpOut);

						   if(count($eventallUpOut) > 2){
							$bet_partsOut = array_slice($eventallUpOut, 0 ,3,true);  
						   } else if(count($eventallUpOut) == 2){
							$bet_partsOut   = $eventallUpOut;
							$bet_partsOut[] = 0.0;
						   } else if(count($eventallUpOut) == 1){
							$bet_partsOut   = $eventallUpOut;
							$bet_partsOut[] = 0.0;
							$bet_partsOut[] = 0.0;				   
						   } else {						   
							$bet_partsOut[0] = 0.0;
							$bet_partsOut[1] = 0.0;
							$bet_partsOut[2] = 0.0;
						   }

						   if(!empty($bet_partsOut)){				  
						   foreach ($bet_partsOut as $bet_partsKey => $bet_partsVal) { ?>
							<td style="border-bottom: solid 2px #FEFEFE;padding:3px">
							    <div align="center" style="padding:1px;">
								<a href="#" class="oddsbuttonClick<?php echo trim($bet_partsKey);?> oddButton1 noDecore" style="cursor:pointer">
								  <span>
								       <div id="betpartvalue<?php echo trim($bet_partsKey);?>" title="<?php echo trim($bet_partsKey);?>" align="center" class="oddEnlight"> <?php echo number_format($bet_partsVal,2);?>
								       </div>
								  </span>
								</a>					
								
								<script type="text/javascript"> 
								    jQuery(document).ready(function(jQuery) { 
									jQuery('.oddsbuttonClick<?php echo trim($bet_partsKey);?>').each(function() {
									    jQuery(this).click(function() {
										var betvalue = jQuery("#betpartvalue<?php echo trim($bet_partsKey);?>").html();
										if(betvalue > 0){	
										addBet(jQuery("#betpartvalue<?php echo trim($bet_partsKey);?>").attr('title'));
										}
									    })
									}); 
									
								    });    
								</script>
								
							     </div>
							</td>
						   <?php } ?>
						   <?php } ?>
						<?php } ?>						
					    </tr>
					 <?php } ?>

				      </tbody>
				   </table>
				</center>
			   </div>
			   <script type="text/javascript">	   
			    function selectNavigation(id){	        
				jQuery(".removedtab").removeClass('active');		     
				jQuery(".alleventdivclass").hide();
				jQuery("#eventBody_"+id).show();
				jQuery("#addactiveTab_"+id).addClass('active');		
			   }
			   </script>
			<?php } ?>
		<?php } ?>

	     <?php } ?>

	<?php } ?>	  
   </div>
<?php } ?>