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

    /**
     * @var array list of pages. Yet used only on 404
     */
    public $pages404 = [
        [
            'name' => '',
            'address' => ''
        ]
    ];

    public $menu = [
        [
            'name' => '',
            'address' => '',
            'icon' => ''//optional
        ]
    ];

    /**
     * @param null $controller
     * @return array
     */
    function get_data($controller = NULL) {
        if ($controller == '') // case of main page
            $controller = 'main';
        // then we check if current model class contains needed method
        if (method_exists($this, 'get_'.$controller)) {
            $action = 'get_'.$controller;
            // if yes - receive params from there
            $specific_params = $this->$action();
            // then receive common params from root Model Class
            $common_params = array('menu' => $this->menu);
            // return merged array
            return array_merge($specific_params, $common_params);
        }
        // 404 case
        return array(
            'address' => urldecode($_SERVER['REQUEST_URI']),
            'template' => '404',
            'pages' => $this->pages404,
            'menu' => $this->menu
        );
    }

    function __construct($db = NULL) {
        $this->db = $db;
    }
}