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


final class CssRenderer {

    /**
     * @param $files - array of files to include
     * @param $path - path to file to write to
     */
    public static function render($files, $path)
    {
        $scss = self::buildScss($files);
        require_once('scssphp/scss.inc.php');
        $scssc = new \scssc();
        $css = $scssc->compile($scss);
        $dir = dirname($path);
        if(!file_exists($dir))
        {
            mkdir($dir, 0777, true);
        }
        file_put_contents($path, $css);
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