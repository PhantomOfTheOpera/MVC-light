<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 17:21
 */

namespace MVC_light;

// TODO: here must be formed installer script on first startup

require_once 'core/settings.php';

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

require_once 'core/service.autoloader.php';

new Autoloader();

new MVC_Core();