<div id="groups">
<?php echo $this->Session->flash(); ?>
    <?php if (!empty($acos)): ?>
        <?php echo $this->Form->create('Group', array('url' => array('action' => 'edit', $id))); ?>
        <table>
            <?php foreach ($acos as $aco): ?>
                <tr>
                    <td>
                        <?php echo $this->Form->input($aco['Aco']['id'], array('type' => 'checkbox', 'label' => $aco['Aco']['alias'])); ?>
                    </td>
                    <td>
                        <?php if (!empty($aco['childs'])): ?>
                            <a href="#" onClick="showActions(<?php echo $aco['Aco']['id']; ?>); return false">actions</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if (!empty($aco['childs'])): ?>
                    <tr id="actions_<?php echo $aco['Aco']['id']; ?>" class="hidden">
                        <td></td>
                        <td>
                            <?php foreach ($aco['childs'] as $aco): ?>
                                <?php echo $this->Form->input($aco['Aco']['id'], array('type' => 'checkbox', 'label' => $aco['Aco']['alias'])); ?>
                                <br />
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <?php echo $this->Form->submit(__('Save', true)); ?>
        <?php echo $this->Form->end(); ?>

    <?php endif; ?>

</div>

<script type="text/javascript">
    function showActions(id) {
        jQuery('#actions_' + id).toggle();
    }
</script>