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
        <th><?php echo __('Settings'); ?></th>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>  
    <tr>
        <td></td>
        <td><?php echo __('Allow deposits'); ?></td>
        <td><?php echo $this->Form->input($data['deposits']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['deposits']['value'])); ?></td>
    </tr>
    <tr>
        <td></td>
        <td><?php echo __('Manual deposits'); ?></td>
        <td><?php echo $this->Form->input($data['D_Manual']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['D_Manual']['value'])); ?></td>
    </tr>    
</table>

<table class="items">

    <tr>
        <th width="300px"><?php echo __('Payments'); ?></th>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>      


    <tr>
        <th rowspan="2"><?php echo $this->Html->image('admin/payments/UMF.png'); ?></th>
        <td><?php echo __('UMF'); ?></td>
        <td><?php echo $this->Form->input($data['D_Umf']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['D_Umf']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('UMF seller'); ?></td>
        <td><?php echo $this->Form->input($data['D_UmfSeller']['id'], array('value' => $data['D_UmfSeller']['value'])); ?></td>
    </tr>
    
    <tr>
        <th rowspan="2"><?php echo $this->Html->image('voguepay.png'); ?></th>
        <td><?php echo __('VoguePay'); ?></td>
        <td><?php echo $this->Form->input($data['D_VoguePay']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['D_VoguePay']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('VoguePay merchant Id'); ?></td>
        <td><?php echo $this->Form->input($data['D_VoguePayMerchantId']['id'], array('value' => $data['D_VoguePayMerchantId']['value'])); ?></td>
    </tr>

    <tr>
        <th rowspan="2"><?php echo $this->Html->image('admin/payments/BARDO.gif'); ?></th>
        <td><?php echo __('Bardo'); ?></td>
        <td><?php echo $this->Form->input($data['D_Bardo']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['D_Bardo']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Bardo shop id'); ?></td>
        <td><?php echo $this->Form->input($data['D_BardoShopId']['id'], array('value' => $data['D_BardoShopId']['value'])); ?></td>
    </tr>
    <!---
    <tr>
                <td><?php echo __('Bardo Deposit percentage fee'); ?></td>
        <td><?php echo $this->Form->input($data['bardo_deposit_funding_percentage']['id'], array('value' => $data['bardo_deposit_funding_percentage']['value'])); ?></td>
    </tr>
    -->


    <tr>
        <th rowspan="2"><?php echo $this->Html->image('admin/payments/Eyowo.png'); ?></th>
        <td><?php echo __('Eyowo'); ?></td>
        <td><?php echo $this->Form->input($data['D_Eyowo']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['D_Eyowo']['value'])); ?></td>
    </tr>
    <tr>

        <td><?php echo __('Wallet code'); ?></td>
        <td><?php echo $this->Form->input($data['D_EyowoWalletCode']['id'], array('value' => $data['D_EyowoWalletCode']['value'])); ?></td>
    </tr>



    <tr>
        <th rowspan="3"><?php echo $this->Html->image('admin/payments/vtn.png'); ?></th>
        <td><?php echo __('VTN'); ?></td>
        <td><?php echo $this->Form->input($data['D_Vtn']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['D_Vtn']['value'])); ?></td>
    </tr>
    <tr>

        <td><?php echo __('VTN merchant email'); ?></td>
        <td><?php echo $this->Form->input($data['D_VtnMerchantEmailId']['id'], array('value' => $data['D_VtnMerchantEmailId']['value'])); ?></td>
    </tr>
    <tr>

        <td><?php echo __('VTN call back id'); ?></td>
        <td><?php echo $this->Form->input($data['D_VtnCallbackId']['id'], array('value' => $data['D_VtnCallbackId']['value'])); ?></td>
    </tr>


</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>