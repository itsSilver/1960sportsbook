<?php $this->groupidmp = $this->Session->read('Auth.User.group_id');?>

<?php if (!empty($mostplayedData)) { ?>

   <div class="box">
	
	<?php foreach ($mostplayedData as $mostplayedkey => $mostplayedDataDetail) { ?>
		
		<?php if (isset($mostplayedkey) && $mostplayedkey == 'navigation') { ?>
			
		     <?php if (!empty($mostplayedDataDetail)) { ?>
			<div class="eventHead clearfix">
				<div class="headTitle">
					<div id="nextEventsDiv">
					   <div style="text-align: right;">
					     <span class="tableHeadTitle">Most Played</span>

					      <ul class="sportTabs">
						  <?php foreach ($mostplayedDataDetail as $navigationKey => $navigationName) { 
						  list($firstDiv) = array_keys($mostplayedDataDetail);
						  ?>
						      <li>
							<a id="mostactiveTab_<?php echo $navigationKey;?>" href="javascript: selectMostPlayedNavigation('<?php echo $navigationKey;?>')" class="mostplayedremovedtab"><span style="font-size: 9px;"><?php echo $navigationName;?></span></a>
						      
							<script type="text/javascript">
							 jQuery(document).ready(function(){
							   jQuery("#mosteventBody_<?php echo $firstDiv;?>").show();
							   jQuery("#mostactiveTab_<?php echo $firstDiv;?>").addClass('active');
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

		<?php if (isset($mostplayedkey) && $mostplayedkey == 'mostplayedbet') { ?>

		    <?php if (!empty($mostplayedData['mostplayedbet'])) { ?>
		
			     <?php foreach ($mostplayedData['mostplayedbet'] as $mostplayedkey => $mostplayedDataDetail) { ?>

				   <div id="mosteventBody_<?php echo $mostplayedkey;?>" class="allmostplaydivclass block" style="display:none;">
					<center>
					<table cellspacing="0" cellpadding="0" border="0" align="center">
					
					    <thead>
					      <tr bgcolor="#E3E3E3">
						<td width="41%" height="20" style="padding-left: 1em"><strong> Match </strong></td>
						<td width="32%" style="text-align:center"><strong> Date and Time </strong></td>
						<td width="9%" style="text-align:center"><strong>1</strong></td>
						<td width="9%" style="text-align:center"><strong>X</strong></td>
						<td width="9%" style="text-align:center"><strong>2</strong></td>
					     </tr>
					    </thead>
					    
					    <tbody>
						
					       <?php foreach ($mostplayedDataDetail as $Key => $eventallMp) { ?>

					       <tr style=" background-color:#6396C9;">

					        <td height="28" style="padding-top:10px;color:#ffff !important;border-bottom: solid 2px #FEFEFE;text-align:left;padding-left:0.6em;">
						<?php echo $this->Html->link(ucwords(strtolower($eventallMp['event_name'])), array('controller' => 'events', 'action' => 'view', $eventallMp['event_id']),array('style'=> 'color:#fff')); ?>	
						</td>
						
						<td align="center" style="border-bottom: solid 2px #FEFEFE;">
						<center><span><?php echo date('d/m/Y, h:m A',strtotime($eventallMp['event_date']));?> </span></center>
						</td>

						<?php if (!empty($eventallMp['bet_part_odd_most'])) { ?>	

						   <?php 
						   $eventallMpOut = array_unique($eventallMp['bet_part_odd_most']);
						   asort($eventallMpOut);
						   
						   if(count($eventallMpOut) > 2){
							$bet_partsOutMp = array_slice($eventallMpOut, 0 ,3,true);  
						   } else if(count($eventallMpOut) == 2){
							$bet_partsOutMp   = $eventallMpOut;
							$bet_partsOutMp[] = 0.0;
						   } else if(count($eventallMpOut) == 1){
							$bet_partsOutMp   = $eventallMpOut;
							$bet_partsOutMp[] = 0.0;
							$bet_partsOutMp[] = 0.0;			   
						   } else {						   
							$bet_partsOutMp[0] = 0.0;
							$bet_partsOutMp[1] = 0.0;
							$bet_partsOutMp[2] = 0.0;
						   }
						   
						   if(!empty($bet_partsOutMp)){
						   foreach ($bet_partsOutMp as $bet_partsKey => $bet_partsVal) { ?>
							<td style="border-bottom: solid 2px #FEFEFE;padding:3px">
							<div align="center" style="padding:1px;">
							    <a href="#" class="oddsbuttonClickmp<?php echo trim($bet_partsKey);?> oddButton1 noDecore" style="cursor:pointer">
								  <span>
								       <div id="betpartmpvalue<?php echo trim($bet_partsKey);?>" title="<?php echo trim($bet_partsKey);?>" align="center" class="oddEnlight" style=""><?php echo number_format($bet_partsVal,2);?></div>
								  </span>
							     </a>

							     <script type="text/javascript"> 
								    jQuery(document).ready(function(jQuery) { 
									jQuery('.oddsbuttonClickmp<?php echo trim($bet_partsKey);?>').each(function() {
									    jQuery(this).click(function() {
										var betvalue = jQuery("#betpartmpvalue<?php echo trim($bet_partsKey);?>").html();
										if(betvalue > 0){	
										addBet(jQuery("#betpartmpvalue<?php echo trim($bet_partsKey);?>").attr('title'));
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

		      <?php } ?>

	          <?php } ?>

		  <script type="text/javascript">	   
		   function selectMostPlayedNavigation(id){	        
			jQuery(".mostplayedremovedtab").removeClass('active');		     
			jQuery(".allmostplaydivclass").hide();
			jQuery("#mosteventBody_"+id).show();
			jQuery("#mostactiveTab_"+id).addClass('active');		
		   }
		  </script>

	   <?php } ?>

       <?php } ?>	  
   </div>

<?php } ?>