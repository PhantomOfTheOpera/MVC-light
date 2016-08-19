<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 05/08/16
 * Time: 20:36
 */

/**
 * Class Controller
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
     * @return bool false. Look next for more info
     */
    function action_index() {
        /*
         * this is usual action_index of Controller. It doesn't have routing as a purpose,
         * so only possible name of action is action_index
         * Sample of routing action_index could be found in Controller_Ajax
         */
        if ($this->core->action['name'] != 'action_index')
            return false;
        /*
         * we pass to model current controller's name. This allows to make
         * routing from main func of model
         */
        echo $this->view->render('layout.twig', $this->model->get_data($this->core->request[1]));
    }

    function __construct($core) {
        $this->view = $core->twig;
        $this->model = $core->model;
        $this->db = $core->db;
        $this->core = $core;
    }
}