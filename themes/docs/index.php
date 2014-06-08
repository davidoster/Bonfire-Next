<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Docs</title>
</head>
<body style="padding-top: 70px;">

    <!-- Navbar -->
    <header class="navbar navbar-inverse navbar-fixed-top" role="banner">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-nav-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>


            <div class="collapse navbar-collapse" id="main-nav-collapse">
                <ul class="nav navbar-nav navbar-left">
                    <?php if (config_item('docs.show_app_docs')) :?>
                        <li <?= $this->uri(2, 'application', 'class="active"') ?>>
                        <a href="<?= site_url('docs/application'); ?>"><?= lang('docs_title_application') ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if (config_item('docs.show_dev_docs')) : ?>
                        <li <?= $this->uri(2, 'developer', 'class="active"') ?>>
                        <a href="<?= site_url('docs/developer'); ?>"><?= lang('docs_title_bonfire') ?></a>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Search Form -->
                <?= form_open( site_url('docs/search'), 'class="navbar-form navbar-right"' ); ?>
                <div class="form-group">
                        <input type="text" class="form-control" name="search_terms" placeholder="<?= lang('docs_search_for') ?>"/>
                    </div>
                    <input type="submit" name="submit" class="btn btn-default" value="<?= lang('docs_search') ?>">
                </form>
            </div>

        </div>
    </header>

    <!-- Content Area -->
    <div class="container">

        <?= isset($this->notice) ? $this->notice : ''; ?>

        <div class="row">

            <div class="col-md-3 sidebar">
                <div class="inner">
                    <?php if (isset($this->sidebar)) : ?>
                        <?= $this->sidebar; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-9 main">
                <div class="inner">
                    <?= $this->content(); ?>
                </div>
            </div>

        </div>

    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/<?= $jqueryVersion; ?>/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?= js_path(); ?>jquery-<?= $jqueryVersion; ?>.min.js"><\/script>')</script>
    <?php //echo Assets::js(); ?>
</body>
</html>