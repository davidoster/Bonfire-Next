<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Docs</title>

    <link rel="stylesheet" type="text/css" href="/assets/docs.css" />
</head>
<body>

    <a name="top"></a>

    <!-- Navbar -->
    <header class="navbar navbar-inverse navbar-static-top" role="banner">
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
                        <li <?= $this->uri(1, 'application', 'class="active"') ?>>
                        <a href="<?= site_url('docs/application'); ?>"><?= lang('docs_title_application') ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if (config_item('docs.show_dev_docs')) : ?>
                        <li <?= $this->uri(1, 'developer', 'class="active"') ?>>
                        <a href="<?= site_url('docs/developer'); ?>"><?= lang('docs_title_bonfire') ?></a>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Search Form -->
                <?= form_open( site_url('docs/search'), 'class="navbar-form navbar-right"' ); ?>
                <div class="form-group">
                        <input type="search" class="form-control" name="search_terms" placeholder="<?= lang('docs_search_for') ?>"/>
                    </div>
                    <input type="submit" name="submit" class="btn btn-default" value="<?= lang('docs_search') ?>">
                </form>
            </div>

        </div>
    </header>

    <div class="toc">
        <div class="container">

            <a href="#" id="toc-btn" style="margin: 5px 10px 0 0"><?= lang('docs_toc') ?></a>
        </div>
    </div>


    <!-- Content Area -->
    <div class="container">

        <?= isset($this->notice) ? $this->notice : ''; ?>

        <div class="row">

            <div class="col-md-9 main">
                <?php $content = $this->content();  ?>
                <?php if (! empty($content)) : ?>
                    <?= $content; ?>
                <?php else: ?>
                    <div class="alert">
                        <?= lang('docs_not_found') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-3 sidebar">
                <?php if (isset($this->sidebar)) : ?>
                    <?= $this->sidebar; ?>
                <?php endif; ?>
            </div>

        </div>

    </div>

    <script src="/assets/js/docs.js"></script>
</body>
</html>