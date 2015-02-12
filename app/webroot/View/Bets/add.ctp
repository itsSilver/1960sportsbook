<div id="users" class="bets add">
    <?php echo $this->Session->flash(); ?>
    <h3><?php echo __('Add bet', true); ?></h3>
    <?php
    echo $this->Form->create('Bet', array('url' => array($this->params['pass'][0])));
    echo $this->Form->input('name');
    echo $this->Form->input('type');
    ?>

    <table id="table_liquid" class="picksTable" cellpadding="0" cellspacing="0">
        <tr>
            <th><label><?php echo __('Name'); ?></label></th>
            <th><label><?php echo __('Odd'); ?></label></th>
        </tr>
        <tr>
            <td class=""><input class="input-big" type="text" name="data[BetPart][0][name]" type="text" maxlength="255" id="BetPartName"></td>
            <td class=""><input name="data[BetPart][0][odd]" type="text" maxlength="255" id="BetPartOdd"></td>                    
        </tr>
        <tr>
            <td class=""><input class="input-big" type="text" name="data[BetPart][1][name]" type="text" maxlength="255" id="BetPartName"></td>
            <td class=""><input name="data[BetPart][1][odd]" type="text" maxlength="255" id="BetPartOdd"></td>                    
        </tr>
        <tr>
            <td colspan="2"><div class="lefted"><a href="" class="button" id="addPickButton">ADD</a></div></td>                
        </tr>
    </table>
    <div class="lefted">
    <?php
    echo $this->Form->submit(__('Submit', true), array('class' => 'button', 'div' => false));
    ?>
    </div>
    <?php
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