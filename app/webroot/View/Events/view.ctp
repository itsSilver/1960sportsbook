<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

<div id="message"><a href="#top">Scroll to top</a></div>

<div id="events">
    <?php if (!empty($event)): ?>        
        
	<h3 style="font-size:13px;height:auto;padding:5px;">
	<?php echo $event['Event']['id']; ?> <?php echo $event['Event']['name']; ?>        
	<?php if(isset($this->groupid) && ($this->groupid !='8')) { ?>   
	<?php //echo $this->Html->link(__('Add bet', true), array('controller' => 'bets', 'action' => 'add', $event['Event']['id']), array('class' => 'button', 'style'=> 'float:right;padding:0px;')); ?>
	<?php } ?>
	</h3><br>
	

        <?php if (!empty($data)): ?>

            <?php foreach ($data as $bet): ?>
         
		<h3 style="font-size:12px;height:auto;">
		   <?php echo __('ID: '); ?> <?php echo $bet['Bet']['id']; ?> <?php echo $bet['Bet']['name']; ?>
		</h3><br>
        
                
                 <table style="padding-left:5px;" class="items" cellpadding="10" cellspacing="0">
                    <tr>
                        <th style="background:#003366;color:#fff;padding:5px;"><?php echo __('Name'); ?></th>
			<th style="background:#003366;color:#fff;padding:5px;"></th>
                        <th style="background:#003366;color:#fff;padding:5px 65px ;text-align:right;"><?php echo __('Odd'); ?></th>
                    </tr>
 
                    <?php
                    $i = 1;
                    foreach ($bet['BetPart'] as $betPart):
                        $class = null;
                        if ($i++ % 2 == 0) {
                            $class = ' alt';
                        }
                        ?>
                        <tr>
                            <td style="padding:5px;" class="<?php echo $class; ?>"><?php echo $betPart['name']; ?></td>
			    <td style="padding:5px;" class="<?php echo $class; ?>"></td>
                            <td style="padding:5px 50px ;float:right;" class="<?php echo $class; ?>">

			    <?php echo $this->Html->link($betPart['odd'] , '#', array('class' => 'OddsButtonClickView'.$betPart['id'].' OddsButton','id' => 'betpartviewvalue'.$betPart['id'].'','title' => $betPart['id'])); ?>
			    
			   <?php if(!isset($this->groupid) || (isset($this->groupid) && $this->groupid !='8')) {?>
				<script type="text/javascript"> 
				    jQuery(document).ready(function(jQuery) { 
					jQuery('.OddsButtonClickView<?php echo trim($betPart['id']);?>').each(function() {
					    jQuery(this).click(function() {
						var betodd = jQuery("#betpartviewvalue<?php echo trim($betPart['id']);?>").html();
						if(betodd > 0){	
						addBet(jQuery("#betpartviewvalue<?php echo trim($betPart['id']);?>").attr('title'));
						}
					    })
					}); 
					
				    });    
				</script>
			    <?php } ?>

			    </td>			   		    
			   </td>
                        </tr>		

                    <?php endforeach; ?>               
		    
		</table>
              
            <?php endforeach; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php echo __('Can not find event'); ?>
    <?php endif; ?>

</div>

<script type="text/javascript"> 
jQuery(function () { // run this code on page load (AKA DOM load)
 
	/* set variables locally for increased performance */
	var scroll_timer;
	var displayed = false;
	var $message = jQuery('#message a');
	var $window = jQuery(window);
	var top = jQuery(document.body).children(0).position().top;
 
	/* react to scroll event on window */
	$window.scroll(function () {
		window.clearTimeout(scroll_timer);
		scroll_timer = window.setTimeout(function () { // use a timer for performance
			if($window.scrollTop() <= top) // hide if at the top of the page
			{
				displayed = false;
				$message.fadeOut(500);
			}
			else if(displayed == false) // show if scrolling down
			{
				displayed = true;
				$message.stop(true, true).show().click(function () { $message.fadeOut(500); });
			}
		}, 100);
	});
});
</script>