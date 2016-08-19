<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/08/16
 * Time: 13:36
 */

class Controller_404 extends Controller {
    // TODO: check if it is still needed
    function action_index() {
        echo $this->view->render('layout.twig', $this->model->get_data());
    }
}