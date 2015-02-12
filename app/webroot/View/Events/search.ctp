<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.event-more').each(function() {
            $(this).click(function() {
                var el = $(this).parent().parent().parent().children('.other-bets');                
                el.animate({
                    height: 'toggle'
                }, 800);
            })
        }); 
        $('.bet-odd').each(function() {
            $(this).click(function() {
                addBet($(this).attr('title'));
            })
        }); 
        
    });
     
</script>

   <div id="sportsList" class="display">
        <?php if (!empty($events)): ?>

    <div id="itemListing">
            <?php foreach ($events as $event): ?>
                
                            <?php echo $this->Beth->makeNiceBet($event); ?>
            <?php endforeach; ?> 
</div>

        <?php else: ?>
            <?php echo $this->Session->flash(); ?>
            <?php if ($id == true): ?>
                <div>
                    <?php echo $this->Form->create('Event', array('url' => array('controller' => 'events', 'action' => 'search'), 'id' => 'SportSearchForm')); ?>
                    <?php echo $this->Form->input('id', array('class' => 'regi', 'type' => 'text', 'label' => 'Search another event ID')); ?>
                    <div class="lefted">
                        <?php echo $this->Form->submit(__('Search', true), array('class' => 'button')); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            <?php else: ?>
                <div>
                    <?php echo $this->Form->create('Event', array('url' => array('controller' => 'events', 'action' => 'search'), 'id' => 'SportSearchForm')); ?>
                    <?php echo $this->Form->input('name', array('class' => 'regi', 'type' => 'text', 'label' => 'Search another event name')); ?>
                    <div class="lefted">
                        <?php echo $this->Form->submit(__('Search', true), array('class' => 'button')); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
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





