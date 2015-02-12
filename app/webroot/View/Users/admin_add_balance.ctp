<?php

echo $this->Form->create();
echo $this->Form->input('amount');
echo $this->Form->submit(__('Add', true), array('class' => 'button'));
echo $this->Form->end();
?>
