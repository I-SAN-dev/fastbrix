<?php
/**
 * Loads JSON-encoded config-files
 *
 * This file is part of "fastbrix"
 *
 * @author Sebastian Antosch <s.antosch@i-san.de>
 * @copyright 2015 I-SAN.de Webdesign & Hosting GbR
 * @link http://i-san.de
 *
 * @license MIT
 */

namespace core\config;


final class Loader {

    /**
     * @var array $bricksconf - already parsed configs of used bricks
     */
    private static $bricksconf = array();

    /**
     * Loads a JSON-File and returns it's contents as multidimensional array
     * @param $path - the path to the json file
     * @return array
     */
    private static function loadJSON($path)
    {
        if(!file_exists($path))
        {
            return NULL;
        }
        $file = file_get_contents($path);
        return json_decode($file, true);
    }

    /**
     * Gets the core config or config for a given brick
     * @param null|string $brick
     * @return array
     * @throws \Exception
     */
    public static function get($brick = NULL)
    {
        /* If no brick is defined, return the default config */
        if(!$brick || $brick == '')
        {
            $brick = 'core\config';
        }
        /* Only pare a json file one time, than hold the data */
        if(!isset(self::$bricksconf[$brick]))
        {
            $path = str_replace('\\','/', $brick).'/config.json';
            try
            {
                self::$bricksconf[$brick] = self::loadJSON($path);
            }
            catch(\Exception $e)
            {
                throw new \Exception('Error loading config for '.$brick, 500, $e);
            }
        }
        return self::$bricksconf[$brick];
    }

}