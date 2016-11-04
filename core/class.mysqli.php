<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 18/09/16
 * Time: 11:52
 */

namespace MVC_light;
use mysqli, mysqli_result, mysqli_sql_exception, Exception, Error;

if (DATABASE == 'mysql') {

    class Database extends Ab_Database
    {


        protected function query(string $query)
        {
            try {
                return $this->link->query($query);
            } catch (Error $e) {
                Service::error($e);
            }
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
            try {
                return $resource->fetch_all();
            } catch (Error $e) {
                Service::error($e);
            }
        }

        protected function fetch_assoc($resource)
        {
            try {
                return $resource->fetch_assoc();
            } catch (Error $e) {
                Service::error($e);
            }
        }

        protected function close()
        {
            try {
                $this->link->close();
            } catch (Error $e) {
                Service::error($e);
            }
        }

        protected function connect(string $host, string $user, string $password, string $db)
        {
            if (DEBUG)
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                $this->link = new mysqli($host, $user, $password, $db);
            } catch (Exception $e) {
                Service::error($e, 'fatal');
            }
            $this->link->set_charset('UTF-8');
        }

        protected function get_error($resource)
        {
            // TODO: Implement get_error() method.
        }


    }

}