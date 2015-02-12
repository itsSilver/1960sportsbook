<div id="users" class="bonus">
    <h3><?php echo __('Promotional Code'); ?></h3>
    <h4><?php echo __('Please add promotional code below to get additional money or other benefit.'); ?></h4>
    <?php
    echo $this->Session->flash();
    if (!isset($success)) {
        echo $this->Form->create('User');

        echo $this->Form->input('bonus_code', array('label' => __('Promotional Code', true), 'class' => 'regi'));
        ?>
        <div class="lefted">
            <?php echo $this->MyHtml->spanLink(__('Submit bonus code', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#UserBonusForm').submit()")); ?>
        </div>
        <?php
        echo $this->Form->end();
    }
    ?>
</div>

