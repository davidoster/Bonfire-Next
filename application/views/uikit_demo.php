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
        .bordered > div { border: 1px solid #ccc; height: 3em; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?= $uikit->navbar(['inverse'], function() use($uikit) {
        echo $uikit->navbarTitle('UIKit Demo');

        echo $uikit->navbarRight([], function() use($uikit) {

            echo $uikit->navDropdown('CSS Framework', [], function() use($uikit) {
                    echo $uikit->navItem('Bootstrap 3.2', current_url() .'?uikit=Bootstrap3', [], isset($_GET['uikit']) && $_GET['uikit'] == 'Bootstrap3');
                    echo $uikit->navItem('Foundation 5', current_url() .'?uikit=Foundation5', [], isset($_GET['uikit']) && $_GET['uikit'] == 'Foundation5');
                });
        });
    }); ?>

    <?= $uikit->row([], function() use($uikit) {

            // Sidebar
            echo $uikit->column(['sizes' => ['l'=>3]], function() use($uikit) {

                echo $uikit->sideNav([], function() use($uikit){
                        echo $uikit->navItem('Grid System', '#grids');
                        echo $uikit->navItem('Offset Grids', '#offset-grids');
                        echo $uikit->navItem('Tables', '#tables');
                    });

                }); // End SideNav Column

            // Main Content
            echo $uikit->column(['sizes' => ['l'=>9]], function() use($uikit) { ?>

                    <div style="margin: 20px 40px; position: relative">
                        <h1>UIKit Demo</h1>

                        <p>This page provides a demonstration of all of the features of the UIKit Library and allows you to see how
                        they look in the CSS Frameworks that we support.</p>

                        <br />


                        <a name="grids"></a>
                        <h2>Grid System</h2>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['s'=>2, 'l'=>4], 'class'=>'light1'], function(){ echo '1a'; });
                                echo $uikit->column(['sizes' => ['s'=>4, 'l'=>4], 'class'=>'light2'], function(){ echo '1b'; });
                                echo $uikit->column(['sizes' => ['s'=>6, 'l'=>4], 'class'=>'light1'], function(){ echo '1c'; });
                            }); ?>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['l'=>3], 'class'=>'light1'], function(){ echo '2a'; });
                                echo $uikit->column(['sizes' => ['l'=>6], 'class'=>'light2'], function(){ echo '2b'; });
                                echo $uikit->column(['sizes' => ['l'=>3], 'class'=>'light1'], function(){ echo '2c'; });
                            }); ?>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['s'=>6, 'l'=>2], 'class'=>'light1'], function(){ echo '3a'; });
                                echo $uikit->column(['sizes' => ['s'=>6, 'l'=>8], 'class'=>'light2'], function(){ echo '3b'; });
                                echo $uikit->column(['sizes' => ['s'=>12, 'l'=>2], 'class'=>'light1'], function(){ echo '3c'; });
                            }); ?>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['s'=>3], 'class'=>'light1'], function(){ echo '4a'; });
                                echo $uikit->column(['sizes' => ['s'=>9], 'class'=>'light2'], function(){ echo '4b'; });
                            }); ?>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['l'=>4], 'class'=>'light1'], function(){ echo '5a'; });
                                echo $uikit->column(['sizes' => ['l'=>8], 'class'=>'light2'], function(){ echo '5b'; });
                            }); ?>


                        <a name="offset-grids"></a>
                        <h3>Offset Grids</h3>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['l'=>1], 'class'=>'light1'], function(){ echo '5a'; });
                                echo $uikit->column(['sizes' => ['l'=>11], 'class'=>'light2'], function(){ echo '5b'; });
                            }); ?>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['l'=>1], 'class'=>'light1'], function(){ echo '5a'; });
                                echo $uikit->column(['sizes' => ['l'=>10, 'l-offset'=>1], 'class'=>'light2'], function(){ echo '5b'; });
                            }); ?>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['l'=>1], 'class'=>'light1'], function(){ echo '5a'; });
                                echo $uikit->column(['sizes' => ['l'=>9, 'l-offset'=>2], 'class'=>'light2'], function(){ echo '5b'; });
                            }); ?>

                        <?= $uikit->row(['class'=>'bordered'], function() use($uikit) {
                                echo $uikit->column(['sizes' => ['l'=>1], 'class'=>'light1'], function(){ echo '5a'; });
                                echo $uikit->column(['sizes' => ['l'=>8, 'l-offset'=>3], 'class'=>'light2'], function(){ echo '5b'; });
                            }); ?>




                        <!--
                            Tables
                        -->
                        <a name="tables"></a>
                        <h2>Tables</h2>

                        <table class="<?= $uikit->table() ?>">
                            <thead>
                                <tr>
                                    <th width="200">Table Header</th>
                                    <th>Table Header</th>
                                    <th width="150">Table Header</th>
                                    <th width="150">Table Header</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Content Goes Here</td>
                                    <td>This is longer content Donec id elit non mi porta gravida at eget metus.</td>
                                    <td>Content Goes Here</td>
                                    <td>Content Goes Here</td>
                                </tr>
                                <tr>
                                    <td>Content Goes Here</td>
                                    <td>This is longer Content Goes Here Donec id elit non mi porta gravida at eget metus.</td>
                                    <td>Content Goes Here</td>
                                    <td>Content Goes Here</td>
                                </tr>
                                <tr>
                                    <td>Content Goes Here</td>
                                    <td>This is longer Content Goes Here Donec id elit non mi porta gravida at eget metus.</td>
                                    <td>Content Goes Here</td>
                                    <td>Content Goes Here</td>
                                </tr>
                            </tbody>
                        </table>




                    </div>

                <?php }); // End Main Content Column
        });?>




    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <?= $scripts ?>
</body>
</html>