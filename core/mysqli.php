<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/08/16
 * Time: 18:12
 */

class Database {

    /**
     * @var here mysqli object is stored
     */
    public $mysqli;

    /**
     * throws mysqli exception
     */
    private function connect() {
        try {
            $this->mysqli = new mysqli('localhost', MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    /**
     * @param $string query func wrap
     */
    public function query($string) {
        try {
            return $this->mysqli->query($string);
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    /**
     * pg_query_params mysqli implementation. Why?
     * Just cause it's cool
     * @param $query
     * @param array $params
     */
    public function query_params($query, $params = []) {
        $i = 1;
        $safe_params = [];
        foreach ($params as $item)
            $safe_params[] = $this->mysqli->real_escape_string($item);
        foreach ($safe_params as $param) {
            $query = str_replace('$'.$i, "'".$param."'", $query);
            $i++;
        }
        return $this->query($query);
    }

    /**
     * Database constructor.
     */
    function __construct() {
        try {
            $this->connect();
        } catch (Exception $e) {
            die($e->errorMessage());
        }
        $this->mysqli->set_charset("utf8");
    }

    /**
     * just close
     */
    function __destruct() {
        $this->mysqli->close();
    }

}