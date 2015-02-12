<div id="backButton" class="hiden">
    <a onclick="backToSports()" href="#">&lt;&lt; <?php echo __('Back'); ?></a>
</div>

<?php if(!empty($lotteryDatainfo)){?>

	<?php foreach ($lotteryDatainfo as $LotteryType => $lotteryAll) {?>

	      <h4 style="color:red;font-size:18px;font-weight:bold;padding:10px;"><?php echo __($LotteryType); ?></h4>

	      <?php foreach ($lotteryAll as $key => $lotterys) {?>
		    <ul class="sidemenu">
			<?php $link = $this->Html->link($lotterys['name'], array('controller' => 'lotterys', 'action' => view,$lotterys['id'],about));
			echo '<li class="gr' . $lottery_gr . ' menuMarker' . $lotterys['id'] . '">' . $link . '</li>';?>
		    </ul>

	      <?php } ?>

	<?php } ?>

<?php } else { ?>

    <h4 style="color:red;font-size:18px;font-weight:bold;padding:10px;"><?php echo __('No Lottery menu.'); ?></h4>

<?php } ?>