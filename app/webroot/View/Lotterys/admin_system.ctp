<div class="index">

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Set Lottery System draw Type'); ?></h3></th>
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
			echo $this->Form->input('lottery_id', array('label' => false, 'type' => 'select', 'options' => $lotteryOption, 'class' => 'dropbox','id' => 'select_lottery_jackpot_system','style'=>'width:30%;','selected' => $selected));
			?>
		     </th>
		</tr>	

		<?php
		if (isset($this->params['pass'][0]) && $this->params['pass'][0]!=0) {		
			$display ='';
			if($lottery_system_draw ==1)      { $checkedLottey = '1'; } 
			else if($lottery_system_draw ==2) { $checkedLottey = '2'; }
			else                              { $checkedLottey = '0'; } 

			if($jackpot_system_draw ==1)      { $checkedJackpot = '1'; } 
			else if($jackpot_system_draw ==2) { $checkedJackpot = '2'; }
			else                              { $checkedJackpot = '0'; }
			?>

			<tr id="set_lottery_system">
			     <td><h3 class="fontbld "><?php echo __('Select Lottery System draw Type'); ?></h3></td>
			     <td>
				<?php echo $this->Form->radio('lottery_system_draw',  array('1' => 'The system can ran randomly choose any combinations of numbers.', '2' => 'The system can choose combination of numbers that are not on Ticket placed by players.','0' => 'Deactivate both System.') , array('label' => false ,'hiddenField' => 'false','id' => 'jackpot_system_draw','separator'=> '<br><br>','between'=> '<br>','value'=> $checkedLottey));?>
			     </td>
			</tr>	

			<!-- <tr id="set_lottery_jackpot">
			     <td><h3 class="fontbld "><?php echo __('Set Jackpot System draw Type'); ?></h3></td>
			     <td>
				<?php echo $this->Form->radio('jackpot_system_draw',  array('1' => 'The system can ran randomly choose any combinations of numbers.', '2' => 'The system can choose combination of numbers that are not on Ticket placed by players.','0' => 'Deactivate both System.') , array('label' => false ,'hiddenField' => 'false','id' => 'jackpot_system_draw','separator'=> '<br><br>','between'=> '<br>','value'=> $checkedJackpot));?>
			     </td>
			</tr> -->	

		<?php } else { ?>
		     <?php $display ='display:none;';?>
		<?php } ?>

		<tr style="<?php echo $display;?>">
		     <td></td>
		     <td>
			<?php echo $this->Form->end(__(array('value'=>'Submit','id'=>'lottery_option_jackpot_submit','style'=> ''))); ?>
		     </td>
		</tr>		

		<script type="text/javascript">
		   jQuery(document).ready(function(){	     
		     jQuery('#select_lottery_jackpot_system').change(function(){
		       if(jQuery('#select_lottery_jackpot_system').val() !=0)
		       jQuery('#LotteryAdminSystemForm').submit();
		     });
		   });
		</script>

		<script type="text/javascript">
		jQuery(document).ready(function(){
		  jQuery('#lottery_option_jackpot_submit').click(function(e){
		     e.preventDefault();
		     var lotterysystemdraw=jQuery("input[type='radio'][name='data[Lottery][lottery_system_draw]']:checked").length;
		     var jackpotsystemdraw=jQuery("input[type='radio'][name='data[Lottery][jackpot_system_draw]']:checked").length;
		     if(lotterysystemdraw=='0' && jackpotsystemdraw=='0') {
			jQuery("#loader").removeClass('success');  
			jQuery("#loader").addClass('error');
			jQuery("#loader").html('Please select a radio button.').show(); 	       
			return false;	       
		     } else {
			jQuery("#loader").addClass('success');  
			jQuery("#loader").removeClass('error');  
			jQuery("#loader").html('Please wait ...').show();	
			jQuery.ajax({
			    type: "POST",
			    data: 'posttype=1&'+jQuery("#LotteryAdminSystemForm").serialize(),
			    url : "/admin/lotterys/system?posttype=1",	
			    success: function(msg){	
				  if(jQuery.trim(msg)=='1'){					 
				    jQuery("#loader").removeClass('error');  
				    jQuery("#loader").addClass('success');
				    jQuery("#loader").html('Entry has been saved.').show();
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