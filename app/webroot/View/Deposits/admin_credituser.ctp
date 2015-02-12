<div id="deposits" class="index">
    <h3><?php echo __('Credit Players Account'); ?></h3>

    <?php echo $this->Session->flash(); ?>

    <?php if (!empty($data)): ?>

        <table class="default-table">

           

        </table>

   

    <?php endif; ?>

    <div class="centered">
        <?php echo $this->MyHtml->spanLink(__('Make Deposit'), array('action' => 'choose'), array('class' => 'button-blue')); ?>
    </div>
</div>