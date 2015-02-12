<div class="sidebar-box"> 
    <h3><?php echo __('Search'); ?></h3>
    <div id="searchBox">
        <?php echo $this->Form->create('Event', array('url' => array('controller' => 'events', 'action' => 'search'), 'id' => 'SportSearchForm')); ?>
        
        <?php echo $this->Form->input('id', array('type' => 'text', 'value' => __('Event ID', true), 'id' => 'search-event-id', 'label' => false)); ?>
        <?php echo $this->Form->input('name', array('value' => __('Event name', true), 'id' => 'search-event-name', 'label' => false)); ?>
        <?php echo $this->Form->submit(__('Search'), array('class' => 'button')); ?>
        
        
        <?php echo $this->Form->end(); ?>
    </div>

</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#search-event-id').click(function(){
            $(this).val('');
        });
        $('#search-event-name').click(function(){
            $(this).val('');
        });
    });     
</script>