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
     * will be returned as answer in json format. By default it's jsoned-restfull api
     * possible meanings:
     * 500 - error of any character. Details must be provided in 'message'
     * 200 - success, some details could be provided in 'message'.
     * 404 - unrecognized case
     */
    public $message = [
        'status' => '404',
        'message' => 'no action was presented on this url'
    ];

    function __construct($controller) {
        $this->params = $_REQUEST;
        $this->controller = $controller;
    }

    function __destruct() {
        echo json_encode($this->message);
    }

}