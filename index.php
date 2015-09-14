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

use core\routing\Router as Router;
use core\assetmanager\Collector as AssetCollector;
use core\head\Metadata as Metadata;
use core\head\Icons as Icons;

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


/**
 * Sends a complete page to the browser
 * @param $maincontent - the content of the page
 */
function sendPage($maincontent)
{
    $metadata = Metadata::get();
    $scriptsstyles = AssetCollector::getCssAndJs();
    $icons = Icons::get();

    ob_start();
    include('template/template.php');
    ob_end_flush();
}

/**
 * Send page data as JSON to the client for ajax-laods
 * @param $title - the page title
 * @param $content - the page content
 */
function sendPageJson($title, $content)
{
    $response = array(
        "title" => $title,
        "content" => $content,
    );
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}

/**
 * Fetches the content of the
 * @param $pageData
 * @return string
 */
function getPageContent($pageData)
{
    /* Convert errors into catchable exceptions */
    set_error_handler(function($severity, $string, $file, $line, $context){
        throw new \ErrorException($string, 0, $severity, $file, $line);
    }, error_reporting());

    /* Fetch page content */
    $content='';
    try{
        ob_start();
        include($pageData['path']);
        $content = $content.ob_get_clean();
    }
    catch( \Exception $e)
    {
        $content = $content.'
<div class="alert alert-danger" role="alert">
  <strong>'.$e->getCode().'</strong> '.$e->getMessage().'<br/>
  <br/>'.$e->getFile().' - Line '.$e->getLine().'
</div>
            ';
    }

    /* restore default error handling */
    restore_error_handler();

    /* Return Data */
    return array(
        "content" => $content,
        "title" => $pageData['title']
    );
}

/**
 * Start Fastbrix
 */
function start()
{
    $pageContent = getPageContent(Router::getPageData());
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        sendPage($pageContent['content']);
    }
    else
    {
        sendPageJson($pageContent['title'], $pageContent['content']);
    }
}
start();
