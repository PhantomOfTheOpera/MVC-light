<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/08/16
 * Time: 17:10
 */
 class Controller_Ajax extends Controller {
     /**
      * start action
      */
     public function action_index() {
         $action_name = strtolower($this->core->request[2]);
         require_once 'ajax/ajax_'.$action_name.'.php';
         $ajax_class_name = 'Ajax_'.$action_name;
         $class = new $ajax_class_name($this);
         $class->action();
         echo $class->message;
     }
 }