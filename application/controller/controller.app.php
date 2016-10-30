<?php

namespace MVC_light\Controller;

use \MVC_light\Controller as Controller;
use MVC_light\Service;

class Controller_app extends Controller {

    function action_index(bool $route = false) {
        $new_app = Service::register_app('app_sample');
        $new_app->start($this);
    }

}