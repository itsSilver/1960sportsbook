<div class="results index">
    <?php if (!empty($data)): ?>
        <h2><?php echo __('Results'); ?></h2>
        <table class="items"  cellpadding="0" cellspacing="0">
            <tr>
                <th>
                    <?php echo $this->Paginator->sort('id'); ?>
                </th>
                <th>
                    <?php echo $this->Paginator->sort('name'); ?>
                </th>      
                <th>
                    <?php echo $this->Paginator->sort('result'); ?>
                </th>   
                <th>
                    <?php echo $this->Paginator->sort('date'); ?>
                </th>  
                <th>
                    <?php echo __('Actions'); ?>
                </th>  
            </tr>        
            <?php
            $i = 1;
            foreach ($data as $field):
                $class = null;
                if ($i++ % 2 == 0) {
                    $class = ' alt';
                }
                ?>
                <tr>
                    <?php
                    echo "<td class=\"{$class}\">\n\t\t\t" . $field[$model]['id'] . "</td>";
                    $t = $this->Html->link($field[$model]['name'], array('action' => $action, $field[$model]['id']));
                    echo "<td class=\"{$class}\">\n\t\t\t" . $t . "</td>";
                    echo "<td class=\"{$class}\">\n\t\t\t" . $field[$model]['result'] . "</td>";
                    echo "<td class=\"{$class}\">\n\t\t\t" . $field[$model]['date'] . "</td>";
                    ?>
                    <td class="<?php echo $class; ?>">
                        <?php echo $this->Html->link(__('Cancel', true), array('action' => 'cancel', $field[$model]['id']), null, __('Are you sure you want to cancel this event?', true)); ?>
                    </td>
                </tr>
                <?php
            endforeach;
            ?>

        </table>
        <?php echo $this->element('admin/paginator'); ?>
    <?php else: ?>
        <p><?php echo __('No events in this league'); ?></p>
    <?php endif; ?>
</div>
