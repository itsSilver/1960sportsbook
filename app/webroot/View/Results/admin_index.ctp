<div class="results index">
    <h2><?php echo __('Results'); ?></h2>
    <table class="items"  cellpadding="0" cellspacing="0">
        <tr>
            <th>
                <?php echo $this->Paginator->sort('id'); ?>
            </th>
            <th>
                <?php echo $this->Paginator->sort('name'); ?>
            </th>                      
        </tr>

        <?php
        $i = 1;
        foreach ($data as $field):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' alt';
            }
            echo "<tr>";
            echo "<td class=\"{$class}\">\n\t\t\t" . $field[$model]['id'] . "</td>";
            $t = $this->Html->link($field[$model]['name'], array('action' => $action, $field[$model]['id']));
            echo "<td class=\"{$class}\">\n\t\t\t" . $t . "</td>";
            echo "</tr>";
        endforeach;
        ?>
    </table>
    <?php echo $this->element('admin/paginator'); ?>
</div>
