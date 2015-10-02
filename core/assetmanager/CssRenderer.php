<?php
/**
 * Renders a CSS File from given scss files
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

use Leafo\ScssPhp;
use core\config\Loader as Config;



final class CssRenderer {

    /**
     * @param $files - array of files to include
     * @param $path - path to file to write to
     */
    public static function render($files, $path)
    {
        /*
         * We need an additional autoloader for the ScssPhp Framework,
         * because it relies on that we have one if we use the new non-deprecated namespaced approach
         */
        spl_autoload_register(function($class){
            //load file from namespace
            $class = str_replace('\\','/', $class).'.php';
            $class = str_replace('Leafo/ScssPhp', 'core/assetmanager/scssphp/src', $class);
            if(file_exists($class))
            {
                require_once($class);
            }
        });

        $conf = Config::get();
        $debug = $conf["debug"];

        $scss = self::buildScss($files);
        $scssc = new \Leafo\ScssPhp\Compiler();
        try
        {
            $css = $scssc->compile($scss);
        }
        catch(Exception $e)
        {
            echo($e->getMessage());
        }


        /* Header */
        $content = '

/*
 * Generated with Fastbrix
 * at '.date('d.m.Y H:i:s').'
 */

';

        if(!$debug)
        {
            /* compress css */
            /* remove comments */
            $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
            /* remove tabs, spaces, newlines, etc. */
            $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        }

        $content = $content.$css;

        $dir = dirname($path);
        if(!file_exists($dir))
        {
            mkdir($dir, 0777, true);
        }
        file_put_contents($path, $content);
    }


    /**
     * Create valid scss code to import all necessary files
     * @param $files - array of paths to scss files
     * @return string - the scss code
     */
    private static function buildScss($files)
    {
        $content = '';
        foreach ($files as $path)
        {
            $path = str_replace('.scss','', $path);
            $content = $content.'
@import "'.$path.'";
            ';
        }
        return $content;

    }

}