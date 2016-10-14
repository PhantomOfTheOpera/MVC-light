<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 14.10.16
 * Time: 21:19
 */

require_once "../core/service.api.php";

/**
 * Api initialization
 */

try {
//    Register all api classes you need

    Api::registerApiClass("Sample");
//    Api::registerApiClass("Sample2");
//    Api::registerApiClass("Sample3");

//    Init Api, Parse query and execute request
    Api::init();

} catch (Exception $exc) {
    echo Api::response($exc->getMessage(), $exc->getCode());
}
