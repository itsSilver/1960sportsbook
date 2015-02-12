<div class="side-menu">
    <h3><?php echo __('Sport\'s menu'); ?></h3>
    <div id="sports">        
    </div>

    <script type="text/javascript">
        function getSports() {        
            jQuery('#sports').load('<?php echo $this->Html->url(array('controller' => 'sports', 'action' => 'getSports')); ?>', function() {            
            });
        }
        getSports();
    </script>
</div>