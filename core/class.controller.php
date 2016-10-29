<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 18:27
 */

namespace MVC_light;
use Twig_Environment, Exception, Twig_Loader_Filesystem;

class Controller {

    /**
     * @var Twig_Environment
     */
    protected $view;

    /**
     * @var - Core obj
     */
    protected $MVC_Core;

    /**
     * @var model obj
     */
    protected $model;

    /**
     * @var array received from model
     */
    protected $model_data = [];

    public static $render_time;

    /**
     * @var int - response status
     */
    public static $status = 200;

    public function action_index(bool $route = false) {
        if (isset($route) && $route === true)
            return false;
        echo $this->render('layout.twig', $this->model_data);
    }

    function __construct(MVC_Core $core) {
        session_start();
        $this->MVC_Core = $core;
        $this->view = $this->twig_start();
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

    protected function render(string $template, array $data) : string {
        $t1 = microtime(true);
        $str = $this->view->render($template, $data);
        $t2 = microtime(true);
        self::$render_time += $t2 - $t1;
        Service::$total_time += ($t2 - $t1);
        return $str;
    }


    private function twig_start() : Twig_Environment {
        $loader = new Twig_Loader_Filesystem(ROOT.'application/views');
        $twig = new Twig_Environment($loader,
            array(
                'cache' => TWIG_CACHE_DIR
            )
        );
        return $twig;
    }

    protected function connect_model() {
        $model = ROOT.'application/models/model_'.$this->MVC_Core->request[1].'.php';
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


