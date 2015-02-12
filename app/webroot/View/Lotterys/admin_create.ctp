<?php
$min_selected = $hour_selected = $day_selected = $name = $num_lott_ball = $lottery_fee = $draw_time = $prize_level = '';

$day_Array = $hour_Array = $min_Array = array();

$filterday_option_json  = $filterhour_option_json = $filtermin_option_json  = json_encode(array());   

if($this->Session->read('postedData')) {
   $name          = $this->Session->read('postedData.name');
   $num_lott_ball = $this->Session->read('postedData.num_lott_ball');
   $lottery_fee   = $this->Session->read('postedData.lottery_fee');
   $draw_time     = $this->Session->read('postedData.draw_time');
   $prize_level   = $this->Session->read('postedData.prize_level');
   $day_selected  = $this->Session->read('postedData.day_option');
   $hour_selected = $this->Session->read('postedData.hour_option');
   $min_selected  = $this->Session->read('postedData.min_option');

   if($day_selected!=''){
        $day_selected_Arr  = explode(';',$day_selected);
	if(!empty($day_selected_Arr) && !empty($day_option)) {
	    foreach($day_selected_Arr as $key => $draw_time_one){
		foreach($day_option as $day_option_one){
		    if($day_option_one['id'] == $draw_time_one){
		       $day_Array[] = array('id' => trim($day_option_one['id']), 'name' => trim($day_option_one['name']));
		    }
		}
	    }
       }
   }
   if($hour_selected!=''){
        $hour_selected_arr_out  = explode(';',$hour_selected);
	if(!empty($hour_selected_arr_out)) {
	    foreach($hour_selected_arr_out as $key => $hour_selected_one){		
	        $hour_Array[] = array('id' => trim($hour_selected_one), 'name' => trim($hour_selected_one));
	    }		    
	}
   }
   if($min_selected!=''){
        $min_selected_arr_out  = explode(';',$min_selected);
	if(!empty($min_selected_arr_out)) {
	    foreach($min_selected_arr_out as $key => $min_selected_one){		
	        $min_Array[] = array('id' => trim($min_selected_one), 'name' => trim($min_selected_one));
	    }		    
	}
   }
   $filterday_option_json  = json_encode($day_Array);
   $filterhour_option_json = json_encode($hour_Array);
   $filtermin_option_json  = json_encode($min_Array);   
}
?>

<div id="account">

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld"><?php echo __('Add Lottery Game'); ?></h3></th> 
	    <th><?php echo $this->MyHtml->spanLink(__('Back'), array('action' => 'admin_list'), array('class' => 'fontbld right')); ?></th> 
	</tr>
    </table><br />

    <?php echo $this->Session->Flash(); ?>
    
    <?php
    echo $this->Form->create('Lottery', array(
	'type' => 'file',
        'inputDefaults' => array(
            'label' => false,
            'class' => '',
            'error' => array(
                'wrap' => 'span',
                'class' => 'my-error-class'
            )
        )
    ));
    ?>

    <table class="items">

	<tr>
            <td><label><?php echo __('Lottery Type'); ?></label></td>
            <td>
	        <?php
		$selected = array(1);		
		echo $this->Form->input('lottery_type', array('label' => false, 'type' => 'select', 'options' => $lotterytypeOption, 'class' => '','id' => 'select_status','style'=>'','selected' => $selected)); ?>	    
	    </td>
        </tr>

        <tr>
            <td><label><?php echo __('Lottery Name'); ?></label></td>
            <td><?php echo $this->Form->input('name', array('type' => 'text','placeholder' => 'Enter lottery name','value'=>''.$name.''));  ?></td>
        </tr>

	<tr>
            <td><label><?php echo __('Number of lottery ball'); ?></label></td>
            <td><?php echo $this->Form->input('num_lott_ball', array('type' => 'text','placeholder' => 'Enter number of lottery ball','value'=>''.$num_lott_ball.''));  ?></td>
        </tr>

	<tr>
            <td><label><?php echo __('Number of lottery prize level'); ?></label></td>
            <td><?php echo $this->Form->input('prize_level', array('type' => 'text','placeholder' => 'Enter number of lottery prize level','value'=>''.$prize_level.''));  ?></td>
        </tr>

	<tr>
            <td><label><?php echo __('Lottery Fee'); ?></label></td>
            <td><?php echo $this->Form->input('lottery_fee', array('div' => false,'type' => 'text','placeholder' => 'Enter lottery Fee','value'=>''.$lottery_fee.'')).' '.$currency;  ?></td>
        </tr>

	<tr>
            <td><label><?php echo __('Lottery Logo'); ?></label></td>
            <td><?php echo $this->Form->input('logo', array('type' => 'file'));  ?></td>
        </tr>

	<tr>
            <td><label><?php echo __('Stuff'); ?></label></td>
            <td>		
		<label>
		<?php echo $this->Form->checkbox('is_stuff', array('value' => '1') , array('hiddenField' => 'false'));?>
		<?php echo __('Check if extra stuff needed like powerball or Megaball.');?>
		</label>
	    </td>
        </tr>

	<tr>
            <td><label><?php echo __('Lottery drawn Time'); ?></label></td>
            <td>
		<?php echo $this->Form->input('day_option', array('type' => 'text','label' => 'Day','id' => 'search_day_option_input')); ?>
		<?php echo $this->Form->input('hour_option', array('type' => 'text','label' => 'Hour','id' => 'search_hour_option_input')); ?>
		<?php echo $this->Form->input('min_option', array('type' => 'text','label' => 'Minute','id' => 'search_min_option_input')); ?>

		<script type="text/javascript">
		   jQuery(document).ready(function(){
			jQuery("#search_day_option_input").tokenInput(<?php echo $day_option_json; ?>, {
			    preventDuplicates: true,
			    tokenLimit: 3 ,
			    prePopulate: <?php echo $filterday_option_json; ?>
			});
			jQuery("#search_hour_option_input").tokenInput(<?php echo $hour_option_json; ?>, {
			    preventDuplicates: true,
			    tokenLimit: 1,
			    prePopulate: <?php echo $filterhour_option_json; ?>
			});
			jQuery("#search_min_option_input").tokenInput(<?php echo $min_option_json; ?>, {
			    preventDuplicates: true,
			    tokenLimit: 1,
			    prePopulate: <?php echo $filtermin_option_json; ?>
			});
		  });
		</script>

	    </td>
        </tr>

	<tr>
            <td><label><?php echo __('Game Status'); ?></label></td>
            <td>
		<?php echo $this->Form->input('is_active', array('label' => false, 'type' => 'select', 'options' => array('1'=>'Active','2'=>'Deactive'), 'class' => '','id' => 'select_status','style'=>'')); ?>	    
	    </td>
        </tr>

	<tr>
            <td>&nbsp;</td>
            <td><?php echo $this->Form->submit(__('Submit', true), array('class' => 'button')); ?></td>
        </tr>

    </table>

    <?php echo $this->Form->end(); ?>

</div>