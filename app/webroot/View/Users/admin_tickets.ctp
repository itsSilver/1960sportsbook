
<div class="tickets index">
    <?php echo $this->Session->flash(); ?>
    <h2><?php echo __('Tickets'); ?></h2>

    <?php
    echo $this->element('admin/list');
    ?>

</div>

