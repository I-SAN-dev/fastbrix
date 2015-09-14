<?php
use core\config\Loader as Config;
use core\navigation\Menu as Menu;
$conf = Config::get();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags always come first -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <!-- Metadata -->
        <?php echo $metadata ?>

        <!-- Styles & Scripts -->
        <?php echo $scriptsstyles ?>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicons -->
        <?php echo $icons ?>

    </head>
    <body>

        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand ajax" href="/"><?php echo $conf['name'] ?></a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <?php echo Menu::get('main', 'nav navbar-nav'); ?>
                </div><!--/.nav-collapse -->
            </div>
        </nav>


        <div class="container" id="main">
            <?php echo $maincontent ?>
        </div>



    </body>
</html>