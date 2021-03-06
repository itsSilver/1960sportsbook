<?php
$id = $name = $lottery_type_name = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = '';
if(!empty($data[0]['Lottery'])) {
   $id			= $data[0]['Lottery']['id'];
   $name		= $data[0]['Lottery']['name'];
   $lottery_type_name   = $data[0]['Lottery']['lottery_type_name'];
   $num_lott_ball	= $data[0]['Lottery']['num_lott_ball'];
   $lottery_fee		= $data[0]['Lottery']['lottery_fee'];
   $draw_time		= $data[0]['Lottery']['draw_time'];  
   $is_stuff		= $data[0]['Lottery']['is_stuff'];  
   $is_active		= $data[0]['Lottery']['is_active'];
   $logo		= $data[0]['Lottery']['logo'];
   $prize_level		= $data[0]['Lottery']['prize_level'];
   if($is_stuff == 0){
	$gen_lott_ball	= $num_lott_ball;
   } else {
	$gen_lott_ball	= $num_lott_ball-1;
   }
}
?>

<div id="account">

     <?php echo $this->Html->css(array('smoothness/jquery-ui')); ?>
     <?php //echo $this->Html->script(array('jquery-ui')); ?>

     <style>
	#feedback { font-size: 18px; }
	#selectable .ui-selecting { background: #FECA40; }
	#selectable .ui-selected { background: #F39814; color: white; }
	#selectable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
	#selectable li { margin: 3px; padding: 1px; float: left; width: 35px; height: 25px; font-size: 15px; text-align: center;font-weight: bold; }
     </style> 
     
     <?php echo $this->Session->Flash(); ?>

     <?php echo $this->element('lottery_tab'); ?>

     <?php echo $this->Form->create('ticket', array('url' => array('controller' => 'LotteryTickets', 'action' => 'ticket_print')));?>
     
     <fieldset style="background:#E4E5E6;">
	
	<fieldset class="pT10 pL10 pR10">	 
	     <h3 class="pL5"><?php echo __('Select Lottery Number'); ?></h3>
	</fieldset>

	<fieldset class="pT10 pL10 pR10">    
	        <?php echo $this->Form->input('ticket_id', array('disabled' => true,'label' => false,'div' => false,'type' => 'text','placeholder' => 'LOTTERY NUMBER','id' => 'ticket_id','class'=>'ticketball left'));  ?>
		<?php if($is_stuff==1){?>
		<?php echo $this->Form->input('stuff_ball', array('disabled' => true,'label' => false,'div' => false,'type' => 'text','placeholder' => 'STUFF BALL','id' => 'stuff_ball','class'=>'hasstuffball stuffball right'));  ?>
		<?php } ?>
		<?php echo $this->Form->input('lottery_id', array('type' => 'hidden','value'=>''.$id.''));  ?>
		<?php echo $this->Form->input('lottery_fee', array('type' => 'hidden','value'=>''.$lottery_fee.''));  ?>
		<?php echo $this->Form->input('draw_date', array('type' => 'hidden','value' => $draw_time));?>
	</fieldset>

	<fieldset class="pT10 pL10 pR10 fontbld font15">      
		<?php echo $this->Form->input('reset',array('id'=>'resetlotteryball','type'=>'reset','label'=>false,'class'=>'left uppercase button','value'=>'Reset Ticket'));?>
		<?php echo $this->Form->input('Generate Ticket',array('id'=>'generate_ticket_button','type'=>'submit','label'=>false,'class'=>'right uppercase button','value'=>'Generate Ticket'));?>	      
	</fieldset>

	<fieldset class="pT20 pL10 pR10 pB10">  
	    <ol id="selectable">
		<?php for($i=1;$i<50;$i++){?>		
		<li id="clickkey<?php echo $i;?>" class="ui-state-default"><?php echo $i;?></li>
		<?php } ?>
	    </ol>
	</fieldset>

	<fieldset style="display:none;" class="txtcenter pB20 colorred fontbld" id="message_display_id"></fieldset>

	<script type="text/javascript">
	   jQuery(document).ready(function(){
	     jQuery('#ticket_id').attr('disabled','true');
	     jQuery('#stuff_ball').attr('disabled','true');
	     jQuery('#generate_ticket_button').click(function(){	       
	       var lottery_field   = jQuery('#ticket_id').val();
	       var inputedlottery  = lottery_field.split(',').length;
	       var stuff_field     = jQuery('#stuff_ball').val();
	       var gen_lott_ball   = '<?php echo $gen_lott_ball;?>';
	       if(inputedlottery == gen_lott_ball && stuff_field !=''){
		  if(confirm("It will take a lottery fee from your account in generating a Ticket.Click OK to Contine and Cancel to Return.")){
		      jQuery('#ticket_id').removeAttr('disabled');
	              jQuery('#stuff_ball').removeAttr('disabled');
	              jQuery('#ticketTicketForm').submit();
		      return true;
		  } else {
		      return false;
		  }
	       } else {
	          if(lottery_field ==''){
		     jQuery('#ticket_id').css('border','1px solid red');
		  } else {
		     jQuery('#ticket_id').css('border','1px solid #000000');
		  }
		  if(lottery_field ==''){
		     jQuery('#stuff_ball').css('border','1px solid red');
		  }else {
		     jQuery('#stuff_ball').css('border','1px solid #000000');
		  }	
		  jQuery('#ticket_id').attr('disabled','true');
	          jQuery('#stuff_ball').attr('disabled','true');
	          return false;
	       }
	     });
	   });
	</script>

	<script type="text/javascript">	   
	jQuery(document).ready(function(){	
	     jQuery('#resetlotteryball').click(function(){
	       jQuery('#ticket_id').css('border','1px solid #000');
	       jQuery('#stuff_ball').css('border','1px solid #000');
	       jQuery("#selectable").removeAttr("disabled");
	       jQuery("li").removeClass("ui-selected");
	       jQuery("#message_display_id").html('').hide();
	       lotterynumber.length = 0;
	     });	     
	     var lotterynumber = new Array();
	     jQuery('#selectable').children().click(function(e){		    
		    var number = e.target.innerHTML;			
		    jQuery(this).addClass('ui-selected');
		    lotterynumber.push(number);		    
		    jQuery('#ticket_id').css('border','1px solid #000000');
		    jQuery('#stuff_ball').css('border','1px solid #000000');
		    var num_lott_ball = '<?php echo $num_lott_ball;?>';
		    var selectedBall  = lotterynumber.length;
		    if(selectedBall <= num_lott_ball){
		        jQuery("#message_display_id").html('').hide();
		        jQuery("#selectable").attr('disabled','true');
			if(jQuery("#stuff_ball").hasClass("hasstuffball")){				
			  if((lotterynumber.length) > (num_lott_ball-1)){	
			     var slicetag   = num_lott_ball-1;
			     var ballField  = lotterynumber.slice(0,slicetag);
			     var stuffField = lotterynumber.slice(-1);
			     jQuery("#ticket_id").val(ballField);
			     jQuery("#stuff_ball").val(stuffField);
			     jQuery("#selectable").attr("disable",'true');
			  }else{
			     jQuery("#selectable").attr('disabled','true');
			     jQuery("#ticket_id").val(lotterynumber);
			  }
			} else {
			    jQuery("#selectable").attr('disabled','true');
			    jQuery("#ticket_id").val(lotterynumber);
			}			
		   } else {		   
		      jQuery("#message_display_id").html("You are allowed to select <?php echo $num_lott_ball;?> ball only including the stuff ball also(if any).").show();
		      jQuery(this).removeClass("ui-selected");
		      return false;
		   }
	       });
	    });
	 </script>
	
     </fieldset>

     <?php echo $this->Form->end(); ?>

</div>