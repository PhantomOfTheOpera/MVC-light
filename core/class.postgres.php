<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/11/16
 * Time: 14:53
 */

namespace MVC_light;

if (DATABASE == 'postgres') {
    class Database extends Ab_Database {

        protected $link;

        protected function query(string $query)
        {
            $res = pg_query($this->link, $query);
            if ($res === false)
                Service::error(pg_last_error($this->link), 'fatal');
            return $res;
        }

        protected function query_params(string $query, array $params)
        {
            $res = pg_query_params($this->link, $query, $params);
            if ($res === false)
                Service::error(pg_last_error($this->link), 'fatal');
            return $res;
        }

        protected function fetch_all($resource)
        {
            $res = pg_fetch_all($resource);
            if ($res === false)
                Service::error(pg_last_error($this->link));
            return $res;
        }

        protected function fetch_assoc($resource)
        {
            $res = pg_fetch_assoc($resource);
            if ($res === false)
                Service::error(pg_last_error($this->link));
            return $res;
        }

        protected function connect(string $host, string $user, string $password, string $db)
        {
            $this->link = pg_connect(
                "host=$host dbname=$db user=$user password=$password options='--client_encoding=UTF8'"
            );
            if ($this->link === false) {
                Service::error("Failed to connect to database");
            }
        }

        protected function close()
        {
            @pg_close($this->link);
        }

    }
}