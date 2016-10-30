<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 30/10/16
 * Time: 09:29
 */

namespace MVC_light;


abstract class Ab_Core {

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
    protected $name_controller = 'Controller_main';

    /**
     * @var string = path to controller
     */
    protected $path_controller =  ROOT.'application/controller/controller.main.php';

    /**
     * @var object - here would be stored controller object on creation
     */
    protected $obj_controller;

    /**
     * @var string - default action name
     */
    protected $action = 'action_index';

    /**
     * MVC_Core constructor.
     * fills request var and inits controller
     */
    abstract function __construct();

    /**
     * 404 case function
     * is ran when couldn't find controller file, or action
     * method isn't presented in controller
     */
    abstract protected function error404();

    /**
     * Firstly checks if request is filled enough for starting controller
     * then forms name, path,, and inits controller
     * if no file presented - 404
     * if no method presented we run action_index method - thas allows to make routing from controller
     * if itb returns false - that's 404
     * @throws Exception
     */
    abstract function init();


    abstract public function needs_database();
}