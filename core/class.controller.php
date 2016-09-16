<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 18:27
 */

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
     * @var data received from model
     */
    protected $model_data = [];

    /**
     * @var int - response status
     */
    public static $status = 200;

    public function action_index() {
        if ($this->MVC_Core->request[2] != 'index')
            return false;
        echo $this->render('layout.twig', $this->model_data);

    }

    function __construct($core) {
        $this->MVC_Core = $core;
        $this->view = $this->twig_start();
        try {
            $this->connect_model();
        } catch (Exception $e) {
            tools::error($e);
        }
    }

    function __destruct() {
        http_response_code(self::$status);
    }

    protected function render($template, $data) {
        return $this->view->render($template, $data);
    }


    private function twig_start() {
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
        $model_name = 'Model_'.$this->MVC_Core->request[1];
        $model_action = 'get_'.$this->MVC_Core->request[1];
        if (file_exists($model))
            require_once $model;
        else
            throw new Exception("Couldn't include $model");
        if (class_exists($model_name))
            $this->model = new $model_name();
        else
            throw new Exception("Couldn't start $model_name class in $model");
        if (method_exists($this->model, $model_action))
            $this->model_data = $this->model->get_data($model_action);
        else
            throw new Exception("No $model_action presented in $model_name");
    }


}


