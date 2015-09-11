<?php
/**
 * Creates a nice set of metadata
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
use core\routing\Router;

final class Metadata {

    /**
     * Renders the metadata partial with data from various configs
     * @return string - metadata html
     */
    public static function get()
    {
        $mainconf = Config::get();
        $conf = Config::get(__NAMESPACE__);
        $page = Router::getPageData();

        $title = $page["title"];
        $description = $page["description"];
        $author = $conf["author"];
        $banner = $conf["social"]["banner"];
        $gpluslink = $conf["social"]["gpluslink"];
        $type = $conf["social"]["type"];
        $sitename = $mainconf["name"];
        $fbadmins = $conf["social"]["fbadmins"];

        ob_start();
        include('partials/metadata.phtml');
        return ob_get_clean();
    }

}