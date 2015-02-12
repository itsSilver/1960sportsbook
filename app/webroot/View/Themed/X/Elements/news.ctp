<div class="mid_box" style="margin-top:5px;">
    <div class="mid_box_title">
        <div class="showtime"><?php echo __('News'); ?></div>
        <div class="show_hide"></div>            
    </div>
    <div id="news">
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
</div>