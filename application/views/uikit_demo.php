<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../../favicon.ico">

    <title>Bonfire Admin</title>

    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= $stylesheet ?>" rel="stylesheet">
    <?php if ($uikit->name() == 'Pure05UIkit') : ?>
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
    <?php endif; ?>

    <style type="text/css">
        .light1 { background: #f7f7f7; }
        .light2 { background: #d7d7d7; }
        .<?= $uikit->row() ?> > div { border: 1px solid #ccc; height: 3em; }
    </style>
</head>
<body>

    <script type="text/javascript">
        function doRedirect()
        {
            var _select = document.getElementById("uikit-chooser");
            var _val = _select.options[_select.selectedIndex].value;
            var _url = [location.protocol, '//', location.host, location.pathname].join('');
            return window.location.href = _url +'?uikit='+ _val;
        }
    </script>

    <div style="margin: 20px 40px; position: relative">
        <h1>UIKit Demo</h1>

        <p>This page provides a demonstration of all of the features of the UIKit Library and allows you to see how
        they look in the CSS Frameworks that we support.</p>

        <br />

        <select id="uikit-chooser" onchange="return doRedirect();">
            <option value="Bootstrap3" <?php if ($uikit->name() == 'Bootstrap3UIKit') echo 'selected' ?>>Bootstrap 3.2.0</option>
            <option value="Foundation5" <?php if ($uikit->name() == 'Foundation5UIKit') echo 'selected' ?>>Foundation 5</option>
            <option value="Pure05" <?php if ($uikit->name() == 'Pure05UIkit') echo 'selected' ?>>Pure CSS 0.5</option>
        </select>

        <br />

        <h2>Grid System</h2>

        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['s'=>2, 'l'=>4]) ?> light1">1a</div>
            <div class="<?= $uikit->column(['s'=>4, 'l'=>4]) ?> light2">1b</div>
            <div class="<?= $uikit->column(['s'=>6, 'l'=>4]) ?> light1">1c</div>
        </div>
        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['l'=>3]) ?> light1">2a</div>
            <div class="<?= $uikit->column(['l'=>6]) ?> light2">2b</div>
            <div class="<?= $uikit->column(['l'=>3]) ?> light1">2c</div>
        </div>
        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['s'=>6, 'l'=>2]) ?> light1">3a</div>
            <div class="<?= $uikit->column(['s'=>6, 'l'=>8]) ?> light2">3b</div>
            <div class="<?= $uikit->column(['s'=>12, 'l'=>2]) ?> light1">3c</div>
        </div>
        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['s'=>3]) ?> light1">4a</div>
            <div class="<?= $uikit->column(['s'=>9]) ?> light2">4b</div>
        </div>
        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['l'=>4]) ?> light1">5a</div>
            <div class="<?= $uikit->column(['l'=>8]) ?> light2">5b</div>
        </div>

        <h3>Offset Grids</h3>

        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['l'=>1]) ?> light1">1</div>
            <div class="<?= $uikit->column(['l'=>11]) ?> light2">11</div>
        </div>
        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['l'=>1]) ?> light1">1</div>
            <div class="<?= $uikit->column(['l'=>10, 'l-offset'=>1]) ?> light2">10</div>
        </div>
        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['l'=>1]) ?> light1">1</div>
            <div class="<?= $uikit->column(['l'=>9, 'l-offset'=>2]) ?> light2">9</div>
        </div>
        <div class="<?= $uikit->row() ?>">
            <div class="<?= $uikit->column(['l'=>1]) ?> light1">1</div>
            <div class="<?= $uikit->column(['l'=>8, 'l-offset'=>3]) ?> light2">8</div>
        </div>

    </div>


</body>
</html>