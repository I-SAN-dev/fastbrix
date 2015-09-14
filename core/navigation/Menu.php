<?php
/**
 * Renders bootstrap navigations
 *
 * This file is part of "fastbrix"
 *
 * @author Sebastian Antosch <s.antosch@i-san.de>
 * @copyright 2015 I-SAN.de Webdesign & Hosting GbR
 * @link http://i-san.de
 *
 * @license MIT
 */

namespace core\navigation;
use core\config\Loader as Config;


final class Menu {

    /**
     * Renders a menu
     * @param $menukey - the key of the menutree that should be rendered (as used in config.json)
     * @param $class - classes for the ul tag
     * @return string - html of ul
     */
    public static function get($menukey, $class)
    {
        $conf = Config::get(__NAMESPACE__);

        /* Check menu content */
        if(isset($conf[$menukey]) && is_array($conf[$menukey]))
        {
            $menu = $conf[$menukey];
        }
        else
        {
            $menu = NULL;
        }
        ob_start();
        include('partials/bootstrap3nav.phtml');
        return ob_get_clean();
    }

}