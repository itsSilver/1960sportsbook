<div class="results index">
    <?php if (!empty($data)): ?>
        <h2><?php echo $model['Event']['name']; ?> <?php echo __('Results'); ?></h2>

        <?php echo $this->Form->create('Result', array('url' => array($this->params['pass'][0]))); ?>

        <?php echo $this->Form->input('Result', array('label' => __('Result', true), 'value' => $model['Event']['result'], 'name' => 'data[Event][result]', 'type' => 'text', 'class' => 'input-short')); ?>
        <?php echo $this->Form->input('Result', array('value' => $model['Event']['id'], 'name' => 'data[Event][id]', 'type' => 'hidden')); ?>        
        <?php foreach ($data as $bet): ?>
            <?php
            if ($bet['Bet']['pick'] == 'asd')
                continue;
            ?>
            <h4><?php echo $bet['Bet']['name']; ?></h4>
            <table class="items"  cellpadding="0" cellspacing="0">
                <tr>                    
                    <th><?php echo __('Pick'); ?></th>                        
                </tr>

                <?php
                $i = 1;
                foreach ($bet['BetPart'] as $betPart):
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
                        <td class="<?php echo $class; ?>"><?php echo $this->Form->input($betPart['id'], array('value' => 1, 'type' => 'checkbox', 'label' => $betPart['name'], 'checked' => $selected)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
        <div class="input radio">        
            <label>Title</label>





        </div>
        <?php echo $this->Form->submit(__('Submit', true), array('class' => 'button')); ?>
        <?php echo $this->Form->end(); ?>

    <?php endif; ?>
</div>