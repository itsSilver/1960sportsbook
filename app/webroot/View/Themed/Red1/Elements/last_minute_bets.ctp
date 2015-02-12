<div class="white">
    
<?php
$data = $this->requestAction('sports/getLastMinutebets');
?>
<?php if (!empty($data)): ?>
    <h3  class="la"><?php echo __('Last Minute Bets'); ?></h3>
    <div class="box">
        <?php foreach ($data as $event): ?>
            <?php
            if ($event['Event']['count'] == 1) {
                $name = $event['League']['name'] . ' - ' . $event['Event']['name'];
            } else {
                $name = $event['League']['name'] . ' - ' . __('%d events available', $event['Event']['count']);
            }
            ?>
            <div class="last"><span><?php echo $this->Beth->getRemainingTime($event['Event']['date']); ?></span><?php echo $this->Html->link($name, array('controller' => 'sports', 'action' => $event['League']['id'])); ?><div class="clear"></div></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>