<?php
/**
 * Reads an ini-File
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


class IniReader {

    /**
     * Parses the ini-File with the given path, checks only allowed variables
     * @param $path
     */
    public function __construct($path)
    {
        $this->scss = array();
        $this->js = array();
        $this->jsOnReady = array();
        $this->jsOnLoad = array();
        $this->core = array();
        $this->brix = array();

        if(file_exists($path) && $data = parse_ini_file($path, true))
        {
            /* scss files */
            if(
                isset($data['scss'])
                && isset($data['scss']['file'])
                && is_array($data['scss']['file'])
            )
            {
                $this->scss = $data['scss']['file'];
            }
            /* js files */
            if(isset($data['js']))
            {
                if(isset($data['js']['file']) && is_array($data['js']['file']))
                {
                    $this->js = $data['js']['file'];
                }
                if(isset($data['js']['jsOnReady']) && is_array($data['js']['jsOnReady']))
                {
                    $this->jsOnReady = $data['js']['jsOnReady'];
                }
                if(isset($data['js']['jsOnLoad']) && is_array($data['js']['jsOnLoad']))
                {
                    $this->jsOnLoad = $data['js']['jsOnLoad'];
                }
            }
            /* core-brix */
            if(
                isset($data['core'])
                && isset($data['core']['load'])
                && is_array($data['core']['load'])
            )
            {
                $this->core = $data['core']['load'];
            }
            /* brix */
            if(
                isset($data['brix'])
                && isset($data['brix']['load'])
                && is_array($data['brix']['load'])
            )
            {
                $this->brix = $data['brix']['load'];
            }
        }
    }

}