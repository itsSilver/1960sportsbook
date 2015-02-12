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
        <td><?php echo __('Min withdraw'); ?></td>
        <td><?php echo $this->Form->input($data['minWithdraw']['id'], array('value' => $data['minWithdraw']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Max withdraw'); ?></td>
        <td><?php echo $this->Form->input($data['maxWithdraw']['id'], array('value' => $data['maxWithdraw']['value'])); ?></td>
    </tr>
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>