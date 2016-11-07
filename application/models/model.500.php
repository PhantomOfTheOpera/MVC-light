<?php


namespace MVC_light\Model;
use \MVC_light\Model as Model,
    \MVC_light\Service as Service;

class Model_500 extends Model {

    function get_500() {
        if (!array_key_exists('error', $_SESSION) || $_SESSION['error'] == null) {
            $this->MVC_core->error404();
        }
        return [
            'template' => '500',
            'error' => array_key_exists('error', $_SESSION) ? $_SESSION['error'] : 'null'
        ];

    }

}