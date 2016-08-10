<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 06/08/16
 * Time: 18:12
 */

class Database {

    /**
     * @var - postgres object is stored
     */
    public $postgres;


    private function connect() {
        $this->postgres = pg_connect('host=localhost user='.POSTGRES_USER.' password='.POSTGRES_PASS.' dbname='.POSTGRES_DB);
    }

    /**
     * @param $query
     * @param array $params
     * @return resource
     */
    public function query_params($query, $params = []) {
        return pg_query_params($this->postgres, $query, $params);
    }

    /**
     * @param $string
     * @return resource
     */
    public function query($string) {
        return pg_query($this->postgres, $string);
    }

    public function assoc($resource) {
        return pg_fetch_assoc($resource);
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
        pg_set_client_encoding($this->postgres, "UTF-8");
    }

    /**
     * just close
     */
    function __destruct() {
        pg_close($this->postgres);
    }

}