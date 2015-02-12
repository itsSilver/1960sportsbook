<?php if (Configure::read('Settings.right_promo_enabled')): ?>
<div class="box">
   
    <h3><?php echo Configure::read('Settings.right_promo_header')?></h3>
    <div>
    	<?php echo Configure::read('Settings.right_promo_body')?>
	</div>
</div>
<?php endif; ?>