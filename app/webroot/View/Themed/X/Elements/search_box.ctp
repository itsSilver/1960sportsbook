<div id="search_event">
    <?php echo $this->Form->create('Event', array('url' => array('controller' => 'events', 'action' => 'search'), 'id' => 'search_event_form')); ?>
     <?php echo $this->Form->input('id', array('type' => 'text', 'value' => __('Your event id', true), 'id' => 'event_id', 'label' => false)); ?>
        
        <?php echo $this->Form->input('name', array('value' => __('Event name', true), 'id' => 'event_name', 'label' => false)); ?>
        
        <div id="search_event_btn" onClick="$('#search_event_form').submit();"><div style="float:left; width:62px;">SEARCH</div><div style="float:left; padding-top:6px;">
                <?php echo $this->Html->image('search-logo.png'); ?>
            </div></div>
    <?php echo $this->Form->end(); ?>
</div>
