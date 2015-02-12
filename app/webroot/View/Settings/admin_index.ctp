<h3><?php echo __('General Settings'); ?></h3>

<?php echo $this->Session->flash(); ?>

<?php
$options = array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false)
);
echo $this->Form->create('Setting', $options);
$yesNoOptions = array('1' => 'Yes', '0' => 'No');
$feedTypes = array('nordicbet' => 'NordicBet', 'line' => 'line.com');
$timezones = $this->TimeZone->getTimeZones();
$themes = $this->Beth->getThemesList();
?>

<table class="items">

    <tr>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>
    <tr>
        <td><?php echo __('Website name'); ?></td>
        <td><?php echo $this->Form->input($data['websiteName']['id'], array('value' => $data['websiteName']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Copyright'); ?></td>
        <td><?php echo $this->Form->input($data['copyright']['id'], array('value' => $data['copyright']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Events date format'); ?></td>
        <td><?php echo $this->Form->input($data['eventDateFormat']['id'], array('value' => $data['eventDateFormat']['value'])); ?></td>
    </tr>   
    <tr>
        <td><?php echo __('Contact Email'); ?></td>
        <td><?php echo $this->Form->input($data['contactMail']['id'], array( 'value' => $data['contactMail']['value']) ); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Registration'); ?></td>
        <td><?php echo $this->Form->input($data['registration']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['registration']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Login'); ?></td>
        <td><?php echo $this->Form->input($data['login']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['login']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Password reset'); ?></td>
        <td><?php echo $this->Form->input($data['passwordReset']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['passwordReset']['value'])); ?></td>
    </tr>   
    <tr>
        <td><?php echo __('Default currency'); ?></td>
        <td><?php echo $this->Form->input($data['defaultCurrency']['id'], array('type' => 'select', 'options' => $currencies, 'value' => $data['defaultCurrency']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Default timezone:'); ?></td>
        <td><?php echo $this->Form->input($data['defaultTimezone']['id'], array('type' => 'select', 'options' => $timezones, 'value' => $data['defaultTimezone']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Default language'); ?></td>
        <td><?php echo $this->Form->input($data['defaultLanguage']['id'], array('type' => 'select', 'options' => $locales, 'value' => $data['defaultLanguage']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Charset'); ?></td>
        <td><?php echo $this->Form->input($data['charset']['id'], array('value' => $data['charset']['value'])); ?></td>
    </tr>   
    <tr>
        <td><?php echo __('Items per page'); ?></td>
        <td><?php echo $this->Form->input($data['itemsPerPage']['id'], array('value' => $data['itemsPerPage']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Default theme'); ?></td>
        <td><?php echo $this->Form->input($data['defaultTheme']['id'], array('type' => 'select', 'options' => $themes, 'value' => $data['defaultTheme']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Referals'); ?></td>
        <td><?php echo $this->Form->input($data['referals']['id'], array('type' => 'select', 'options' => $yesNoOptions,'value' => $data['referals']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Show event ID for main event'); ?></td>
        <td><?php echo $this->Form->input($data['show_main_event_id']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['show_main_event_id']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Show event ID for sub events'); ?></td>
        <td><?php echo $this->Form->input($data['show_sub_event_id']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['show_sub_event_id']['value'])); ?></td>
    </tr>   
    <tr>
        <td><?php echo __('Feed type'); ?></td>
        <td><?php echo $this->Form->input($data['feedType']['id'], array('type' => 'select', 'options' => $feedTypes, 'value' => $data['feedType']['value'])); ?></td>
    </tr> 
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>