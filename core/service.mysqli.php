<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 18/09/16
 * Time: 11:52
 */

namespace MVC_light;
use mysqli, mysqli_result, mysqli_sql_exception, Exception;

if (DATABASE == 'mysql') {

    class Database extends Ab_Database
    {


        protected function query(string $query)
        {
            return $this->link->real_escape_string(
                $this->link->query($query)
            );
        }

        protected function query_params(string $query, array $params)
        {
            $i = 1;
            $safe_params = [];
            foreach ($params as $item)
                $safe_params[] = $this->link->real_escape_string($item);
            foreach ($safe_params as $param) {
                $query = str_replace('$'.$i, "'".$param."'", $query);
                $i++;
            }
            return $this->query($query);
        }

        protected function fetch_all($resource)
        {
            return $resource->fetch_all();
        }

        protected function fetch_assoc($resource)
        {
            return $resource->fetch_assoc();
        }

        protected function close()
        {
            // TODO: Implement close() method.
        }

        protected function connect(string $host, string $user, string $password, string $db)
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                try {
                    $this->link = new mysqli($host, $user, $password, $db);
                } catch (mysqli_sql_exception $e) {
                    throw $e;
                }
            } catch (Exception $e) {
                Service::error($e);
            }
            $this->link->set_charset('UTF-8');
        }

        protected function get_error($resource)
        {
            // TODO: Implement get_error() method.
        }


    }

}