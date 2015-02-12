<div class="<?php echo $pluralName; ?> translate">    
    <h2><?php echo $singularName; ?></h2>
    <?php
    echo $this->Form->create($model, array('type' => 'file'));
    echo $this->Form->input(__('locale', true), array('type' => 'select', 'options' => $locales));    
    echo $this->Form->inputs(array_merge($fields, array('legend' => __('Create %s', $singularName))));
    echo $this->Form->submit(__('Submit', true), array('class' => 'button'));
    echo $this->Form->end();
    ?>
</div>