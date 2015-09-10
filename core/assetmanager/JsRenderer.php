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
    public static function render($files, $onReady, $onLoad, $path)
    {

    }

}