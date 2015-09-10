<?php
/**
 * Collects all needed assets
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


final class Collector {

    /**
     * Delivers code for <head> to include all necessary css and javascript
     */
    public static function getCssAndJs()
    {
        $files = self::collectAllFiles();
        var_dump($files);
    }

    /**
     * Parses all include.ini files and returns an 2-dimensional array
     * with all files that shall be included
     * @return array
     */
    private static function collectAllFiles()
    {
        $toLoad = array();
        $scss = array();
        $js = array();
        $jsOnReady = array();
        $jsOnLoad = array();

        /* Get all files that shall be included */
        $coreInclude = new IniReader("core/include.ini");
        $brixInclude = new IniReader("brix/include.ini");

        foreach($coreInclude->core as $brick)
        {
            if($brick != '')
            {
                $toLoad[] = 'core/'.$brick.'/';
            }
        }
        foreach($brixInclude->brix as $brick)
        {
            if($brick != '')
            {
                $toLoad[] = 'brix/'.$brick.'/';
            }
        }

        /* Add Template Variables */
        if(file_exists("template/variables.scss"))
        {
            $scss[] = "template/variables.scss";
        }

        /* Read all that inis */
        foreach($toLoad as $path)
        {
            $ini = new IniReader($path.'include.ini');
            $scss = array_merge($scss, array_map(function($val) use($path) {return $path.$val;},$ini->scss));
            $js = array_merge($js, array_map(function($val) use($path) {return $path.$val;},$ini->js));
            $jsOnReady = array_merge($jsOnReady, $ini->jsOnReady);
            $jsOnLoad = array_merge($jsOnLoad, $ini->jsOnLoad);
        }

        /* Add Template Styles */
        if(file_exists("template/styles.scss"))
        {
            $scss[] = "template/styles.scss";
        }

        return array(
            "scss" => $scss,
            "js" => $js,
            "jsOnReady" => $jsOnReady,
            "jsOnLoad" => $jsOnLoad
        );
    }

}