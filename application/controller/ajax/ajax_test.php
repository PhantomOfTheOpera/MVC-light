<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 29/10/16
 * Time: 20:58
 */


class Ajax_test extends Ajax {

    function action() {
        $this->message['state'] = 'success';
        $this->code = 200;
    }

}