<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 20:31
 */


class Model_Main extends Model {

    function get_main() {
        Service::needs('css', 'main', true);
        Service::needs('js', 'src', true);
        Service::needs('less', 'style', true);
        return [
            'template' => 'main'
        ];
    }


}