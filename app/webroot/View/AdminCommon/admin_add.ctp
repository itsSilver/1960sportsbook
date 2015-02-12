<div class="<?php echo $pluralName; ?> add">
    <?php echo $this->Session->flash(); ?>
    <h2><?php echo $singularName; ?></h2>
    <?php
    $url = array();
    if (isset($this->params['pass'][0]))
        $url = array($this->params['pass'][0]);
    echo $this->Form->create($model, array('url' => $url, 'type' => 'file'));
    echo $this->MyForm->inputs(array_merge($fields, array('legend' => __('Create %s', $singularName))));
    echo $this->Form->submit(__('Add', true), array('class' => 'button'));
    echo $this->Form->end();
    ?>
</div>