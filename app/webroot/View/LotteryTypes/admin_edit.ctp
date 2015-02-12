<?php
$is_active = $lottery_type_id = $lottery_type = '';
if(!empty($data['LotteryType'])) {
   $lottery_type_id  = $data['LotteryType']['id'];  
   $lottery_type     = $data['LotteryType']['lottery_type']; 
   $is_active        = $data['LotteryType']['is_active']; 
}
?>

<div id="index">

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Edit Lottery Type'); ?></h3></th> 
	    <th><?php echo $this->MyHtml->spanLink(__('View All'), array('action' => 'admin_types'), array('class' => 'right')); ?></th> 
	</tr>
    </table><br />
    
    <?php echo $this->Session->Flash(); ?>
   
    <?php echo $this->Form->create(); ?>
    
    <table class="items">

	<tr>
            <td><label><?php echo __('Lottery Type'); ?></label></td>
            <td>
	        <?php echo $this->Form->input('lottery_type', array('label' => false,'type' => 'text','placeholder' => 'Enter lottery name','value'=>''.$lottery_type.''));  ?>
	        <?php echo $this->Form->input('id', array('type' => 'hidden','value'=> $lottery_type_id));  ?>
	    </td>
        </tr>

	<tr>
            <td><label><?php echo __('Lottery Status'); ?></label></td>
            <td>
	        <?php
		if($is_active ==1){
		   $selected = array(1);
		} else {
		   $selected = array(2);
		}
		echo $this->Form->input('is_active', array('label' => false, 'type' => 'select', 'options' => array('1'=>'Active','2'=>'Deactive'),'id' => 'select_status','selected' => $selected)); ?>   
	    </td>
        </tr>

	<tr>
            <td>&nbsp;</td>
            <td><?php echo $this->Form->submit(__('Submit', true), array('class' => 'button')); ?></td>
        </tr>

    </table>

    <?php echo $this->Form->end(); ?>

</div>