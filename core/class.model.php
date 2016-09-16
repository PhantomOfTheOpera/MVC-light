<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 20:38
 */

class Model {

    /**
     * @var object of database class
     */
    protected $database;

    /**
     * @var array of common params
     */
    private $common = [
        'debug' => DEBUG
    ];

    /**
     * @var array interface for dependencies inclusion
     */
    static public $dependencies = [];

    function __construct() {
        // !?
    }

    private function get_common() {
        Service::needs('js', 'AF-light', 'true');
        return $this->common;
    }

    private function get_js_css() {
        return [
            'includes' => self::$dependencies
        ];
    }

    function get_data($next_method) {

        $specific_params = $this->$next_method();

        $common_params = $this->get_common();

        $includes = $this->get_js_css();

        return array_merge($common_params, $specific_params, $includes);

    }

}