<h1><?php echo lang('docs_search_results') ?></h1>

<div class="well">
    <?php echo form_open( current_url(), 'class="form-inline"'); ?>
        <input type="text" name="search_terms" class="form-control" style="width: 85%" value="<?php echo set_value('search_terms', $this->search_terms) ?>" />
        <input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('docs_search'); ?>"/>
    <?php echo form_close(); ?>
</div>

<p><small><?php echo isset($this->results) && isset($this->results) ? count($this->results) : 0; ?> result<?= count($this->results) == 1 ? '' : 's'; ?> found in <?php echo $this->search_time ?> seconds.</small></p>

<?php if (isset($this->results) && is_array($this->results) && count($this->results)) : ?>

    <?php foreach ($this->results as $result) : ?>
    <div class="search-result">
        <p class="result-header">
            <a href="<?php echo site_url($result['url']) ?>"><?php echo $result['title'] ?></a>
        </p>
        <p class="result-url"><?php echo $result['url'] ?></p>
        <p class="result-excerpt">
            <?php echo preg_replace("/({$this->search_terms})/", "<mark>$1</mark>", $result['extract']); ?>
        </p>
    </div>
    <?php endforeach; ?>

<?php else: ?>

    <div class="alert alert-info">
        <?php echo sprintf(lang('docs_no_results'), $this->search_terms); ?>
    </div>

<?php endif; ?>
