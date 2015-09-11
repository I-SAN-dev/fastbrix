<?php
/**
 * Includes favicons and stuff, see partials/icons.phtml
 *
 * This file is part of "fastbrix"
 *
 * @author Sebastian Antosch <s.antosch@i-san.de>
 * @copyright 2015 I-SAN.de Webdesign & Hosting GbR
 * @link http://i-san.de
 *
 * @license MIT
 */

namespace core\head;
use core\config\Loader as Config;


final class Icons {

    /**
     * Renders the icons partial
     * @return string - icons html
     */
    public static function get()
    {
        $conf = Config::get(__NAMESPACE__);
        $color = $conf["color"];
        $applestatusbarstyle = $conf["apple-status-bar-style"];

        ob_start();
        include('partials/icons.phtml');
        return ob_get_clean();
    }

}