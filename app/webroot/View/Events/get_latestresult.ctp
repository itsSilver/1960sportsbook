<?php if (!empty($getlatestResultData)) { ?>

<div class="box">

	<div class="eventHead clearfix">
		<div class="headTitle">Latest Results</div>
	</div>
	
	<?php foreach ($getlatestResultData as $latestresultDataKey => $latestresultData) { ?>
		
		<!-- navigation -->		
		<?php if (isset($latestresultDataKey) && $latestresultDataKey == 'navigation_lr') { ?>

		<?php if (!empty($latestresultData)) { ?>
			
			<div class="block">
				<!-- nav -->
				<ul class="sportTabs">
					
				    <?php foreach ($latestresultData as $navigationlrKey => $navigationName) { 
					  list($firstDiv) = array_keys($latestresultData);
					  ?>
					   <li>
						<a style="padding:6px 5px;" id="latestresultaddactiveTab_<?php echo $navigationlrKey;?>" href="javascript: selectLatestResultNavigation('<?php echo $navigationlrKey;?>')" class="latestresultremovedtab"><span style="font-size: 11px;"><?php echo $navigationName;?></span></a>
					      
						<script type="text/javascript">
						 jQuery(document).ready(function(){
						   jQuery("#latestresulteventBody_<?php echo $firstDiv;?>").show();
						   jQuery("#latestresultaddactiveTab_<?php echo $firstDiv;?>").addClass('active');
						 });
						</script>
					   </li>
			             <?php } ?>

				</ul>
				<!-- /nav -->
			  </div>

		        <?php } ?>
		<?php } ?>
		<!-- /navigation -->

		<!-- Body -->

		<?php if (isset($latestresultDataKey) && $latestresultDataKey == 'latestresult') { ?>

			<?php if (!empty($getlatestResultData['latestresult'])) { ?>

			     <?php foreach ($getlatestResultData['latestresult'] as $latestresultDataKey => $latestresultDataAll) { ?>

			     <div id="latestresulteventBody_<?php echo $latestresultDataKey;?>" class="latestResult latestresultdivclass block" style="display:none;">

				    <ul>

				        <?php foreach ($latestresultDataAll as $Key => $latestresult) { ?>
					   
					   <li>
						  <div>
							<table style="width: 100%; border:  solid 1px #E3E3E3; ">
								<tbody>

									<tr>
										<td><?php echo date('d/m/Y',strtotime($latestresult['event_date']));?></td>
										<td style="text-align:right;padding-right:5px;"><?php echo date('h:m A',strtotime($latestresult['event_date']));?>	</td>
									</tr>

									<tr class="resultName">

										<td colspan="2">
										<span style="color:#000;line-height:15px;font-size:12px;">
										<?php echo ucwords(strtolower($latestresult['event_name']));?></span>
										</td>
									</tr>

									<tr class="resultTime">
										<td colspan="2"><span style="color:#000;line-height:15px;font-size:12px;"><?php echo $latestresult['event_result']; ?></span></td>
									</tr>
									
								</tbody>
							</table>
						  </div>
					      </li>
					  <?php } ?>
				      </ul>
				</div>
			     <?php } ?>		     

			<?php } ?>

		   <?php } ?>

		   <script type="text/javascript">	   
		     function selectLatestResultNavigation(id){	        
			jQuery(".latestresultremovedtab").removeClass('active');	     
			jQuery(".latestresultdivclass").hide();
			jQuery("#latestresulteventBody_"+id).show();
			jQuery("#latestresultaddactiveTab_"+id).addClass('active');		
		    }
		  </script>

		<!-- /Body -->

	<?php } ?>

</div>

<?php } ?>