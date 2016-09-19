<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 20:31
 */

namespace MVC_light\Model;
use \MVC_light\Model as Model,
    \MVC_light\Service as Service;

class Model_Main extends Model {

    function get_main() : array {
        Service::needs('css', 'test', true);
        Service::needs('js', 'test', true);
        Service::needs('less', 'test1', true);
        $this->database = $this->MVC_core->needs_database();
        return [
            'template' => 'main',
            'db' => $this->database->check_database()
        ];
    }


}