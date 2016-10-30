<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/08/16
 * Time: 23:18
 */

namespace MVC_light;

use MVC_light\Controller\Controller_Ajax as Controller_Ajax;
class Ajax extends Ab_Ajax
{
    function __construct(Controller_Ajax $controller) {
        if (CSRF_SAFE && !Service::validate_token($_REQUEST['token'])) {
            $this->message['state'] = 'Forbidden';
            $this->code = 403;
            exit();
        }
        $this->params = array_diff_key($_REQUEST, ['token' => 'anything']);
        $this->controller = $controller;
    }

    function __destruct() {
        http_response_code($this->code);
        $this->message['code'] = $this->code;
        echo json_encode($this->message);
    }
}