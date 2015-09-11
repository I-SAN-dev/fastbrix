<?php
/**
 * This is the entry point
 *
 * This file is part of "fastbrix"
 *
 * @author Sebastian Antosch <s.antosch@i-san.de>
 * @copyright 2015 I-SAN.de Webdesign & Hosting GbR
 * @link http://i-san.de
 *
 * @license MIT
 */

set_include_path(getcwd());

use core\config\Loader as Config;
use core\routing\Router as Router;
use core\assetmanager\Collector as AssetCollector;

/* This block must come first! */
/**
 * Autoloads necessary bricks
 * @param $class
 * @throws Exception
 */
function defaultAutoload($class)
{
    //load file from namespace
    $class = str_replace('\\','/', $class).'.php';
    if(file_exists($class))
    {
        require_once($class);
    }
}
spl_autoload_register('defaultAutoload');

/*var_dump(Router::getPageData());*/
echo(AssetCollector::getCssAndJs());