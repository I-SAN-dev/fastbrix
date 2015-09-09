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
        $pages = $conf['pages'];

        $rawurl = $_SERVER["REQUEST_URI"];
        /* ignore GET parameters here! They may be used by some brix, but are not used for routing inside Fastbrix  */
        $url = explode('?', $rawurl);

        /* now split up the url */
        $url = explode("/", $url[0]);


        /* check some special cases */
        if(count($url)<2 || $url[1] == '')
        {
            return self::processData($pages['default']);
        }
        else if($url[1] == 'default')
        {
            self::redirectTo();
        }
        else if($url[1] == 'handler')
        {
            // TODO: handle handlers, so we do not need to create urls for each used handler manually
        }
        else
        {
            if (isset($pages[$url[1]]) && is_array($pages[$url[1]]))
            {
                /* Get the deepest matching path */
                $currentobj = $pages[$url[1]];
                $positionToCheck = 2;
                $failed = false;
                $suffixPathSegments = array();
                while(isset($url[$positionToCheck]) && $url[$positionToCheck] != '')
                {
                    if(!$failed && isset($currentobj['pages']) && isset($currentobj['pages'][$url[$positionToCheck]]))
                    {
                        $currentobj = $currentobj['pages'][$url[$positionToCheck]];
                    }
                    else
                    {
                        $failed = true;
                        $suffixPathSegments[] = $url[$positionToCheck];
                    }
                    $positionToCheck++;
                }
                /* check if additionalPath should be kept for further use in page */
                if($currentobj['keepAdditionalPath'])
                {
                    return self::processData($currentobj, $suffixPathSegments);
                }
                else if(count($suffixPathSegments)==0)
                {
                    return self::processData($currentobj);
                }
                else
                {
                    return self::show404();
                }
            }
            else
            {
                return self::show404();
            }
        }
    }

    /**
     * Redirects to a given path, defaults to home
     * @param $path
     */
    private static function redirectTo($path = '')
    {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $redirectto = $protocol.$_SERVER["SERVER_NAME"].'/'.$path;
        header("Location:".$redirectto, true, 301);
        die();
    }

    /**
     * Loads Data for 404 page
     * @return array - page array with 404 page
     */
    private static function show404()
    {
        $conf = Config::get();
        /* Set the header only for get requests, otherwise an ajax 404 produces an endless loop */
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            header("HTTP/1.0 404 Not Found", true, 404);
        }
        return self::processData($conf['pages']['404']);

    }

    /**
     * Takes a page data object and replaces markers etc, then returns the processed object
     * @param $data
     * @param $additionalPath (optional) - additional path steps that may be returned if allowed in config
     * @return array
     */
    private static function processData($data, $additionalPath = NULL)
    {
        $conf = Config::get();

        /* define markers */
        $replacements = array(
            "%name%" => $conf['name'],
            "%url%" => $conf['baseurl']
        );

        /* use default description, if none is set */
        if(!isset($data['description']) || $data['description'] == '')
        {
            $data['description'] = $conf['description'];
        }
        /* replace markers in description */
        $data['description'] = strtr($data['description'], $replacements);

        /* Build title */
        $data['title'] = str_replace('%title%', $data['title'], $conf['title']);

        /* replace markers in title */
        $data['title'] = strtr($data['title'], $replacements);

        /* remove subpages */
        unset($data['pages']);

        /* add additional path if delivered */
        if($additionalPath && is_array($additionalPath))
        {
            $data['additionalPath'] = $additionalPath;
        }
        return $data;
    }

}