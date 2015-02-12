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
        <td><?php echo __('Current jackpot'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotSize']['id'], array('value' => $data['jackpotSize']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Starting pot'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotStart']['id'], array('value' => $data['jackpotStart']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Pot increase'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotIncrease']['id'], array('value' => $data['jackpotIncrease']['value'])); ?></td>
    </tr>    
    <tr>
        <td><?php echo __('7-predictions to get 7 right'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotPicks7']['id'], array('value' => $data['jackpotPicks7']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('8-predictions to get 7 right'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotPicks8']['id'], array('value' => $data['jackpotPicks8']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('9-predictions to get 7 right'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotPicks9']['id'], array('value' => $data['jackpotPicks9']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('10-predictions to get 7 right'); ?></td>
        <td><?php echo $this->Form->input($data['jackpotPicks10']['id'], array('value' => $data['jackpotPicks10']['value'])); ?></td>
    </tr>
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>