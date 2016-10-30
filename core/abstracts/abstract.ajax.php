<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 30/10/16
 * Time: 09:52
 */

namespace MVC_light;


use MVC_light\Controller\Controller_Ajax as Controller_Ajax;

abstract class Ab_Ajax {

    /**
     * @var string params
     */
    public $params;

    /**
     * @var \MVC_light\Controller\Controller_Ajax sample of controller class
     */
    public $controller;

    /**
     * @var array result message
     * will be returned as answer
     */
    public $message = [
        'state' => 'error'
    ];

    public $code = 500;

    abstract function __construct(Controller_Ajax $controller);

    abstract function __destruct();
}