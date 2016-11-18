<?php

namespace MVC_light\Controller;

use \MVC_light\Controller as Controller;

class Controller_500 extends Controller {

    public function action_index(bool $route = false) {
        echo $this->render($this->model_data['template'].'.twig', $this->model_data);
        $current_log_file = ROOT.'application/errors/'.date("j-n").'_log';
        file_put_contents($current_log_file, date("j-n")." - ".$_SESSION['error']."\n", FILE_APPEND);
        $_SESSION['error'] = null;
        return true;
    }

}