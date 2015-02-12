<div class="bets add">
    <?php echo $this->Session->flash(); ?>    
    <?php
    echo $this->Form->create('Bet', array('url' => array($this->params['pass'][0])));
    echo $this->Form->input('name');
    echo $this->Form->input('type');
    ?>
 
    <table id="table_liquid" class="picksTable" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo __('Name'); ?></th>
            <th><?php echo __('Odd'); ?></th>
        </tr>
        <?php $i = 0; ?>
        <?php foreach ($data['BetPart'] as $betPart): ?>

            <tr>
                <td class="">
                    <input value="<?php echo $betPart['id']; ?>" type="hidden" name="data[BetPart][<?php echo $i; ?>][id]" />
                    <input value="<?php echo $betPart['name']; ?>" class="input-big" type="text" name="data[BetPart][<?php echo $i; ?>][name]" type="text" maxlength="255" />
                </td>
                <td class=""><input value="<?php echo $betPart['odd']; ?>" name="data[BetPart][<?php echo $i; ?>][odd]" type="text" maxlength="255" /></td>                    
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>        
    </table>
    <div class="actions">        
        <a href="" id="addPickButton">ADD</a>
    </div>
    <?php
    echo $this->Form->submit(__('Submit', true), array('class' => 'button'));
    echo $this->Form->end();
    ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#addPickButton').bind('click', addPick);
    });
    var i = 2;
    function addPick() {
        var a = '<tr><td class=""><input class="input-big" type="text" name="data[BetPart]['+i+'][name]" type="text" maxlength="255" id="BetPartName"></td><td class=""><input name="data[BetPart]['+i+'][odd]" type="text" maxlength="255" id="BetPartOdd"></td>                            </tr>';        
        jQuery('.picksTable tr:last').before(a);
        i++;
        return false;
    }
</script>