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

    /*
     * array of css file includes
     */
    public $css = [
        'main.css'
    ];

    /**
     * @var array of arrays
     * url, defer - bool
     */
    public $js = [
        [
            'url' => 'ajax_form.js',
            'defer' => false
        ]
    ];

    function get_common() {
        return [
            'site_name' => SITE_NAME,
            'root' => WEB_ROOT_FOLDER,
            'title' => '_'.SITE_NAME,
            'menu' => $this->menu,
            'css_common' => $this->css,
            'js_common' => $this->js,
            'domain' => DOMAIN_NAME
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