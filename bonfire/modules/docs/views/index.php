<?php if (empty($this->content)) : ?>
    <div class="alert alert-info" style="margin-top: 40px;">
        <span class="glyphicon glyphicon-book"></span>
        <?php echo lang('docs_not_found'); ?>
    </div>

<?php else: ?>

    <div class="page">
        <?php echo $this->content; ?>
    </div>

<?php endif; ?>