<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 18/09/16
 * Time: 11:52
 */

namespace MVC_light;


abstract class Ab_Database {

    abstract protected function query(string $query);

    abstract protected function query_params(string $query, array $params);

    abstract protected function fetch_assoc($resource);

    abstract protected function fetch_all($resource);

    abstract protected function connect(string $host, string $user, string $password, string $db);

    abstract protected function close();

    protected $link;

    function __construct(string $host = DB_HOST, string $user = DB_USER,
                         string $password = DB_PASSWORD, string $db = DB_NAME) {
        $this->connect(($host == DB_HOST) ? DB_HOST : $host, ($user == DB_USER) ? DB_USER : $user,
            ($password == DB_PASSWORD) ? DB_PASSWORD : $password, ($db == DB_NAME) ? DB_NAME : $db);
    }

    function __destruct()
    {
        $this->close();
    }

}