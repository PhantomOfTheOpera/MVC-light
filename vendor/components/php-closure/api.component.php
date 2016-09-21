<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 16/09/16
 * Time: 14:49
 */
namespace MVC_light\components;
use tureki, \MVC_light\Autoloader as Autoloader;


require_once __DIR__.'/src/tureki/PhpCc.php';

Autoloader::$components['phpcc'] = new tureki\PhpCc(array(
    'java_file' => 'java',
    'output_path' => ROOT.'assets/js/',
    'optimization' => 'ADVANCED_OPTIMIZATIONS',
    'charset' => 'utf-8',
));
