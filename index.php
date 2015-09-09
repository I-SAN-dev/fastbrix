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

/**
 * Autoloads necessary bricks
 * @param $class
 * @throws Exception
 */
function __autoload($class)
{
    //load file from namespace
    $class = str_replace('\\','/', $class).'.php';
    if(file_exists($class))
    {
        require_once($class);
    }
    else
    {
        throw new Exception("File ".$class." could not be found!", 404);
    }
}