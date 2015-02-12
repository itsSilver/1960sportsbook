<?php echo $this->Form->create(); ?>

<?php echo $this->Form->input('query', array('type' => 'textarea', 'class' => 'mceNoEditor')); ?>

<?php echo $this->Form->submit(__('Submit', true), array('class' => 'button')); ?>

<?php echo $this->Form->end(); ?>
