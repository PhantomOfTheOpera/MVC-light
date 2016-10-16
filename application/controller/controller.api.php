<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 16/10/16
 * Time: 08:24
 */

namespace MVC_light\Controller;
use \MVC_light\Controller as Controller,
    \MVC_light\Api as Api;


class Controller_Api extends Controller {

    public function action_index(bool $route = true)
    {
        try {
            Api::registerApiClass("Sample");
            Api::init($this->MVC_Core->request[2]);
        } catch (Exception $exc) {
            // TODO: service::error??
            echo Api::response($exc->getMessage(), $exc->getCode());
        }
        return true;
    }

}