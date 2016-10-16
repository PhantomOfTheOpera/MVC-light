<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 17:21
 */

namespace MVC_light;

require_once 'core/settings.php';

require_once 'core/service.autoloader.php';

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

new Autoloader();

new MVC_Core();