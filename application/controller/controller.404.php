<?php

namespace MVC_light\Controller;

use \MVC_light\Controller as Controller;

class Controller_404 extends Controller {

    public function action_index(bool $route = false) {
        if (isset($route) && $route === true)
            ;
        echo $this->render('layout.twig', $this->model_data);
    }

}