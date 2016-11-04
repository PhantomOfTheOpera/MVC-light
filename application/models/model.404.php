<?php


namespace MVC_light\Model;
use \MVC_light\Model as Model,
    \MVC_light\Service as Service;

class Model_404 extends Model {

    function get_404() {

        return [
            'template' => '404',
            'prev_page' => substr($_SERVER['REQUEST_URI'], 5)
        ];

    }

}