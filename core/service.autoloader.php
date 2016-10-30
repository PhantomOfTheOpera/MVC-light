<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 17:22
 */

namespace MVC_light;
use Exception;
/**
 * called on start. Loads core of framework, then components.
 * provides method for plugin register
 *
 * Class Autoloader
 */
require_once ROOT.'core/abstracts/abstract.autoloader.php';
class Autoloader extends Ab_Autoloader {

    function __construct() {
        $this->abstracts = array_diff(scandir(ROOT.'core/abstracts', 1), ['.', '..']);
        $this->files = array_diff(scandir(ROOT.'core', 1), ['.', '..', 'settings.php', 'abstracts',]);
        self::$components = array_diff(scandir(ROOT.'vendor/components', 1), ['.', '..']);
        try {
            $this->init();
        } catch (Exception $e) {
            Service::error($e);
        }
    }

    function init() {
        foreach ($this->abstracts as $file) {
            $file = ROOT.'core/abstracts/'.$file;
            if (file_exists($file))
                require_once $file;
            else
                throw new Exception('Autoloader failed to include '.$file);
        }
        foreach ($this->files as $file) {
            $file = ROOT.'core/'.$file;
            if (file_exists($file))
                require_once $file;
            else
                throw new Exception('Autoloader failed to include '.$file);
        }
        foreach (self::$components as $component) {
            if (!is_dir(ROOT.'vendor/components/'.$component))
                throw new Exception('No files should be stored in components folder');
            $component = ROOT.'vendor/components/'.$component.'/api.component.php';
            if (file_exists($component))
                require_once $component;
            else
                throw new Exception('Autoloader failed to include '.$component);
        }

    }

    static function register(string $address) {
        $plugin_name = ROOT.'/vendor/plugins/'.$address.'/api.plugin.php';
        if (file_exists($plugin_name))
            require_once $plugin_name;
        elseif (DEBUG) {
            die('Autoloader on line '.__LINE__.' failed to include '.$plugin_name);
        }
    }
}