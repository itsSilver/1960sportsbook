<div class="sidebar-box"> 
    <h3><?php echo __('Sport\'s menu'); ?></h3>
    <div id="sports">
        <div id="sports-loading"></div>
    </div>

    <script type="text/javascript">
        function getSports() {        
            jQuery('#sports').load('<?php echo $this->Html->url(array('controller' => 'sports', 'action' => 'getSports')); ?>', function() {            
            });
        }
        getSports();
    </script>
</div>