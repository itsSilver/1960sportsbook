<?php
App::uses('BethHelper', 'View/Helper');
$beth = new BethHelper($this);
$themes = $beth->getThemesList();
?>
<div id="themes">

    <ul>
        <?php foreach ($themes as $key => $theme): ?>
            <li><?php echo $this->Html->link('', array('controller' => 'users', 'action' => 'setTheme', $key), array('class' => $key)); ?></li>
        <?php endforeach; ?>
            
    </ul>
</div>