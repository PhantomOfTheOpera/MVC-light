<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 16/09/16
 * Time: 18:19
 */

require ROOT.'vendor/components/Less/lessc.inc.php';

Autoloader::$components['less'] = new Less_Parser();