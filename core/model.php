<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 05/08/16
 * Time: 20:36
 */
class Model
{
    public $db;


    public $menu = [
        [
            'name' => '',
            'address' => '',
            'icon' => ''//optional
        ]
    ];

    function get_common() {
        return [
            'site_name' => '',
            'title' => '',
            'menu' => $this->menu,
        ];
    }

    /**
     * @param null $controller
     * @return array
     */
    function get_data($controller = NULL) {
        if ($controller == '') // case of main page
            $controller = 'main';
        // then we check if current model class contains needed method
        $action = 'get_'.$controller;
        // if yes - receive params from there
        $specific_params = $this->$action();
        // then receive common params from root Model Class
        $common_params = $this->get_common();
        // return merged array
        // specific params are more important
        return array_merge($common_params, $specific_params);
    }

    function __construct($db = NULL) {
        $this->db = $db;
    }
}