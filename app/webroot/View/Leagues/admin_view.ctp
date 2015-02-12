<?php echo $this->Html->link(__('Add event', true), array('controller' => 'events', 'action' => 'add', $model['League']['id']), array('class' => 'button')); ?>
<h2><?php echo $model['League']['name'] . ' ' . __('Events', true); ?></h2>
<?php if (!empty($data)): ?>

    <table class="items" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('active'); ?></th>            
            <th><?php echo __('Actions'); ?> </th>            
        </tr>

        <?php
        $i = 1;
        foreach ($data as $row):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' alt';
            }
            ?>
            <tr>
                <td class="<?php echo $class; ?>"><?php echo $row['Event']['id']; ?></td>
                <td class="<?php echo $class; ?>"><?php echo $this->Html->link($row['Event']['name'], array('controller' => 'events', 'action' => 'view', $row['Event']['id'])); ?></td>
                <td class="<?php echo $class; ?>"><?php echo $row['Event']['active']; ?></td>
                <td class="actions <?php echo $class; ?>">                   
                    <?php echo $this->Html->link(__('Edit', true), array('controller' => 'events', 'action' => 'edit', $row['Event']['id'])); ?>
                    <?php echo $this->Html->link(__('Delete', true), array('controller' => 'events', 'action' => 'delete', $row['Event']['id'])); ?>
                </td>                
            </tr>

        <?php endforeach; ?>
    </table>

    <?php echo $this->element('admin/paginator'); ?>

<?php else: ?>

<?php endif; ?>
