<div id="languages"> 
    <ul>

        <?php
        $locales = $this->requestAction('languages/getLanguages/');
        ?>

        <?php foreach ($locales as $locale): ?>            

            <?php $image = $this->Html->image('locales/' . $locale['name'] . '.png', array('alt' => $locale)) ?>

            <li><?php echo $this->Html->link($image, array('controller' => 'languages', 'action' => 'setLanguage', $locale['id']), array('escape' => false)); ?></li>

        <?php endforeach; ?>

    </ul>
</div>        
