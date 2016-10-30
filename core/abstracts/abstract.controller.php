<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 30/10/16
 * Time: 09:06
 */

namespace MVC_light;

abstract class Ab_Controller {

    /**
     * @var - template engine sample
     *      Is inited by @function register_view()
     */
    protected $view;

    /**
     * @var MVC_Core
     *      initialized on construct
     */
    protected $MVC_Core;

    /**
     * @var Model class sample
     *      initialized by @function connect_model()
     */
    protected $model;

    /**
     * @var array - data received from Model
     *      initialized by connect_model()
     */
    protected $model_data;

    /**
     * @var int
     *      response page code; By default is 200.
     *      on destruct is responced
     */
    public static $status = 200;


    public function get_model_data() {
        return $this->model_data;
    }
    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    abstract public function render(string $template, array $data) : string;

    /**
     * @param bool $route
     * @return mixed
     */
    abstract public function action_index(bool $route = false);

    /**
     * @return mixed
     */
    abstract protected function register_view();

    /**
     * @return mixed
     */
    abstract protected function connect_model();

    /**
     * Ab_Controller constructor.
     * @param MVC_Core $core
     */
    abstract function __construct(MVC_Core $core);

    /**
     *
     */
    abstract function __destruct();
}