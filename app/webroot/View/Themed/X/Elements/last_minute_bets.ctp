<?php
$data = $this->requestAction('sports/getLastMinutebets');
?>
<?php if (!empty($data)): ?>

    <div class="mid_box" style="margin-top:5px;">
        <div class="mid_box_title">
            <div class="showtime">Last minute bets</div>
            <div class="show_hide"></div>            
        </div>
        <div class="mid_box_mid">        

            <?php foreach ($data as $event): ?>
                <?php
                if ($event['Event']['count'] == 1) {
                    $name = $event['League']['name'] . ' - ' . $event['Event']['name'];
                } else {
                    $name = $event['League']['name'] . ' - ' . __('%d events available', $event['Event']['count']);
                }
                ?>
                <div class="last_minte_bet">
                    <div class="sporticon" style="background-position:-152px -19px;"></div>
                    <div style="width:390px;">
                        <?php echo $this->Html->link($name, array('controller' => 'sports', 'action' => $event['League']['id']), array('class' => 'color_yellow')); ?>
                    </div>
                    <div class="color_cyan" style="width:58px; text-align:right;"><?php echo $this->Beth->getRemainingTime($event['Event']['date']); ?></div>
                    <div class="clear"></div>
                </div>
            <?php endforeach; ?>

            <div class="clear"></div>
        </div>
    </div>   

<?php endif; ?>