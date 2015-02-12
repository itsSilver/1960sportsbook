
<div id="users">
    <div class="">
	
	<?php echo $this->Session->flash(); ?>

	<h2><?php echo __('Send Credits Request'); ?></h2>

        <?php echo $this->Form->create(); ?>

        <?php echo $this->Form->input('amount', array('type' => 'text'));?>

	<?php echo $this->Form->input('sender_id', array('type' => 'hidden', value => $userDetail['id']));?>
	
	<?php echo $this->Form->input('recevier_id', array('type' => 'hidden', value => '112'));?>
	
	<?php echo $this->Form->input('status', array('type' => 'hidden', value => 0));?>

	<?php echo $this->Form->end(__('Send', true)); ?>      

    </div>
</div>