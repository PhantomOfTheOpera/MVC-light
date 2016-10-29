<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 20:38
 */

namespace MVC_light;

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

    protected $MVC_core;

    /**
     * @var array interface for dependencies inclusion
     */
    static public $dependencies = [];


    function __construct(MVC_Core $MVC_core) {
        $this->MVC_core = $MVC_core;
    }

    private function get_common() : array {
        Service::needs('js', 'AF-light', true);
        $this->common['token'] = Service::generate_token();
        return $this->common;
    }

    private function get_js_css() : array {
        return [
            'includes' => self::$dependencies
        ];
    }

    function get_data(string $next_method) : array {

        $specific_params = $this->$next_method();

        $common_params = $this->get_common();

        $includes = $this->get_js_css();

        return array_merge($common_params, $specific_params, $includes);

    }



}