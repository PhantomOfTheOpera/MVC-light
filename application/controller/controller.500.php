<?php

namespace MVC_light\Controller;

use \MVC_light\Controller as Controller;

class Controller_500 extends Controller {

    public function action_index(bool $route = false) {
        echo $this->render($this->model_data['template'].'.twig', $this->model_data);
        $_SESSION['error'] = null;
        return true;
    }

}