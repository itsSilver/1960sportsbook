<h3><?php echo __('Risk Management'); ?></h3>

<?php echo $this->Session->flash(); ?>

<h4><?php echo __('Set general settings'); ?></h4>

<?php
$options = array(
    'url' => array(
        'controller' => 'risks'
    ),
    'inputDefaults' => array(
        'label' => false,
        'div' => false)
);
echo $this->Form->create('Setting', $options);
?>

<table class="items">    
    <tr>        
        <td><?php echo __('Minimum bet amount for all bets'); ?></td>
        <td><?php echo $this->Form->input($settings['minBet']['id'], array('value' => $settings['minBet']['value'])); ?></td>
    </tr>
    <tr>        
        <td><?php echo __('Maximum bet amount for all bets'); ?></td>
        <td><?php echo $this->Form->input($settings['maxBet']['id'], array('value' => $settings['maxBet']['value'])); ?></td>
    </tr>
    <tr>        
        <td><?php echo __('Maximum wining from ticket'); ?></td>
        <td><?php echo $this->Form->input($settings['maxWin']['id'], array('value' => $settings['maxWin']['value'])); ?></td>
    </tr>   
    <tr>
        <td><?php echo __('Max bets per ticket'); ?></td>
        <td><?php echo $this->Form->input($settings['maxBetsCount']['id'], array('value' => $settings['maxBetsCount']['value'])); ?></td>
    </tr>
    <tr>
        <td><?php echo __('Min bets per ticket'); ?></td>
        <td><?php echo $this->Form->input($settings['minBetsCount']['id'], array('value' => $settings['minBetsCount']['value'])); ?></td>
    </tr>
</table>

<?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
<?php echo $this->Form->end(); ?>