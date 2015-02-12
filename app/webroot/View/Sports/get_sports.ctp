<div id="backButton" class="hiden">
    <a onclick="backToSports()" href="#">&lt;&lt; <?php echo __('Back'); ?></a>
</div> 

<ul class="sidemenu"> 

    <?php    
    $sport_gr = 1;
    foreach ($sports as $sport) {
        echo '<li class="gr' . $sport_gr . ' menuMarkerParent"><a onclick="showMenu(' . $sport['Sport']['id'] . ');" href="#" url="url">' . $sport['Sport']['name'] . '</a></li>';
        
        $link = $this->Html->link(__("All today's events"), array('controller' => 'sports', 'action' => 'today', $sport['Sport']['id']));
        echo '<li class="gr1 all-events menuMarker' . $sport['Sport']['id'] . ' hiden">' . $link . '</li>';
        
        $link = $this->Html->link(__("All tomorrow's events"), array('controller' => 'sports', 'action' => 'tomorow', $sport['Sport']['id']));
        echo '<li class="gr2 all-events menuMarker' . $sport['Sport']['id'] . ' hiden">' . $link . '</li>';
        //cycle sport_gr 1/2
        $sport_gr = $sport_gr % 2 + 1;

        $league_gr = 1;
        foreach ($sport['League'] as $league) {            
            $link = $this->Html->link($league['name'], array('controller' => 'sports', 'action' => $league['id']));
            echo '<li class="gr' . $league_gr . ' menuMarker' . $sport['Sport']['id'] . ' hiden">' . $link . '</li>';
            $league_gr = $league_gr % 2 + 1;
        }
    }
    ?>

</ul>