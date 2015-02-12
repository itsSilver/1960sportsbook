<?php
$min_selected = $hour_selected = $day_selected = array();
$id = $name = $lottery_type = $num_lott_ball = $lottery_fee = $draw_time = $is_stuff = $is_active = $prize_level = '';

$day_Array = $hour_Array = $min_Array = array();

$filterday_option_json  = $filterhour_option_json = $filtermin_option_json  = '0'; 

if(!empty($data[0]['Lottery'])) {
   $id            = $data[0]['Lottery']['id'];
   $name          = $data[0]['Lottery']['name'];
   $lottery_type  = $data[0]['Lottery']['lottery_type'];
   $num_lott_ball = $data[0]['Lottery']['num_lott_ball'];
   $lottery_fee   = $data[0]['Lottery']['lottery_fee'];
   $draw_time     = $data[0]['Lottery']['draw_time'];  
   $is_stuff      = $data[0]['Lottery']['is_stuff'];  
   $is_active     = $data[0]['Lottery']['is_active'];
   $logo          = $data[0]['Lottery']['logo'];
   $prize_level   = $data[0]['Lottery']['prize_level'];
   if($draw_time!=''){
        $draw_time_Arr  = explode('/',$draw_time);
	if(!empty($draw_time_Arr) && !empty($day_option)) {
	    foreach($draw_time_Arr as $key => $draw_time_one){
		$day_one = strtoupper(date('D',strtotime($draw_time_one)));
		foreach($day_option as $day_option_one){
		    if($day_option_one['name'] == $day_one){
		       $day_Array[] = array('id' => trim($day_option_one['id']), 'name' => trim($day_option_one['name']));
		    }
		}			
		$hour_Array = array(array('id' => trim(date('H',strtotime($draw_time_one))), 'name' => trim(date('H',strtotime($draw_time_one)))));
		$min_Array = array(array('id' => trim(date('i',strtotime($draw_time_one))), 'name' => trim(date('i',strtotime($draw_time_one)))));
	    }

	    $filterday_option_json  = json_encode($day_Array);
	    $filterhour_option_json = json_encode($hour_Array);
	    $filtermin_option_json  = json_encode($min_Array);
	}	
   }
}
?>

<div id="account">

   <table class="items">
	  <tr>				
	        <th class="wdth900">
		   <?php echo $this->MyHtml->spanLink(__('All Lottery Game'), array('action' => 'admin_list'), array('class' => 'button-blue')); ?> >>> 
	           <?php echo $this->MyHtml->spanLink(__(''.$name.''), array('action' => 'admin_view',$id,'view'), array('class' => 'button-blue')); ?>
		</th>
	        <th>
	           <?php echo $this->MyHtml->spanLink(__('View'), array('action' => 'admin_view',$id,'view'), array('class' => 'button-blue')); ?> | 
	           <?php echo $this->MyHtml->spanLink(__('Back'), array('action' => 'admin_list'), array('class' => 'button-blue')); ?>
	       </th> 
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
		$selected = array($lottery_type);			
		echo $this->Form->input('lottery_type', array('label' => false, 'type' => 'select', 'options' => $lotterytypeOption, 'class' => '','id' => 'lottery_type','style'=>'','selected' => $selected)); ?>	    
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
            <td><?php 
	        echo $this->Form->input('logo', array('type' => 'file','placeholder' => 'select lottery logo')); 
		echo $this->Form->hidden('oldlogo', array('type' => 'text','value' => ''.$logo.''));
	        
		$imagepath= '/img/lottery/'.$logo;
		echo 'Last Logo: '.$this->MyHtml->image(''.$imagepath.'', array('alt' => ''.$logo.'','class'=>'logomedium'));
	    ?>
	   </td>
        </tr>

	<tr>
            <td><label><?php echo __('Stuff'); ?></label></td>
            <td>		
		<label>
		<?php
		if($is_stuff ==1){
		   $checked = 'checked';
		} else {
		   $checked = '';
		}		
		echo $this->Form->checkbox('is_stuff', array('value' => '1',$checked) , array('hiddenField' => 'false'));?>
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
		<?php
		if($is_active ==1){
		   $selected = array(1);
		} else {
		   $selected = array(2);
		}
		echo $this->Form->input('is_active', array('label' => false, 'type' => 'select', 'options' => array('1'=>'Active','2'=>'Deactive'), 'class' => '','id' => 'select_status','style'=>'','selected' => $selected)); ?>	    
	    </td>
        </tr>

	<tr>
            <td>&nbsp;</td>
            <td><?php echo $this->Form->submit(__('Update', true), array('class' => 'button')); ?></td>
        </tr>

    </table>

    <?php echo $this->Form->end(); ?>

</div>