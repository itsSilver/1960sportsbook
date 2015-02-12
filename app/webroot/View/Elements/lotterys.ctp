<div class="sidebar-box">

    <h3><?php echo __('Lottery\'s menu'); ?></h3>
    <div id="lottery">
        <div id="sports-loading"></div>
    </div>

    <script type="text/javascript">
        function fungetLottery() {
            jQuery('#lottery').load('<?php echo $this->Html->url(array('controller' => 'Lotterys', 'action' => 'getLottery')); ?>', function() {});
        }
        fungetLottery();
    </script>

</div>