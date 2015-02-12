<div class="sidebar-box"> 
    <h3><?php echo __('Search'); ?></h3>
    <div id="searchBox">
        <?php echo $this->Form->create('LotteryTicket', array('url' => array('controller' => 'LotteryTickets', 'action' => 'search'), 'id' => 'LotteryTicketSearchForm')); ?>        
        <?php echo $this->Form->input('ticket_id', array('type' => 'text', 'value' => '' , 'id' => 'search-ticket-id', 'label' => false , 'placeholder' => 'TICKET ID')); ?>
        <?php echo $this->Form->submit(__('Search'), array('class' => 'button')); ?>        
        <?php echo $this->Form->end(); ?>
    </div>

</div> 

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#LotteryTicketSearchForm').click(function(){
            if($("#search-ticket-id").val()==''){	     
	      return false;
	    } else {	      
	      return true;
	    }
        });
    });     
</script>