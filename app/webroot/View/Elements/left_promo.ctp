<?php if (Configure::read('Settings.left_promo_enabled')): ?>
    <div class="sidebar-box"> 
	<h3><?php echo Configure::read('Settings.left_promo_header')?></h3>
	<div id="searchBox">
	    <?php echo Configure::read('Settings.left_promo_body')?>
	</div>
    </div>
<?php endif;?>