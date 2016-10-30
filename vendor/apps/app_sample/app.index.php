<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 30/10/16
 * Time: 14:01
 */


namespace MVC_light;

use MVC_light\Controller\Controller_app as Controller,
    MVC_light\Model as Model;

class Module {

    function start(Controller $Controller) {

        echo $Controller->render('layout.twig', array_merge($Controller->get_model_data(),
            ['template' => 'main']));

    }

}