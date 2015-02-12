<?php
$lottery_type = '';
if($this->Session->read('postedData')) {
   $lottery_type   = $this->Session->read('postedData.lottery_type');  
}
?>

<div id="index">

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Add Lottery Type'); ?></h3></th> 
	    <th><?php echo $this->MyHtml->spanLink(__('View All'), array('action' => 'admin_types'), array('class' => 'right')); ?></th> 
	</tr>
    </table><br />

    <?php echo $this->Session->Flash(); ?>
   
    <?php echo $this->Form->create(); ?>
    
    <table class="items">

	<tr>
            <td><label><?php echo __('Lottery Type'); ?></label></td>
            <td><?php echo $this->Form->input('lottery_type', array('label' => false,'type' => 'text','placeholder' => 'Enter lottery name','value'=>''.$lottery_type.''));  ?></td>
        </tr>

	<tr>
            <td><label><?php echo __('Lottery Status'); ?></label></td>
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