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
        try {
            throw new \Exception('What a fuck');
        } catch (\Exception $e) {
            Service::error($e, 'fatal');
        }
        return [
            'template' => 'main'
        ];
    }


}