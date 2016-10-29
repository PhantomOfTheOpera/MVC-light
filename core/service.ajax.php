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

    function __construct(\MVC_light\Controller\Controller_Ajax $controller) {
        if (CSRF_SAFE && !\MVC_light\Service::validate_token($_REQUEST['token'])) {
            $this->message['state'] = 'Forbidden';
            $this->code = 403;
            exit();
        }
        $this->params = array_diff_key($_REQUEST, ['token' => 'anything']);
        $this->controller = $controller;
    }

    function __destruct() {
        http_response_code($this->code);
        echo json_encode($this->message);
    }
}