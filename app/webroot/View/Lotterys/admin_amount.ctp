<div class="index">

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Assign Lottery Prize amount Percentage'); ?></h3></th>
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
			echo $this->Form->input('lottery_id', array('label' => false, 'type' => 'select', 'options' => $lotteryOption, 'class' => 'dropbox','id' => 'select_lottery_option','style'=>'width:30%;','selected' => $selected));
			?>
		     </th>
		</tr>	

		<?php
		if (isset($this->params['pass'][0]) && $this->params['pass'][0]!=0) {		
		    $display ='';
		    ?>
		    <tr>
			<td>
			    <label><?php echo __('Percentage of ticket sales allocated as prize money'); ?></label>
			</td>
			<td>
			    <?php echo $this->Form->input('prize_perct', array('div' => false, 'label' => false, 'type' => 'text', 'class' => '','id' => 'prize_perct','style'=>'','value' => $prize_perct ,'placeholder'=> 'Enter percentage of ticket sales'));?> %      
			</td>				   				   
		    </tr>

		<?php } else { ?>
		     <?php $display ='display:none;';?>
		<?php } ?>

		<tr style="<?php echo $display;?>">
		     <td></td>
		     <td>
			<?php echo $this->Form->end(__(array('value'=>'Submit','id'=>'lottery_option_name_submit','style'=>''))); ?>
		     </td>
		</tr>
		
		<script type="text/javascript">
		   jQuery(document).ready(function(){
		     jQuery('#select_lottery_option').change(function(){
		       if(jQuery('#select_lotteryoption').val() !=0)
		       jQuery('#LotteryAdminAmountForm').submit();
		     });
		   });
	        </script>
		
		<script type="text/javascript">
			jQuery(document).ready(function(){
			   jQuery('#lottery_option_name_submit').click(function(e){
				e.preventDefault();				
				var lotteryid = "<?php echo $lottery_id;?>";				
				if(lotteryid !='' && lotteryid !='0'){
					jQuery("#loader").addClass('success');  
					jQuery("#loader").removeClass('error');  
					jQuery("#loader").html('Please wait ...').show();	
					jQuery.ajax({
					    type: "POST",
					    data: jQuery("#LotteryAdminAmountForm").serialize(),
					    url : "/admin/lotterys/prizeamountajax",	
					    success: function(msg){	
						  if(jQuery.trim(msg)=='1'){
							jQuery("#loader").removeClass('success');  
							jQuery("#loader").addClass('error');
							jQuery("#loader").html('Please fill the field.').show();  
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