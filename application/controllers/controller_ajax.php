<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/08/16
 * Time: 17:10
 */
 class Controller_Ajax extends Controller {

     /**
      * Careful: this file is static and should be modified. It handles routing to ajax class.
      */
     public function action_index() {
         $action_name = strtolower($this->core->request[2]);
         // that's done for 404 case.
         $ajax_class_name = 'Ajax';
         if (file_exists(ROOT.'application/controllers/ajax/ajax_'.$action_name.'.php')) {
             require_once ROOT.'application/controllers/ajax/ajax_' . $action_name . '.php';
             $ajax_class_name = 'Ajax_' . $action_name;
         }
         $class = new $ajax_class_name($this);
         /*
          * if no method exists we didn't include ajax file, or included file iw written incorrectly.
          * so, then only __construct and __destruct functions will be runed, and as a result will be
          * returned status 404.
          */
         if (method_exists($class, 'action'))
            $class->action();
     }
 }