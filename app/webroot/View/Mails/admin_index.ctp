<h3><?php echo __('Promotion Letter'); ?></h3>

<?php echo $this->Form->create(); ?>

<?php echo $this->Form->input('to', array('label' => __('To:', true), 'class' => 'input-big')); ?>
<?php echo $this->Form->input('subject', array('label' => __('Subject:', true), 'class' => 'input-big')); ?>
<?php echo $this->Form->input('content', array('label' =>  __('Content:', true), 'type' => 'textarea')); ?>

<?php echo $this->Form->submit(__('Send', true), array('class' => 'button')); ?>

<?php echo $this->Form->end(); ?>



