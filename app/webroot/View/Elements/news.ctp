<div id="news">
    <h3><?php echo __('News'); ?></h3>

    <?php foreach ($news as $new): ?>
        <div class="new">
            <h4><?php echo $new['News']['title']; ?></h4>
            <?php echo $new['News']['summary']; ?>
            <div class="read-more">
                <?php echo $this->Html->link(__('Read more', true), array('controller' => 'news', 'action' => 'view', $new['News']['id']), array('class' => 'button')); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>