<div class="results index">
    <?php if (!empty($data)): ?>

        <?php echo $this->Form->create('Result'); ?>

        <?php foreach ($data as $event): ?>

            <h2><?php echo $event['Event']['name']; ?> <?php echo __('Results'); ?></h2>
            <?php echo $this->Form->input('Result', array('label' => __('Result', true), 'value' => $event['Event']['result'], 'name' => "data[{$event['Event']['id']}][Event][result]", 'type' => 'text', 'class' => 'input-short')); ?>
            <?php echo $this->Form->input('Result', array('value' => $event['Event']['id'], 'name' => "data[{$event['Event']['id']}][Event][id]", 'type' => 'hidden')); ?>        

            <?php foreach ($event['Bet'] as $bet): ?>
                <h4><?php echo $bet['Bet']['name']; ?></h4>
                <table class="items"  cellpadding="0" cellspacing="0">
                    <tr>                    
                        <th><?php echo __('Pick'); ?></th>                        
                    </tr>

                    <?php
                    $i = 1;
                    foreach ($bet['BetPart'] as $betPart):
                        $betPart = $betPart['BetPart'];
                        $class = null;
                        if ($i++ % 2 == 0) {
                            $class = ' alt';
                        }
                        //$attributes['value'] = false;
                        $selected = false;
                        if ($bet['Bet']['pick'] == $betPart['id']) {
                            $selected = true;
                        }
                        ?>
                        <tr>
                            <td class="<?php echo $class; ?>"><?php echo $this->Form->input('Result_' . $betPart['id'], array('name' => "data[{$event['Event']['id']}][Result][{$betPart['id']}]", 'value' => 1, 'type' => 'checkbox', 'label' => $betPart['name'], 'checked' => $selected)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
            <div class="input radio">        
                <label>Title</label>
            </div>


        <?php endforeach; ?>
        <?php echo $this->Form->submit(__('Submit', true), array('class' => 'button')); ?>
        <?php echo $this->Form->end(); ?>

<?php echo $this->element('admin/paginator'); ?>

    <?php endif; ?>
</div>