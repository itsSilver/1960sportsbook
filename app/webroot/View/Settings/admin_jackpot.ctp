<h3><?php echo __('Jackpot settings'); ?></h3>

<?php echo $this->Session->flash(); ?>

<?php
$options = array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false)
);
echo $this->Form->create('Setting', $options);
$yesNoOptions = array('1' => 'Yes', '0' => 'No');
?>

<table class="items">

    <tr>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>   
    <tr>
        <td><?php echo __('Enable Jackpot'); ?></td>
        <td><?php echo $this->Form->input($data['jackpot']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['jackpot']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Jackpot Percent (i.e 0.1)'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotPercent']['id'], array('value' => $data['jackpotPercent']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Jackpot Minimum Odds (i.e 1.3)'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotMinOdds']['id'], array('value' => $data['jackpotMinOdds']['value'])); ?></td>
    </tr>    
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>