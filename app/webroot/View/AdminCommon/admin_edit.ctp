<div class="<?php echo $pluralName; ?> add">
    <?php echo $this->Session->flash(); ?>
    <h2><?php echo $singularName; ?></h2>
    <?php
    echo $this->Form->create($model, array('type' => 'file'));
    echo $this->MyForm->inputs(array_merge($fields, array('legend' => __('Edit %s', $singularName))));
    
    
    echo $this->Form->submit(__('Submit', true), array('class' => 'button'));
    echo $this->Form->end();
    ?>
</div>