<?php

/**
 * Created by PhpStorm.
 * User: artem
 * Date: 14.10.16
 * Time: 21:13
 */

namespace MVC_light;

define("GET", 1);
define("POST", 2);
define("PUT", 3);
define("DELETE", 4);
define("SEARCH", 5);
define("DELIMETR", "|");


/**
 * Class ApiAbstract - abstract class describes each of Api Classes.
 * All api classes must extend ApiAbstract
 */
abstract class ApiAbstract
{
    /**
     * Execute methods of class depending on request parameters.
     * Also filters all of queries.
     * @param $method
     * @param $params
     */
    public function execute($method, $params){
        switch($method)
        {
            case GET:
                $query = $params[0];
                $this->select(intval($query));
                break;
            case POST:
                $object = json_decode(file_get_contents('php://input'));
                \MVC_light\Service::filterArr($object);
                $this->insert($object);
                break;
            case PUT:
                $object = json_decode(file_get_contents('php://input'));
                \MVC_light\Service::filterArr($object);
                $this->update($object);
                break;
            case DELETE:
                $query = $params[0];
                $this->delete(intval($query));
                break;
            case SEARCH:
                if(!isset($params[0]))
                    $query = "";
                else
                    $query = $params[0];
                $params = explode(DELIMETR, $query);
                \MVC_light\Service::filterArr($params);
                $this->search($params);
        }
    }

    abstract function select($id);
    abstract function update($object);
    abstract function delete($id);
    abstract function insert($object);
    abstract function search($params);

}
