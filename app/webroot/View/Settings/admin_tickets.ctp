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
        <td><?php echo __('Ticket printing'); ?></td>
        <td><?php echo $this->Form->input($data['printing']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['printing']['value'])); ?></td>
    </tr>  
    <tr>
        <td><?php echo __('Ticket preview'); ?></td>
        <td><?php echo $this->Form->input($data['ticketPreview']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['ticketPreview']['value'])); ?></td>
    </tr>  
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>