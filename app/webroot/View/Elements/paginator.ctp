<div class="paginator">
    <?php if ($this->Paginator->hasPage(2)): ?>
        <div class="paging">
            <?php if ($this->Paginator->hasPrev()): ?>
                <?php echo $this->Paginator->prev('<< ' . __('previous', true), array('class' => ''), null, array('class' => 'disabled')) . "\n"; ?>
                |
            <?php endif; ?>
            <?php echo $this->Paginator->numbers(); ?>
            |
            <?php if ($this->Paginator->hasNext()): ?>
                <?php echo $this->Paginator->next(__('next', true) . ' >>', array('class' => ''), null, array('class' => 'disabled')) . "\n"; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>