<div class="index">
    
    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Set Jackpot Lottery Prize Basis'); ?></h3></th>
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
			echo $this->Form->input('lottery_id', array('label' => false, 'type' => 'select', 'options' => $lotteryOption, 'class' => 'dropbox','id' => 'select_lottery_option_jackpot','style'=>'width:30%;','selected' => $selected));
			?>
		     </th>
		</tr>

		<?php
		if (isset($this->params['pass'][0]) && $this->params['pass'][0]!=0) {		
		    $display ='';
		    if($jackpot_set ==1){
		       $checked = '1';
		    } else {
		       $checked = '0';
		    }
		    ?>

		    <tr>
			<td>
			    <label><?php echo __('Set Jackpot Prize Money'); ?></label>
			</td>
			<td>
			    <?php echo $this->Form->radio('jackpot_set',  array('1' => 'Check if based  upon the allocated percentage  for First Prize Money.', '0' => 'Check if based upon First Prize money carried over from previous draw.') , array('label' => false ,'hiddenField' => 'false','separator'=> '<br>','between'=> '<br>','value'=> $checked));?>   
			</td>				   				   
		    </tr>

		<?php } else { ?>
		     <?php $display ='display:none;';?>
		<?php } ?>

		<tr style="<?php echo $display;?>">
		     <td></td>
		     <td>
			<?php echo $this->Form->end(__(array('value'=>'Submit','id'=>'lottery_option_jackpot_submit','style'=>''))); ?>
		     </td>
		</tr>
		
		<script type="text/javascript">
		   jQuery(document).ready(function(){
		     jQuery('#select_lottery_option_jackpot').change(function(){
		       if(jQuery('#select_lottery_option_jackpot').val() !=0)
		       jQuery('#LotteryAdminJackpotsetForm').submit();
		     });
		   });
	        </script>
		
		<script type="text/javascript">
			jQuery(document).ready(function(){
			   jQuery('#lottery_option_jackpot_submit').click(function(e){
				e.preventDefault();				
				var lotteryid = "<?php echo $lottery_id;?>";				
				if(lotteryid !='' && lotteryid !='0'){
					jQuery("#loader").addClass('success');  
					jQuery("#loader").removeClass('error');  
					jQuery("#loader").html('Please wait ...').show();	
					jQuery.ajax({
					    type: "POST",
					    data: jQuery("#LotteryAdminJackpotsetForm").serialize(),
					    url : "/admin/lotterys/jackpotsetajax",	
					    success: function(msg){	
						  if(jQuery.trim(msg)=='1'){
							jQuery("#loader").removeClass('success');  
							jQuery("#loader").addClass('error');
							jQuery("#loader").html('Please select a field.').show();  
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