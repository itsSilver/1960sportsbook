
<div id="sports">        
</div>

<script type="text/javascript">
    function getSports() {        
        jQuery('#sports').load('<?php echo $this->Html->url(array('controller' => 'sports', 'action' => 'getSports')); ?>', function() {            
        });
    }
    getSports();
</script>
