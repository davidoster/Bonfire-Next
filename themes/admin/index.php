<?= $template->display('admin:parts/head') ?>

<?= $template->display('admin:parts/topbar') ?>

<div class="container-fluid">
    <div class="<?= $uikit->row() ?>">

        <div class="<?= $uikit->column(['m'=>1, 'l'=>2]) ?> sidebar">
            <?= $template->display('admin:parts/sidenav') ?>
        </div>

        <?php if (isset($actionbar)) : ?>

            <div class="<?= $uikit->column(['m'=>3, 'l'=>2]) ?> actionbar">
                <?= $actionbar ?>
            </div>

            <div class="<?= $uikit->column(['m'=>8, 'l'=>8, 'm-offset'=>6, 'l-offset'=>4]) ?> main">
                <?php if (! empty($view_content)) : ?>
                    <?= $view_content; ?>
                <?php endif; ?>
            </div>

        <?php else : ?>

            <div class="<?= $uikit->column(['m'=>11, 'l'=>10, 'm-offset'=>1, 'l-offset'=>2]) ?> main">
                <?php if (! empty($view_content)) : ?>
                    <?= $view_content; ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    </div>
</div>

<?= $template->display('admin:parts/footer') ?>
