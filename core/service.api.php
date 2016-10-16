<?php

/**
 * Created by PhpStorm.
 * User: artem
 * Date: 14.10.16
 * Time: 21:15
 */
require_once "abstract.api.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

define("GET", 1);
define("POST", 2);
define("PUT", 3);
define("DELETE", 4);
// TODO: search is cool, but i think in fact it is not so
define("SEARCH", 5);

define("DELIMETR", "|");

/**
 * Class Api.
 */

class Api
{
    /**
     * @var string Request Method
     */
    private static $method = '';

    /**
     * @var string
     */
    // TODO: not required mb
    private static $request = '';

    /**
     * @var array of argumets after exploding root, etc
     */
    private static $args = Array();

    /**
     * @var array of class names that was registred in index.php file
     */
    private static $registeredClasses = [];

    /**
     * @var array availible requet methods
     */
    // TODO: for what?
    private static $availableMethods = [
        "GET" => GET,
        "POST" => POST,
        "PUT" => PUT,
        "DELETE" => DELETE
    ];

    /**
     * @var string contains start time of api execution (for calculating executing time)
     */
    private static $startTime;

    /**
     * Registers Api class and includes it.
     * Class file path should be located at ../classes/<className>.php
     * @param $className - name of class to register
     * @throws Exception
     */

    public static function registerApiClass($className) {
        if(!file_exists("../application/api/" . $className . ".php"))
            throw new Exception("ERR: Class " . $className . " not registered. ", 500);
        array_push(self::$registeredClasses, $className);
        require_once "../application/api/" . $className . ".php";
    }

    /**
     * Api initialization.
     * Checks method, classname, processes request and makes response.
     */

    public static function init()
    {
        // TODO: rewrite it to some another functions
        self::checkMethod();
        self::$request = $_GET['url'];

        //TODO: explode path to api (set params above, etc)
        self::$args = explode('/', rtrim(self::$request, '/'));
        array_shift(self::$args);

        if(!isset(self::$args[0]))
            self::$args[0] = "";

        $className = self::$args[0];
        self::checkClass($className);
        array_shift(self::$args);

        if(!isset(self::$args[0]))
            self::$args[0] = "";

        if(self::$method == GET && stristr(self::$args[0], "search")) {
            self::$method = SEARCH;
            array_shift(self::$args);
        }

        $api = new $className();

        self::$startTime = microtime(true);
        $api->execute(self::$method, self::$args);
    }

    /**
     * Checks if class is registred
     * @param $className classname to check
     * @throws Exception if not registred
     */

    private static function checkClass($className)
    {
        if(!in_array($className, self::$registeredClasses, TRUE))
            throw new Exception(self::requestMessage(404), 404);
    }

    /**
     * Checks if method is availibled
     * @throws Exception
     */
    private static function checkMethod()
    {
        if(array_key_exists($_SERVER['REQUEST_METHOD'], self::$availableMethods))
            self::$method = self::$availableMethods[$_SERVER['REQUEST_METHOD']];
        else
            throw new Exception(self::requestMessage(405), 405);
    }

    /**
     * Makes response, and set headers.
     * @param $data - body of response
     * @param $status - response code
     * @return string - json encoded data
     */

    public static function response($data, $status)
    {
        // TODO: implement function makeResponse(). Use this function after Api::init();
        header("HTTP/1.1 " . $status . " " . self::requestStatus($status));
        header("Api-Execution-Time: " . sprintf("%.7f", (microtime(true) - self::$startTime)));
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param $code - http status code
     * @return mixed - status name
     */
    private static function requestStatus($code) {
        $status = array(
            200 => 'OK',
            400 => 'Bad Request',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    /**
     * @param $code - http status code
     * @return mixed - status message
     * (Not the same as previous function, because status message might not be the same as status name )
     */
    public static function requestMessage($code){
        $message = array(
            200 => 'Ok.',
            400 => 'Bad request',
            404 => 'Page not found',
            405 => 'Method not allowed',
            500 => 'Internal server error',
        );
        return ($message[$code]) ? $message[$code] : $message[500];
    }
}