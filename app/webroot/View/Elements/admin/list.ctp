<?php if (!empty($data)): ?>

    <table class="items" cellpadding="0" cellspacing="0">
        <tr>

            <?php
            $model = array_keys($data[0]);
            $model = $model[0];
            $titles = $data[0][$model];
            foreach ($titles as $title => $value):
                if (($title != 'locale')):
                    ?>
                    <th>
                        <?php echo $this->Paginator->sort($title); ?>
                    </th>
                    <?php
                endif;
            endforeach;
            ?>      

            <th>
                <?php echo __('Actions'); ?>
            </th>
            <?php if ($translate == true): ?>
                <th><?php echo __('Translations'); ?></th>
            <?php endif; ?>
        </tr>

        <?php
        $i = 1;
        foreach ($data as $field):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' alt';
            }
            echo "<tr>";

            $k = 0;
            foreach ($field[$model] as $key => $var) {
                //TODO better locale field handling
                if ($key != 'locale') {
                    $t = $this->Text->truncate(strip_tags($var), 100, array('ending' => '...', 'exact' => false));
                    if ($k == $mainField)
                        $t = $this->Html->link($t, array('action' => 'view', $field[$model]['id']));
                    echo "<td class=\"{$class}\">";
                    if ($key == 'order') {
                        echo $this->Html->image("admin/tabUp.png", array(
                            "alt" => "Move up", 'class' => 'move-button', 'url' => array('action' => 'moveUp', $field[$model]['id'])
                        ));
                        echo $this->Html->image("admin/tabDown.png", array(
                            "alt" => "Move down", 'class' => 'move-button', 'url' => array('action' => 'moveDown', $field[$model]['id'])
                        ));
                    } else {
                        echo $t;
                    }
                    echo "</td>";
                }
                $k++;
            }



            echo "<td class=\"actions {$class}\">\n";
	    $this->groupid = $this->Session->read('Auth.User.group_id');
	    if(isset($this->groupid) && ($this->groupid =='8')){
	       $actionsAgents = array_slice($actions,0,1);
	    } else {
	       $actionsAgents = $actions;
	    }
            foreach ($actionsAgents as $action) {
                if ($action['action'] == 'delete') {
                    $delete = 'Are you sure?';
                } else {
                    $delete = NULL;
                }
                if (isset($action['controller']))
                    echo $this->Html->link($action['name'], array('controller' => $action['controller'], 'action' => $action['action'], $field[$model]['id']), NULL, $delete);
                else
                    echo $this->Html->link($action['name'], array('action' => $action['action'], $field[$model]['id']), NULL, $delete);
                echo ' ';
            }
            if ($translate == true) {
                echo '<br />' . $this->Html->link(__('New translation', true), array('action' => 'translate', $field[$model]['id']));
            }

            echo "</td>";

            if ($translate == true) {
                echo "<td class=\"actions {$class}\">";
                foreach ($field['translations'] as $translation) {
                    if ($translation['locale'] != Configure::read('Admin.defaultLanguage')) {
                        echo $this->Html->link($translation['locale'], array('action' => 'translate', $field[$model]['id'], $translation['locale']));
                        echo ' ';
                    }
                }
                echo "</td>";
            }
            ?>
        </tr>

    <?php endforeach; ?>
    </table>

    <?php echo $this->element('paginator'); ?>

<?php else: ?>
    <p><?php echo __('No records found'); ?></p>
<?php endif; ?>