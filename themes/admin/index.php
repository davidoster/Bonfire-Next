<?= $template->display('admin:parts/head') ?>

<?= $template->display('admin:parts/topbar') ?>

<div class="container-fluid">
    <div class="row">

        <div class="col-sm-3 col-md-2 sidebar">
            <?= $template->display('admin:parts/sidenav') ?>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <?php if (! empty($view_content)) : ?>
                <?= $view_content; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $template->display('admin:parts/footer') ?>
