<div id="pages" class="display">
        <h3><?php echo __('Jackpot Top Of the Month', true);?></h3>
    <div id="content">
<table id="JacpotTable">
<thead>
<tr>
<th><?php echo __('User'); ?></th>
<th><?php echo __('Right Guess'); ?></th>
</tr>
</thead>
<tbody>
<?php foreach($data as $jk) :?>
<tr>
<td><?= $jk['User']['username'] ?>
<td><?= $jk[0]['guesses'] ?>
</tr>
<?php endforeach ?>
</tbody>
</table>

    </div>
</div>