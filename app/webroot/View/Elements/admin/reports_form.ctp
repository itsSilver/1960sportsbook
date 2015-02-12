<?php echo $this->Form->create('Report'); ?>

<?php echo $this->MyForm->input('Report.from', array('type' => 'date')); ?>
<?php echo $this->MyForm->input('Report.to', array('type' => 'date')); ?>

<?php echo $this->Form->submit(__('Show', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>


