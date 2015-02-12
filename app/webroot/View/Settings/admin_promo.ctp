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

<table class="items" style="float:left; width: 49%">
    <tr>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>
    <tr>
        <td><?php echo __('Left active'); ?></td>
        <td><?php echo $this->Form->input($data['left_promo_enabled']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['left_promo_enabled']['value'])); ?></td>
    </tr>     
        
    <tr>
        <td><?php echo __('Left promo box header'); ?></td>
        <td><?php echo $this->Form->input($data['left_promo_header']['id'], array('value' => $data['left_promo_header']['value'])); ?></td>
    </tr>       
    <tr>
        <td><?php echo __('Left promo box body'); ?></td>
        <td><?php echo $this->Form->textarea($data['left_promo_body']['id'], array('value' => $data['left_promo_body']['value'], 'id' => 'epic')); ?></td>
    </tr>   
     
</table>
<table class="items" style="float:left;width: 49%">
    <tr>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>   
    <tr>
        <td><?php echo __('Right active'); ?></td>
        <td><?php echo $this->Form->input($data['right_promo_enabled']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['right_promo_enabled']['value'])); ?></td>
    </tr> 
    <tr>
        <td><?php echo __('Right promo box header'); ?></td>
        <td><?php echo $this->Form->input($data['right_promo_header']['id'], array('value' => $data['right_promo_header']['value'])); ?></td>
    </tr>   
      <tr>
        <td><?php echo __('Right promo box body'); ?></td>
        <td><?php echo $this->Form->textarea($data['right_promo_body']['id'], array('value' => $data['right_promo_body']['value'])); ?></td>
    </tr>   
</table>
<table class="items" style="float:left;width: 49%">
    <tr>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>   
    <tr>
        <td><?php echo __('Bottom active'); ?></td>
        <td><?php echo $this->Form->input($data['bottom_promo_enabled']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['bottom_promo_enabled']['value'])); ?></td>
    </tr> 
    <tr>
        <td><?php echo __('Bottom promo box header'); ?></td>
        <td><?php echo $this->Form->input($data['bottom_promo_header']['id'], array('value' => $data['bottom_promo_header']['value'])); ?></td>
    </tr>   
    <tr>
        <td><?php echo __('Bottom promo box body'); ?></td>
        <td><?php echo $this->Form->textarea($data['bottom_promo_body']['id'], array('value' => $data['bottom_promo_body']['value'])); ?></td>
    </tr>   

</table>
<div style="clear:both;"></div>
<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>