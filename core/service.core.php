<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 17:37
 */

namespace MVC_light;
use Exception;

class MVC_Core extends Ab_Core {

    function __construct() {
        $this->request = Service::convert_url($_SERVER['REQUEST_URI']);
        try {
            $this->init();
        } catch (Exception $e) {
            Service::error($e);
        }
    }

    protected function error404() {
        http_response_code(404);
        header('Location: /404'.$_SERVER['REQUEST_URI']);
        exit(0);
    }

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