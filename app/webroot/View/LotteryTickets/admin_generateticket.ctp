<?php
$name = $num_lott_ball = $lottery_fee = $draw_time = $prize_level = '';
if($this->Session->read('postedData')) {
   $name          = $this->Session->read('postedData.name');
   $num_lott_ball = $this->Session->read('postedData.num_lott_ball');
   $lottery_fee   = $this->Session->read('postedData.lottery_fee');
   $draw_time     = $this->Session->read('postedData.draw_time');
   $prize_level   = $this->Session->read('postedData.prize_level');
   
}
?>
<div class="account" id="index">

    <?php echo $this->Session->Flash(); ?>

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Generate Lottery Ticket'); ?></h3></th>
	    <th><h3 class="fontbld font16 right"><a href="javascript:;" onclick="javascript:history.go(-1)"> Back </a></h3></th>
	</tr>
    </table><br />
    
    <?php echo $this->Form->create('LotteryTicket', array('url' => array('controller' => 'lottery_tickets', 'action' => 'admin_random_ticket')));?>

    <table class="items">

	<tr>
            <th><label><?php echo __('Select Lottery Type'); ?></label></th>
            <th>
	        <?php
		$selected ='';
		if(isset($lottery_type_id)){
		   $selected = array($lottery_type_id);
		}
		echo $this->Form->input('lottery_type', array('label' => false, 'type' => 'select', 'options' => $lotterytypeOption, 'class' => '','id' => 'lottery_type_id','class'=>'','selected' => $selected)); ?>
		<span class="colorred" id="lottery_type_tr"></span>
	    </th>
        </tr>

	<?php if(!empty($lotteryOption)) { ?>
	<tr>
            <th><label><?php echo __('Select Lottery Game'); ?></label></th>
            <th>
	        <?php		  
	        $selected   = '';		 
		echo $this->Form->input('lottery_id', array('label' => false, 'type' => 'select', 'options' => $lotteryOption, 'class' => '','id' => 'select_lotteryoption','selected' => $selected));
		?> 
	    </th>
        </tr>

	<tr>
            <td>&nbsp;</td>
            <td><?php echo $this->Form->submit(__('Submit', true), array('id' => 'submit_lottery_type_button', 'class' => 'button')); ?></td>
        </tr>

	<script type="text/javascript">
	   jQuery(document).ready(function(){
	         jQuery('#submit_lottery_type_button').click(function(){
		       var lottery_type_id  = jQuery('#lottery_type_id').val();
		       var lottery_id       = jQuery('#select_lotteryoption').val();
		       if(lottery_type_id !='0' && lottery_id !='0'){
		           jQuery('#select_lotteryoption').css('border','');
			   jQuery('#LotteryTicketAdminGenerateticketForm').submit();
			   return true;
		       } else {
			   jQuery('#select_lotteryoption').css('border','1px solid red');
			   return false;
		       }
	         });
	   });
	</script>
	
	<?php } ?>

	<script type="text/javascript">
		jQuery(document).ready(function(){
		   jQuery('#lottery_type_id').change(function(e){
			e.preventDefault();
			var lottery_type_id  = jQuery('#lottery_type_id').val();		
			if(lottery_type_id !='' && lottery_type_id !='0'){
			    jQuery('#lottery_type_id').css('border','');
			    jQuery("#lottery_type_tr").html('Loading please wait ...').show();	
			    jQuery.ajax({
				type: "POST",
				data: "lottery_type_id="+lottery_type_id+"&getlotteryTypeall=1",
				url : "/admin/lottery_tickets/generateticket",	
				success: function(msg){
				   jQuery(".account").html(msg).show();
				   jQuery("#lottery_type_tr").html('').hide();
				}							
			    });
			} else {				
			      jQuery('#lottery_type_id').css('border','1px solid red');
			      return false;		  
			}
		    });	
		});	
	</script>

    </table>

    <?php echo $this->Form->end(); ?>

</div>