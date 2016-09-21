<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 16/09/16
 * Time: 18:19
 */

namespace MVC_light\components;
use MVC_light\Autoloader as Autoloader, Less_Parser;

require ROOT.'vendor/components/Less/lessc.inc.php';

Autoloader::$components['less'] = new \lessc();
