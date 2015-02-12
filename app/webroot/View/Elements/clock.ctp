<?php
$timeZone = 0;
if ($this->Session->check('Auth.User.time_zone')) {
    $timeZone = $this->Session->read('Auth.User.time_zone');
}
App::uses('TimeHelper', 'View/Helper');
$this->Time = new TimeHelper($this);
$time = $this->Time->format('Y-m-d H:i:s', gmdate('Y-m-d H:i:s'), null, $timeZone);
$time = $this->Time->gmt($time) * 1000;
if ($timeZone >= 0)
    $timeZone = '+' . sprintf('%04d', $timeZone * 100);
else
    $timeZone = '-' . sprintf('%04d', -$timeZone * 100);
?>
<script type="text/javascript">
    jQuery(document).ready(function($){    
        var options = {
            format: '%H:%M, %d of %B GMT<?php echo $timeZone; ?>',
            utc: true,        
            seedTime: <?php echo $time; ?>
        }
        jQuery('.jclock').jclock(options);
    });
</script>