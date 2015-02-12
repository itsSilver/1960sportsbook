<div id="users" class="settings">

    <h3><?php echo __('Settings'); ?></h3>

    <h4><?php echo __('Please set up options for more comfortable view.'); ?></h4>


    <?php echo $this->Session->Flash(); ?>
    <?php
    echo $this->Form->create('User', array(
        'action' => 'settings',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class' => 'regi',
            # define error defaults for the form    
            'error' => array(
                'wrap' => 'span',
                'class' => 'my-error-class'
            )
        )
    ));
    ?>
    <table class="default-table borderless">

        <tr>
            <td><label><?php echo __('Odds type'); ?></label></td>
            <td><?php echo $this->Form->input('odds_type', array('type' => 'select', 'options' => $this->Beth->getOddsTypes())); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Time zone'); ?></label></td>
            <td><?php echo $this->Form->input('time_zone', array('type' => 'select', 'options' => $this->TimeZone->getTimeZones())); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Language'); ?></label></td>            
            <td><?php echo $this->Form->input('language_id', array('type' => 'select', 'options' => $locales)); ?></td>
        </tr>

    </table>
    <div class="lefted">
        <?php echo $this->MyHtml->spanLink(__('Confirm changes', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#UserSettingsForm').submit()")); ?>
    </div>
    <?php echo $this->Form->end(); ?>

</div>