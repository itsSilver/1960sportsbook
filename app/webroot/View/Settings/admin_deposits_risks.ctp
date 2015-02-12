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
        <td><?php echo __('Min deposit'); ?></td>
        <td><?php echo $this->Form->input($data['minDeposit']['id'], array('value' => $data['minDeposit']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Max deposit'); ?></td>
        <td><?php echo $this->Form->input($data['maxDeposit']['id'], array('value' => $data['maxDeposit']['value'])); ?></td>
    </tr>
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>