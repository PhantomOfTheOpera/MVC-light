<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 30/10/16
 * Time: 09:21
 */


namespace MVC_light;


abstract class Ab_Model {

    /**
     * @var
     */
    protected $database;

    /**
     * @var array
     */
    protected $common = [
        'debug' => DEBUG
    ];

    /**
     * @var MVC_Core
     */
    protected $MVC_core;

    /**
     * @var array
     */
    static public $dependencies = [];

    /**
     * Ab_Model constructor.
     * @param MVC_Core $MVC_core
     */
    abstract function __construct(MVC_Core $MVC_core);

    /**
     * @return array
     */
    abstract protected function get_common() : array;

    /**
     * @return array
     */
    abstract protected function get_js_css() : array;

    /**
     * @param string $next_method
     * @return array
     */
    abstract function get_data(string $next_method) : array;

}