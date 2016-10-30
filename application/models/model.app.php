<?php


namespace MVC_light\Model;
use \MVC_light\Model as Model,
    \MVC_light\Service as Service;

class Model_app extends Model {

    function get_app() {

        return [
            'template' => 'app'
        ];

    }

}