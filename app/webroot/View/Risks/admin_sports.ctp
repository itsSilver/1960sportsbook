<div class="risks sports">
    <h3><?php echo __('Risk Management'); ?></h3>

    <?php echo $this->Session->flash(); ?>

    <h4><?php echo __('Set general settings'); ?></h4>

    <?php
    $options = array(
        'url' => array(
            'controller' => 'risks'
        ),
        'inputDefaults' => array(
            'label' => false,
            'div' => false)
    );
    echo $this->Form->create('Sport', $options);
    ?>


    <table class="items" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('min_bet'); ?></th>
            <th><?php echo $this->Paginator->sort('max_bet'); ?></th>
        </tr>
        <?php foreach ($data as $row): ?>
            <tr>
                <td><?php echo $row['Sport']['id']; ?></td>
                <td><?php echo $this->Html->link($row['Sport']['name'], array('action' => 'leagues', $row['Sport']['id'])); ?></td>
                <td><input name="data[Sport][<?php echo $row['Sport']['id']; ?>][min_bet]" type="text" value="<?php echo $row['Sport']['min_bet']; ?>" /></td>
                <td><input name="data[Sport][<?php echo $row['Sport']['id']; ?>][max_bet]" type="text" value="<?php echo $row['Sport']['max_bet']; ?>" /></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php echo $this->element('admin/paginator'); ?>
    
    <?php echo $this->Form->submit(__('Save', true), array('class' => 'button')); ?>
    <?php echo $this->Form->end(); ?>

</div>


