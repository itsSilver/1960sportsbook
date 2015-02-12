<?php
if (!isset($tabs))    
    $tabs = array();
?>
<?php if (!empty($tabs)): ?>
    <div id="local-navigation">
        <ul class="localmenu">
            <?php if (!empty($tabs)): ?>
                <?php foreach ($tabs as $tab): ?>
                    <?php if (isset($tab['active'])) : ?>
                        <li class="selected">
                            <?php echo $this->Html->link(__($tab['name'], true), $tab['url']); ?>
                        </li>
                    <?php else: ?>
                        <li>
                            <?php echo $this->Html->link(__($tab['name'], true), $tab['url']); ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>