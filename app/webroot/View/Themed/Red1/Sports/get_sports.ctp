<ul>
    <div id="backButton" class="hiden">
        <a class="gr" onclick="backToSports()" href="#">&lt;&lt; <?php echo __('Back'); ?></a>
    </div> 

    <?php $a = array('1' => '', '2' => 'gr'); $sport_gr = 1; ?>
    <?php foreach ($sports as $sport): ?>

        <li class="<?php echo $a[$sport_gr]; ?> menuMarkerParent">
            <a onclick="showMenu(<?php echo $sport['Sport']['id']; ?>);" href="#" url="url"><?php echo $sport['Sport']['name']; ?></a>
        </li>
        <?php $link = $this->Html->link(__("All today's events"), array('controller' => 'sports', 'action' => 'today', $sport['Sport']['id'])); ?>
        <li class="gr all-events menuMarker<?php echo $sport['Sport']['id']; ?> hiden"><?php echo $link; ?></li>

        <?php $link = $this->Html->link(__("All tomorrow's events"), array('controller' => 'sports', 'action' => 'tomorow', $sport['Sport']['id'])); ?>
        <li class="all-events menuMarker<?php echo $sport['Sport']['id']; ?> hiden"><?php echo $link; ?></li>

        <?php $sport_gr = $sport_gr % 2 + 1; ?>

        <?php $league_gr = 2; ?>
        <?php foreach ($sport['League'] as $league): ?>
            <?php $link = $this->Html->link($league['name'], array('controller' => 'sports', 'action' => $league['id'])); ?>
            <li class="<?php echo $a[$league_gr]; ?> menuMarker<?php echo $sport['Sport']['id']; ?> hiden"><?php echo $link; ?></li>
            <?php $league_gr = $league_gr % 2 + 1; ?>            
        <?php endforeach; ?>    
    <?php endforeach; ?>    

</ul>