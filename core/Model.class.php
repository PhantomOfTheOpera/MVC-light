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
    public $pages = [
        ['name' => 'Регистрация', 'address' => '/main'],
        ['name' => 'Alterschool - Главная', 'address' => 'http://alterschool.com']
    ];

    /**
     * @return array for 404 error
     */
    function get_data() {
       return array(
            'address' => urldecode($_SERVER['REQUEST_URI']),
            'template' => '404',
            'pages' => $this->pages
        );
    }

    function __construct($db = NULL) {
        $this->db = $db;
    }
}