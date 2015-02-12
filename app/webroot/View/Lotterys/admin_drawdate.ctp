<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

<div id="" class="index">
    
    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Set Lottery Draw Date'); ?></h3></th> 
	</tr>
    </table><br />

    <?php echo $this->Session->flash(); ?>

    <?php if (!empty($data)){ ?>

    <?php echo $this->Form->create();?>

        <table class="items">

            <?php foreach ($data as $lotteryType => $rowAll){ ?>
		
		<tr>	
		    <th colspan="3"><h3 class="fontbld"><?php echo __($lotteryType);?></h3></th>
                </tr>

		<tr><td colspan="3"></td></tr>
		
		<tr>		    
		    <th><?php echo $this->Paginator->sort('Lottery.name', __('NAME')); ?></th>
		    <th><?php echo $this->Paginator->sort('Lottery.draw_time', __('DRAW TIME')); ?></th>
		    <th><?php echo __('ACTION'); ?></th> 
                </tr>
	        
		<?php foreach ($rowAll as $lotteryid => $row){ ?>
			<tr>			    		    
			    <td><?php echo $row['lottery_name']; ?></td>
			    <td>
				<?php echo $this->Form->input('draw_time', array('type' => 'text','label' => false,'placeholder' => 'Select lottery drawn time' , 'id' => 'select_lottery_time_'.$row['lottery_id'].'','value'=>''.$row['draw_time'].''));?>
				<div id="loader_<?php echo $row['lottery_id'];?>" class="colorred"></div>
				<script type="text/javascript">
				jQuery("#ui-datepicker-div").hide();				
				jQuery("#select_lottery_time_<?php echo $row['lottery_id'];?>").datetimepicker({
				    changeMonth: true,
				    changeYear: true,
				    timeFormat: 'h:m',
				    showOn: "button",
				    buttonImage: "<?php echo $this->Html->url('/') . IMAGES_URL . 'calendar.gif'; ?>",  
				    buttonImageOnly: true,
				    yearRange: 'c-80:c+0',
				    dateFormat: 'yy-mm-dd'
				});
				</script>
			    </td>
			    <td>
				<a id="save_lottery_date_<?php echo $row['lottery_id'];?>" class="button" href="javascript;">Save</a>
				<script type="text/javascript">
				jQuery(document).ready(function(){
				   jQuery('#save_lottery_date_<?php echo $row['lottery_id'];?>').click(function(e){
					e.preventDefault();
					var draw_time  = jQuery('#select_lottery_time_<?php echo $row['lottery_id'];?>').val();	
					var lottery_id = "<?php echo $row['lottery_id']?>";	
					if(lottery_id !='' && draw_time !=''){
					    jQuery('#select_lottery_time_<?php echo $row['lottery_id'];?>').css('border','');
					    jQuery("#loader_<?php echo $row['lottery_id'];?>").html('Saving data...').show();	
					    jQuery.ajax({
						type: "POST",
						data: "lottery_id="+lottery_id+"&draw_time="+draw_time,
						url : "/admin/lotterys/drawdate",	
						success: function(msg){
						   jQuery("#loader_<?php echo $row['lottery_id'];?>").html(msg).fadeOut(2000);
						   jQuery("#lottery_type_tr").html('').hide();
						}							
					    });
					} else {				
					      jQuery('#select_lottery_time_<?php echo $row['lottery_id'];?>').css('border','1px solid red');
					      return false;		  
					}
				    });	
				});	
				</script>

			    </td>

			</tr>

		<?php } ?>  

            <?php } ?>        

	    <?php echo $this->element('paginator'); ?> 

	    <?php echo $this->Form->end(); ?>

	<?php } else { ?>
	
	<tr><td colspan="3"><?php echo __('There are no records'); ?></td><tr>       
	
	<?php } ?>

    </table>
    
</div>