<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 19/09/16
 * Time: 21:49
 */

namespace MVC_light;

class DB_main extends Database {

    function check_database() {

        return $this->fetch_all(
            $this->query('select * from test')
        );

    }

}