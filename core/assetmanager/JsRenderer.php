<?php
/**
 * Renders a JS file from a list of sourcefiles and functions to call
 *
 * This file is part of "fastbrix"
 *
 * @author Sebastian Antosch <s.antosch@i-san.de>
 * @copyright 2015 I-SAN.de Webdesign & Hosting GbR
 * @link http://i-san.de
 *
 * @license MIT
 */

namespace core\assetmanager;


final class JsRenderer {

    /**
     * Renders a js file
     * @param $files - array of paths to include
     * @param $onReady - function names that shall be called on ready event
     * @param $onLoad - function names that shall be called on load event
     * @param $path - path to file to write to
     */
    public static function render(array $files, array $onReady, array $onLoad, $path)
    {
        /* Header */
        $content = '

/*
 * Generated with Fastbrix
 * at '.date('d.m.Y H:i:s').'
 */

';

        foreach($files as $file)
        {
            $content = $content.'

/************************************************************
 * Fastbrix: '.$file.'
 */

';
            if(file_exists($file))
            {
                $content = $content.file_get_contents($file);
            }
            else
            {
                $content = $content."/* WARNING: File not found! */";
            }
        }

        /* Create function calls */
        $onReadyFunctions = implode("", array_map(function($val){return $val."(freshLoad);\n";}, $onReady));
        $onLoadFunctions = implode("", array_map(function($val){return $val."(freshLoad);\n";}, $onLoad));

        $content=$content.'


/************************************************************
 * Fastbrix: Calls
 */

$(document).ready(function(){

    var freshLoad = true;

    '.$onReadyFunctions.'
});

$(window).load(function(){

    var freshLoad = true;

    '.$onLoadFunctions.'
});

function onFastbrixAjaxPageChange()
{
    var freshLoad = false;

    /* on ready */
    '.$onReadyFunctions.'
    /* on load */
    '.$onLoadFunctions.'
}


';
        /* Write to file */
        $dir = dirname($path);
        if(!file_exists($dir))
        {
            mkdir($dir, 0777, true);
        }
        file_put_contents($path, $content);

    }

}