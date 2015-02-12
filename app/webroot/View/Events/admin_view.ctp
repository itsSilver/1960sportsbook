<div id="events-view">
    <?php if (!empty($event)): ?>        
        
	<h2><?php echo $event['Event']['id']; ?> <?php echo $event['Event']['name']; ?></h2>
        
	<?php echo $this->Html->link(__('Add bet', true), array('controller' => 'bets', 'action' => 'add', $event['Event']['id']), array('class' => 'button')); ?>
        <?php if (!empty($data)): ?>

            <?php foreach ($data as $bet): ?>
         
            <h3><?php echo __('ID: '); ?> <?php echo $bet['Bet']['id']; ?> <?php echo $bet['Bet']['name']; ?></h3>
        
                
                 <table class="items" cellpadding="0" cellspacing="0">
                    <tr>
                        <th><?php echo __('Name'); ?></th>
                        <th><?php echo __('Odd'); ?></th>                   
                    </tr>
 
                    <?php
                    $i = 1;
                    foreach ($bet['BetPart'] as $betPart):
                        $class = null;
                        if ($i++ % 2 == 0) {
                            $class = ' alt';
                        }
                        ?>
                        <tr>
                            <td class="<?php echo $class; ?>"><?php echo $betPart['name']; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $betPart['odd']; ?></td>                    

                        </tr>

                    <?php endforeach; ?>
                </table>
 <?php echo $this->Html->link(__('Edit', true), array('controller' => 'bets', 'action' => 'edit', $bet['Bet']['id'])); ?>
                <?php echo $this->Html->link(__('Delete', true), array('controller' => 'bets', 'action' => 'delete', $bet['Bet']['id'])); ?>
              
            <?php endforeach; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php echo __('Can not find event'); ?>
    <?php endif; ?>

</div>