<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 17:37
 */

namespace MVC_light;
use Exception;

class MVC_Core {

    /**
     * @var array of exploded request url.
     * Created on __construct. Explosion is made by / and ?
     * all '-' are replaced with 'T'
     */
    public $request = [];

    /**
     * @var string - controller name.
     * here is stored current controller's class name
     */
    private $name_controller = 'Controller_main';

    /**
     * @var string = path to controller
     */
    private $path_controller =  ROOT.'application/controller/controller.main.php';

    /**
     * @var object - here would be stored controller object on creation
     */
    public $obj_controller;

    /**
     * @var string - default action name
     */
    private $action = 'action_index';

    /**
     * MVC_Core constructor.
     * fills request var and inits controller
     */
    function __construct() {
        $t1 = microtime(true);
        $this->request = Service::convert_url($_SERVER['REQUEST_URI']);
        try {
            $this->init();
        } catch (Exception $e) {
            Service::error($e);
        }
        $t2 = microtime(true);
        Service::$time_debug_string .= 'Core took: '.($t2 - $t1)."\n";
        Service::$total_time += ($t2 - $t1);
    }

    /**
     * 404 case function
     * is ran when couldn't find controller file, or action
     * method isn't presented in controller
     */
    private function error404() {
        http_response_code(404);
        die('404');
    }

    function __destruct() {
        Service::$time_debug_string .= 'Render took: '.Controller::$render_time;
        if (TIME_DEBUG) {
            echo Service::$time_debug_string . "\n".
                "Js minification took: ". Service::$js_compilation_time."\n".
                "Css minification took: ". Service::$css_compilation_time."\n".
                "Less minification took: ". Service::$less_compilation_time."\n".
                "Total time: ". Service::$total_time."\n\n-->";
        }
    }

    /**
     * Firstly checks if request is filled enough for starting controller
     * then forms name, path,, and inits controller
     * if no fie presented - 404
     * if no method presented we run action_index method - thas allows to make routing from controller
     * if itb returns false - that's 404
     * @throws Exception
     */
    function init() {
        if (empty($this->request[1]))
            $this->request[1] = 'main';
        if (empty($this->request[2]))
            $this->request[2] = 'index';
        $this->path_controller = ROOT . 'application/controller/controller.' . $this->request[1] . '.php';
        $this->name_controller = 'MVC_light\Controller\Controller_'.$this->request[1];
        $this->action = 'action_'.$this->request[2];
        if (file_exists($this->path_controller))
            require_once $this->path_controller;
        else
            $this->error404();
        if (class_exists($this->name_controller))
            $this->obj_controller = new $this->name_controller($this);
        else
            throw new Exception('Not found class '.$this->name_controller.' in '.$this->path_controller);
        if (method_exists($this->obj_controller, $this->action)) {
            $method = $this->action;
            $obj = $this->obj_controller;
            $obj->$method();
        } elseif ($this->obj_controller->action_index(true) === false)
            $this->error404();
    }


    public function needs_database() {
        $name = 'DB_'.$this->request[1];
        $file = ROOT.'application/controller/database/'.$name.'.php';
        $name = '\MVC_light\DB_'.$this->request[1];
        try {
            if (file_exists($file)) {
                require_once $file;
                if (class_exists($name))
                    return new $name;
                else
                    throw new Exception("$name doesn't exist");
            } else
                throw new Exception("$file doesn't exist");
        } catch (Exception $e) {
            Service::error($e);
        }
    }
}