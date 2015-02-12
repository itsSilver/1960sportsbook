
<div id="cycle_slider">

    <?php foreach ($slides as $slide): ?>
        <div class="slider">
            <a href="">
                <?php echo $this->Html->link($this->Html->image('slides' . DS . $slide['Slide']['image']), array('controller' => 'pages', 'action' => $slide['Slide']['title']), array('class' => 'slide', 'escape' => false)); ?>
            </a>
            <div class="txt">
                <a href=""><?php echo $this->Html->image('slider.png', array('class' => 'butt')); ?></a>
                <p><?php echo $slide['Slide']['description']; ?></p>
                <div class="clear"></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<script type="text/javascript">
    jQuery('#cycle_slider').cycle({
        fx: 'scrollLeft',
        speed:  1000,
        timeout: 10000
    }); 
</script>
