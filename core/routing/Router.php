<?php
/**
 * Decides which things shall be loaded
 *
 * This file is part of "fastbrix"
 *
 * @author Sebastian Antosch <s.antosch@i-san.de>
 * @copyright 2015 I-SAN.de Webdesign & Hosting GbR
 * @link http://i-san.de
 *
 * @license MIT
 */

namespace core\routing;
use core\config\Loader as Config;


final class Router {

    /**
     * Returns an array of page info which is needed to render the page
     */
    public static function getPageData()
    {
        $conf = Config::get();
        $rawurl = $_SERVER["REQUEST_URI"];
        /* ignore GET parameters here! They may be used by some brix, but are not used for routing inside Fastbrix  */
        $url = explode('?', $rawurl);

        /* now split up the url */
        $url = explode("/", $url);

        if(count($url)<2 || $url[1] == '')
        {
            $page = 'default';
        }
        else
        {
            $page = $url[1];
        }

        var_dump($url);
        echo("\n".$page);
    }

}