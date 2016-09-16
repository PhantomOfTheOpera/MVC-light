<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 20:38
 */

class Model {

    protected $database;

    private $common = [
        'debug' => DEBUG
    ];

    function __construct() {
        // !?
    }

    private function get_common() {

        return $this->common;
    }

    function get_data($next_method) {

        $specific_params = $this->$next_method();

        $common_params = $this->get_common();

        return array_merge($common_params, $specific_params);

    }

}