<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 05/08/16
 * Time: 20:36
 */

class Controller {

    /**
     * @var model class sample
     */
    public $model;

    /**
     * @var Twig_Environment
     */
    public $view;

    /**
     * @var mysqli
     */
    public $db;

    /**
     * @var core class object
     */
    public $core;

    /**
     * renders layout according to Model::get_data() method. Default controller action
     */
    function action_index() {
        echo $this->view->render('layout.twig', $this->model->get_data());
    }

    function __construct($core) {
        $this->view = $core->twig;
        $this->model = $core->model;
        $this->db = $core->db;
        $this->core = $core;
    }
}