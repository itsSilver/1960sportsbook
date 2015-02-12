<?php
$data = $this->requestAction('sports/getLastMinutebets');

//echo '<pre>';print_r($data);echo '</pre>';die;
?>
<?php if (!empty($data)): ?>

    <div id="last-minute-bets"> 
        <h3><?php echo __('Last Minute Bets'); ?></h3>
        <div id="events">
            <?php foreach ($data as $event): ?>
                <div class="event"> 
                    <div class="main-bet">
                        <div class="event-date">
			  <?php echo $this->Beth->getRemainingTime($event['Event']['date']); ?>
			</div>
                        <?php
                        if ($event['Event']['count'] == 1) {
                            $name = $event['League']['name'] . ' - ' . $event['Event']['name'];
                        } else {
                            $name = $event['League']['name'] . ' - ' . __('%d events available', $event['Event']['count']);
                        }
                        ?>
                        <div class="event-title"><?php echo $this->Html->link($name, array('controller' => 'sports', 'action' => $event['League']['id'])); ?></div>  
                    </div>
                </div>


    <?php endforeach; ?>

        </div>
    </div>   

<?php endif; ?>