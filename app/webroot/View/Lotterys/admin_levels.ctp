<div class="index">
    
    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Assign Lottery Game Level Prize'); ?></h3></th>
	</tr>
    </table><br />
    
    <table class="items">

	<?php echo $flash = $this->Session->flash(); ?>

	<?php echo $this->Html->div('message closable','',array('id'=>'loader','style'=>'display:none;'));?>

	<?php if (!empty($lotteryOption)){ ?>
	
		<?php echo $this->Form->create(); ?>

		<tr>
		     <th><h3 class="fontbld "><?php echo __('Select Lottery Game'); ?></h3></th>
		     <th>
			<?php
			$lottery_id = '';
			if($this->Session->read('lotteryid')) {
			   $lottery_id = $this->Session->read('lotteryid');   
			   $selected   = array(''.$lottery_id.'');
			} 
			echo $this->Form->input('lottery_id', array('label' => false, 'type' => 'select', 'options' => $lotteryOption, 'class' => 'dropbox','id' => 'select_lotteryoption','style'=>'width:30%;','selected' => $selected));
			?>
		     </th>
		</tr>
		
		<?php
		if (isset($this->params['pass'][1]) && $this->params['pass'][1]!=0) {		
		       $display ='';
		       $currency  = Configure::read('Settings.currency');
		       ?>	        	
		       <tr>
			   <td>
			       <label><?php echo __('Starting Prize Money(Amount)'); ?></label>
			   </td>
			   <td>
			       <input type="text" name="start_prize_amount" id="start_prize_amount" placeholder="Enter Starting Prize Amount" value="<?php if(isset($start_prize_amount)) { echo $start_prize_amount; } ?>"> <?php echo $currency;?>	      
			   </td>				   				   
		       </tr>

		       <?php
		       $level = $this->params['pass'][1];			    
		       $level_number =1;
		       for($i=0;$i<$level;$i++) { ?>
		       <tr>
			   <td>
			       <label><?php echo __('Percentage of the prize money for level '.$level_number.''); ?></label>
			   </td>
			   <td>
			      <input value="<?php if(!empty($level_data)){ echo $level_data[$i];} ?>" type="text" name="prizemoney[]" placeholder="Enter percentage of prize money <?php echo $level_number;?>" id="prizemoney<?php echo $i;?>" > %
			   </td>				   				   
		       </tr>
		       <?php $level_number++;?>
		       <?php } ?>	   

		<?php } else { ?>
		     <?php $display ='display:none;';?>
		<?php } ?>

		<tr style="<?php echo $display;?>">
		     <td></td>
		     <td>
			<?php echo $this->Form->end(__(array('value'=>'Submit','id'=>'lotteryoption_name_submit','style'=>''))); ?>
		     </td>
		</tr>
		
		<script type="text/javascript">
		   jQuery(document).ready(function(){
		     jQuery('#select_lotteryoption').change(function(){
		       if(jQuery('#select_lotteryoption').val() !=0)
		       jQuery('#LotteryAdminLevelsForm').submit();
		     });
		   });
	        </script>
		
		<script type="text/javascript">
			jQuery(document).ready(function(){
			   jQuery('#lotteryoption_name_submit').click(function(e){
				e.preventDefault();
				var level     = jQuery('#LotteryLevel').val();
				var lotteryid = "<?php echo $lottery_id;?>";				
				if(lotteryid !='' && lotteryid !='0'){
					jQuery("#loader").addClass('success');  
					jQuery("#loader").removeClass('error');  
					jQuery("#loader").html('Please wait ...').show();	
					jQuery.ajax({
					    type: "POST",
					    data: jQuery("#LotteryAdminLevelsForm").serialize(),
					    url : "/admin/lotterys/levelprizeajax",	
					    success: function(msg){	
						  if(jQuery.trim(msg)=='0'){
							jQuery("#loader").removeClass('success');  
							jQuery("#loader").addClass('error');
							jQuery("#loader").html('Please enter the numeric value for starting prize amount.').show();
							jQuery("#start_prize_amount").val('');
						  } else if(jQuery.trim(msg)=='1'){
							jQuery("#loader").removeClass('success');  
							jQuery("#loader").addClass('error');
							jQuery("#loader").html('Please fill all the fields.').show();  
						  } else if(jQuery.trim(msg)=='3'){
							jQuery("#loader").removeClass('success');  
							jQuery("#loader").addClass('error');
							jQuery("#loader").html('Internal error occured.Try again.').show();  
						  } else {						 
							jQuery("#loader").removeClass('error');  
							jQuery("#loader").addClass('success');
							jQuery("#loader").html('Entry saved.').show();
							location.reload();
						  }						    
					     }							
					});
				}
			    });	
			});	
		</script>
		
	<?php } else { ?>

		<?php echo __('No Lottery Game added'); ?></h4>

	<?php } ?>

    </table>

</div>