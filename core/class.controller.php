<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 18:27
 */

namespace MVC_light;
use Twig_Environment, Exception, Twig_Loader_Filesystem;

class Controller extends Ab_Controller {

    function __construct(MVC_Core $core) {
        session_start();
        $this->MVC_Core = $core;
        $this->view = $this->register_view();
        try {
            $this->connect_model();
        } catch (Exception $e) {
            Service::error($e);
        }
    }

    function __destruct() {
        session_write_close();
        http_response_code(self::$status);
    }

    public function action_index(bool $route = false) {
        if (isset($route) && $route === true)
            return false;
        echo $this->render('layout.twig', $this->model_data);
    }

    public function render(string $template, array $data) : string {
        $str = $this->view->render($template, $data);
        return $str;
    }

    protected function register_view() : Twig_Environment {
        $loader = new Twig_Loader_Filesystem(ROOT.'application/views');
        $twig = new Twig_Environment($loader,
            array(
                'cache' => TWIG_CACHE_DIR
            )
        );
        return $twig;
    }

    protected function connect_model() {
        $model = ROOT.'application/models/model.'.$this->MVC_Core->request[1].'.php';
        $model_name = '\MVC_light\Model\Model_'.$this->MVC_Core->request[1];
        $model_action = 'get_'.$this->MVC_Core->request[1];
        if (file_exists($model))
            require_once $model;
        else
            throw new Exception("Couldn't include $model");
        if (class_exists($model_name))
            $this->model = new $model_name($this->MVC_Core);
        else
            throw new Exception("Couldn't start $model_name class in $model");
        if (method_exists($this->model, $model_action))
            $this->model_data = $this->model->get_data($model_action);
        else
            throw new Exception("No $model_action presented in $model_name");
    }

}


