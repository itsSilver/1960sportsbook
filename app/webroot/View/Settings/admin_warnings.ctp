<h3><?php echo __('Warnings settings'); ?></h3>

<?php echo $this->Session->flash(); ?>

<?php
$options = array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false)
);
echo $this->Form->create('Setting', $options);
$yesNoOptions = array('1' => 'Yes', '0' => 'No');
$timezones = $this->TimeZone->getTimeZones();
?>

<table class="items">

    <tr>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>   
    <tr>
        <td><?php echo __('Warning deposit from'); ?></td>
        <td><?php echo $this->Form->input($data['bigDeposit']['id'], array('value' => $data['bigDeposit']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Warning withdraws from'); ?></td>
        <td><?php echo $this->Form->input($data['bigWithdraw']['id'], array('value' => $data['bigWithdraw']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Warning stakes from'); ?></td>
        <td><?php echo $this->Form->input($data['bigStake']['id'], array('value' => $data['bigStake']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Warning odds from'); ?></td>
        <td><?php echo $this->Form->input($data['bigOdd']['id'], array('value' => $data['bigOdd']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Warning winnings from'); ?></td>
        <td><?php echo $this->Form->input($data['bigWinning']['id'], array('value' => $data['bigWinning']['value'])); ?></td>
    </tr>
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>