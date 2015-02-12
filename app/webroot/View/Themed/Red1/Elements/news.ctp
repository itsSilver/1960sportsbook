
<h3 class="hi"><?php echo __('News'); ?></h3>
<div class="box">
    <?php foreach ($news as $new): ?>
        <div class="link"><?php echo $this->Html->link($new['News']['title'], array('controller' => 'news', 'action' => 'view', $new['News']['id'])); ?><div class="clear"></div></div>
    <?php endforeach; ?>
</div>



</div>