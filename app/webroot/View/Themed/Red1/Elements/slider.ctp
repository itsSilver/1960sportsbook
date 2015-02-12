<div id="cycle-slider-c">
    
</div>
<script type="text/javascript">
    function getSlides() {        
        jQuery('#cycle-slider-c').load('<?php echo $this->Html->url(array('controller' => 'slides', 'action' => 'getSlides')); ?>', function() {            
        });
    }
    getSlides();
</script>