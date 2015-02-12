<h1 style="background-color: red;">Top Jackopeters, which like gueeses</h1>
<?php $jackpoters = $this->requestAction('JackpotWinnings/getMonthTop/'.$limit); // there are parameter $limit! ?>
<?php foreach($jackpoters as $jackpot) : ?>
<?php echo $jackpot['User']['username'].'<br/>'; ?>
<?php endforeach; ?>
 