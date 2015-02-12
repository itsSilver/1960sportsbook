<div class="mid_box">
    <div class="mid_box_title"><div class="showtime">Highlights</div><div class="show_hide"></div></div>
    <div class="mid_box_mid">
        <div class="slider-wrapper theme-default">
            <div class="ribbon"></div>
            <div id="slider" class="nivoSlider">

                <?php $i = 0; ?>
                <?php foreach ($slides as $slide): ?>
                    <?php echo $this->Html->image('slides' . DS . $slide['Slide']['image'], array('url' => $this->MyHtml->customUrl($slide['Slide']['url'])), array('title' => '#htmlcaption' . $i)); ?>                
                    <?php $i++; ?>
                <?php endforeach; ?>
                
            </div>
        </div>

        <?php foreach ($slides as $slide): ?>
            <div id="htmlcaption" class="nivo-html-caption">
                <div class="nivo_divas">
                    <?php echo $slide['Slide']['description']; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="clear"></div>
    </div>
</div>
