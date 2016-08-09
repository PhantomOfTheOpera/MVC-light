<?php

/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/08/16
 * Time: 23:18
 */
class Ajax
{
    /**
     * @var params
     */
    public $params;

    /**
     * @var sample of controller class
     */
    public $controller;

    /**
     * @var string result message
     *
     */
    public $message = 'error';


    function __construct($controller) {
        $this->params = $_REQUEST;
        $this->controller = $controller;
    }
}