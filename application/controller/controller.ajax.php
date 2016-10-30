<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/08/16
 * Time: 17:10
 */
namespace MVC_light\Controller;
use \MVC_light\Controller as Controller;

 class Controller_Ajax extends Controller {

     /**
      * @param bool $route
      * @return void
      */
     public function action_index(bool $route = false) {
         $action_name = strtolower($this->MVC_Core->request[2]);
         require_once __DIR__.'/ajax/ajax_'.$action_name.'.php';
         $ajax_class_name = '\MVC_light\Ajax_'.$action_name;
         $class = new $ajax_class_name($this);
         $class->action();
     }

 }