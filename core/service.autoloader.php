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
class Autoloader {

    /**
     * @var array of /core files.
     * Here are stored their names after scandir:
     *      settings.php and this file are not stored here
     * after that, all of them are included in foreach
     */
    private $files = [];

    /**
     * @var array of component's folders
     * received after scandir
     * on components init we try to include api.component.php in ths folders
     */
    public static $components = [];

    /**
     * Autoloader constructor.
     *
     * fills files and components variables.
     * All errors must be treated as fatal and if smth was caught
     * script should be stopped and exited.
     * For more info look at MVC_error func
     */
    function __construct() {
        $t1 = microtime(true);
        $this->files = array_diff(scandir(ROOT.'core', 1),
            ['.', '..', 'settings.php', 'abstract.autoloader.php',  'interface.database.php']);
        self::$components = array_diff(scandir(ROOT.'vendor/components', 1), ['.', '..']);
        try {
            $this->init();
        } catch (Exception $e) {
            Service::error($e);
        }
        $t2 = microtime(true);
        Service::$time_debug_string .= "Autoloader took: ".($t2 - $t1)."\n";
        Service::$total_time += ($t2 - $t1);
    }

    /**
     * includes core and components files.
     *
     * @throws Exception - cases when expected files are not presented
     */
    function init() {
        require_once ROOT . 'core/abstract.database.php';
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


    /**
     * @param string $address - plugin folder name
     * provide plugin connect api
     */
    static function register(string $address) {

        $plugin_name = ROOT.'/vendor/plugins/'.$address.'/api.plugin.php';
        if (file_exists($plugin_name))
            require_once $plugin_name;
        elseif (DEBUG) {
            die('Autoloader on line '.__LINE__.' failed to include '.$plugin_name);
        }

    }

}