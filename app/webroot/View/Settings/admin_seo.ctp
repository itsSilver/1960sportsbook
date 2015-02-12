<h3><?php echo __('SEO Settings'); ?></h3>

<?php echo $this->Session->flash(); ?>

<?php
$options = array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false)
);
echo $this->Form->create('Setting', $options);
?>

<table class="items">

    <tr>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Value'); ?></th>
    </tr>
    
    <tr>
        <td><?php echo __('Title'); ?></td>
        <td><?php echo $this->Form->input($data['defaultTitle']['id'], array('value' => $data['defaultTitle']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Keywords'); ?></td>
        <td><?php echo $this->Form->input($data['metaKeywords']['id'], array('value' => $data['metaKeywords']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Description'); ?></td>
        <td><?php echo $this->Form->input($data['metaDescription']['id'], array('value' => $data['metaDescription']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Reply to email'); ?></td>
        <td><?php echo $this->Form->input($data['metaReplayTo']['id'], array('value' => $data['metaReplayTo']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Copyright:'); ?></td>
        <td><?php echo $this->Form->input($data['metaCopyright']['id'], array('value' => $data['metaCopyright']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Bot content revisit time'); ?></td>
        <td><?php echo $this->Form->input($data['metaRevisitTime']['id'], array('value' => $data['metaRevisitTime']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Identifier url link:'); ?></td>
        <td><?php echo $this->Form->input($data['metaIdentifierUrl']['id'], array('value' => $data['metaIdentifierUrl']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Author'); ?></td>
        <td><?php echo $this->Form->input($data['metaAuthor']['id'], array('value' => $data['metaAuthor']['value'])); ?></td>
    </tr>
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>