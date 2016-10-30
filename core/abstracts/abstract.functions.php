<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 30/10/16
 * Time: 09:41
 */

namespace MVC_light;

abstract class Ab_Service {


    /**
     * makes explosion by given array of delimiters.
     * @author cool guy from php.net
     *
     * @param array $delimiters
     * @param $string
     * @return array
     */
    abstract static function multi_explode (array $delimiters = [], string $string) : array;

    /**
     * Converts given url into array of meaning parts
     * @note: all '-' are replaced with letter T. Name controllers so
     *
     * @param string $url
     * @return array
     */
    abstract public static function convert_url(string $url) : array;

    abstract public static function generate_token() : string;

    abstract public static function validate_token(string $token) : bool;

    /**
     * handles errors from whole framework. This is the only handler.
     * $e message from Exception. $type - two cases:
     *      1) usual - on debug die, if no - log
     *      2) fatal - die
     *
     * @param string $e
     * @param string $type
     */
    abstract public static function error(string $e, string $type = 'usual');

    /** Unescapes input string
     * @param $value
     * @return string
     */
    abstract static function filter(string $value) : string;

    /**
     * Minifies js by given name, Depending on minify flag compresses.
     * in any case, files are moved from js_src to js
     * if no file present in js_src directory we run self::needs with !DEBUG flag,
     *  to check if it's presented in production. Errors would be handled from there next.
     * Minification is done using php wrap upon google closure. Wrap is included
     * as component using standart Autoloader component api
     *
     * @param string $name
     * @param bool $minify
     */
    abstract static function minify_js(string $name, bool $minify);

    /**
     * Common details are same as in minify_js.
     * Minification in done by regulars
     *
     * @param string $name
     * @param bool $minify
     */
    abstract static function minify_css(string $name, bool $minify);

    /**
     * compiles less by given name. Less parser is included as component by
     * standart Autoloader components api
     * @note: names of css and less files mustn't be same.
     *
     * @param string $name
     * @return string $name
     * @throws Exception - if names exists in css file
     */
    abstract static function compile_less(string $name) : string;

    /**
     * Main interface for references declaration
     * if $name is array function is called recursively.
     * two cases of start:
     *      1) DEBUG ($mode is same as DEBUG, by default)
     * on DEBUG all files are minificated and moved (if needed) manually.
     *      2) non-DEBUG - just check that file exists. If no - initialize fatal error
     * after that files are added to Model::$dependencies for further template inclusion
     * Something else?
     *
     *
     * @param string $type
     * @param array|string $name
     * @param bool $minify
     */
    abstract static function needs(string $type, $name, bool $minify, bool $mode = DEBUG);


    abstract static function register_app(string $name) : Module;
}