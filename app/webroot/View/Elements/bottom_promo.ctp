<?php if (Configure::read('Settings.bottom_promo_enabled')): ?>
    <div id="bottom_promo"> 
        <?php echo Configure::read('Settings.bottom_promo_header') ?>
        <div>
            <?php echo Configure::read('Settings.bottom_promo_body') ?>
        </div>
    </div>
<?php endif; ?>