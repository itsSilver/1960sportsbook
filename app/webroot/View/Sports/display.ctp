<div id="message"><a href="#top">Scroll to top</a></div>
<div id="sportsList" class="display">
    <div id="top"></div>
    <div id="filterBox">
    <?php foreach($betTypes AS $key=>$bet):?>
    
        <a href="#" class="filterLink<?php if((!isset($this->params['pass'][1])&&$key=='All')) echo ' filterActive'; elseif((isset($this->params['pass'][1])&&$key==$this->params['pass'][1])) echo ' filterActive'; ?>" onclick="gto('<?php echo ($key=='All' ? '':$key); ?>')"><?php echo $bet; ?></a>
    
    <?php endforeach; ?>
    </div>
<div class="spacer"></div>

<div id="itemListing">
    <?php foreach ($events as $sport): ?>
        <?php if (isset($sport['League'])): ?>
            <?php foreach ($sport['League'] as $league): ?>
    
                <?php if (!empty($league['Event'])): ?>

                    
                    <?php foreach ($league['Event'] as $event): ?>
  
                    
                <?php echo $this->Beth->makeNiceBet($event); ?>


            <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>

</div>
<br class="clear" />
</div>

<script type="text/javascript"> 
    jQuery(document).ready(function($) { 
        $('.OddsButton').each(function() {
            $(this).click(function() {
                addBet($(this).attr('title'));
            })
        }); 
        
    });    
    function filter() {
        var type = jQuery('#bet-type').val()
        window.location.href = '<?php echo $url; ?>' + '/' + type;
    }
    function gto( type ) {
       window.location.href = '<?php echo $url; ?>' + '/' + type;
    }
</script>

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