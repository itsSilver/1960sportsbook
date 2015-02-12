<?php echo $this->Html->link(__('Add league', true), array('controller' => 'leagues', 'action' => 'add', $model['Sport']['id'])); ?>
<h2><?php echo $model['Sport']['name'] . ' ' . __('Leagues', true); ?></h2>
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
                <td class="<?php echo $class; ?>"><?php echo $row['League']['id']; ?></td>
                <td class="<?php echo $class; ?>"><?php echo $this->Html->link($row['League']['name'], array('controller' => 'leagues', 'action' => 'view', $row['League']['id'])); ?></td>
                <td class="<?php echo $class; ?>"><?php echo $row['League']['active']; ?></td>
                <td class="actions <?php echo $class; ?>">
                    <?php echo $this->Html->link(__('View', true), array('controller' => 'leagues', 'action' => 'view', $row['League']['id'])); ?>
                    <?php echo $this->Html->link(__('Edit', true), array('controller' => 'leagues', 'action' => 'edit', $row['League']['id'])); ?>
                    <?php echo $this->Html->link(__('Add event', true), array('controller' => 'events', 'action' => 'add', $row['League']['id'])); ?>
                </td>                
            </tr>

        <?php endforeach; ?>
    </table>

<?php else: ?>
     
<?php endif; ?>
