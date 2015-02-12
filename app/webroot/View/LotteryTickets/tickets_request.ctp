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
}
?>

<div id="account">
    <?php echo $this->Session->Flash(); ?>
    
    <?php echo $this->element('lottery_tab'); ?>

    <?php echo $this->Form->create();?>

    <table class="marginTable default-table">

        <tr>
            <td><label><?php echo __('Lottery Type'); ?></label></td>
            <td><label><?php echo __($lottery_type_name);?></label>
	        <?php echo $this->Form->input('lottery_id', array('type' => 'hidden','value' => $id));?>
		<?php echo $this->Form->input('lottery_fee', array('type' => 'hidden','value' => $lottery_fee));?>
		<?php echo $this->Form->input('user_id', array('type' => 'hidden','value' => $user_id));?>
	    </td>	    
        </tr>

	<tr>
            <td><label><?php echo __('Lottery Name'); ?></label></td>
            <td><label><?php echo $name; ?></label></td>	    
        </tr>

	<tr>
            <td><label><?php echo __('Number of lottery ball'); ?></label></td>
            <td><label><?php echo $num_lott_ball; ?></label></td>
        </tr>

	<tr>
            <td><label><?php echo __('Prize Level'); ?></label></td>
            <td><label><?php echo $prize_level; ?></label></td>
        </tr>

	<tr>
            <td><label><?php echo __('Lottery Fee'); ?></label></td>
            <td><label><?php echo $lottery_fee; ?></label></td>
        </tr>

	<tr>
            <td><label><?php echo __('Lottery Logo'); ?></label></td>
            <td><label><?php
		$imagepath= '/img/lottery/'.$logo;
		echo $this->MyHtml->image(''.$imagepath.'', array('alt' => ''.$logo.'','class'=>'logo'));
		?>
	     </label></td>
        </tr>

	<tr>
            <td><label><?php echo __('Stuff'); ?></label></td>
            <td><label>		
		<?php if(isset($is_stuff) && $is_stuff==1) { echo 'Yes'; } else { echo 'No';} ?>
	    </label></td>
        </tr>

	<tr>
            <td><label><?php echo __('Lottery drawn Time'); ?></label></td>
            <td><label><?php echo date('d M Y h:i',strtotime($draw_time)); ?></label></td>
        </tr>

	<tr>
            <td><label><?php echo __('Select Agent'); ?></label></td>
            <td>
		<label>
		<?php
		echo $this->Form->input('agent_id', array('label' => false, 'type' => 'select', 'options' => $agentsOption, 'class' => '','id' => 'select_agent_option','style'=>'width:50%;','selected' => ''));
		?>
		</label>
	    </td>
        </tr>

	<tr>
            <td>&nbsp;</td>
            <td><?php echo $this->Form->submit(__('Send Request', true), array('class' => 'button')); ?></td>
        </tr>

    </table>

    <script type="text/javascript">
	   jQuery(document).ready(function(){
	     jQuery('#LotteryTicketTicketsRequestForm').submit(function(){	       
		  if(jQuery('#select_agent_option').val() !=0){
		     jQuery('#select_agent_option').css('border','');
		     if(confirm("It will transfer lottery fee from your account to Agent account.Click OK to Contine and Cancel to Return.")){		         
		         return true;
		      } else {
		        return false;
		      }
		  } else {
		      jQuery('#select_agent_option').css('border','3px solid red');
		      return false;
		  }
	     });
	   });
   </script>

    <?php echo $this->Form->end(); ?>

</div>