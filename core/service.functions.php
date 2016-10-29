<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 17:41
 */

namespace MVC_light;
use Exception;
/**
 * Class Service
 * has some helper funcs
 * realises minificaton and less translation
 *
 * @author ~/killer
 */
class Service {

    public static $time_debug_string = "\n\n<!--\n\n";

    public static $total_time;

    public static $js_compilation_time;

    public static $css_compilation_time;

    public static $less_compilation_time;

    /**
     * makes explosion by given array of delimiters.
     * @author cool guy from php.net
     *
     * @param array $delimiters
     * @param $string
     * @return array
     */
    private static function multi_explode (array $delimiters = [], string $string) : array {

        if (!is_array($delimiters) && DEBUG)
            die('Delimiters should be array in '.__FILE__.' on '.__LINE__);
        $ready = str_replace($delimiters, $delimiters[0], $string);
        return explode($delimiters[0], $ready);
    }

    /**
     * Converts given url into array of meaning parts
     * @note: all '-' are replaced with letter T. Name controllers so
     *
     * @param string $url
     * @return array
     */
    public static function convert_url(string $url) : array {
        $url = str_replace('-', 'T', $url);
        return array_diff(self::multi_explode(['/', '?'], $url), [""]);
    }

    public static function generate_token() : string {
        if (!(array_key_exists('token_expire', $_SESSION)) ||
            !(array_key_exists('token', $_SESSION)) ||
            time() > $_SESSION['token_expire']) {
            $_SESSION['token'] = uniqid(random_int(0, PHP_INT_MAX), true);
            $_SESSION['token_expire'] = time() + 10 * 60;
        }
        return $_SESSION['token'];
    }

    public static function validate_token(string $token) : bool {
        if ($_SESSION['token'] == $token && $_SESSION['token_expire'] >= time())
            return true;
        self::generate_token();
        return false;
    }


    /**
     * handles errors from whole framework. This is the only handler.
     * $e message from Exception. $type - two cases:
     *      1) usual - on debug die, if no - log
     *      2) fatal - die
     *
     * @param string $e
     * @param string $type
     */
    public static function error(string $e, string $type = 'usual') {
        Controller::$status = 500;
        if (DEBUG || $type = 'fatal')
            die("<pre>$e</pre>");
        else {
            error_log($e);
        }
    }

    /** Unescapes input string
     * @param $value
     * @return string
     */
    public static function filter(string $value) : string
    {
        return addslashes(htmlspecialchars($value));
    }

    /** Filters all the fields in object or array recursively
     * @param $array
     */
    public static function filterArr(array &$array)
    {
        foreach ($array as &$value) {
            if(is_array($value) || is_object($value))
                self::filterArr($value);
            else
                $value = self::filter($value);
        }
    }

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
    private static function minify_js(string $name, bool $minify) {
        $t1 = microtime(true);
        $path = ROOT.'assets/js_src/'.$name.'.js';
        $path_new = ROOT.'assets/js/'.$name.'.js';
        if (!file_exists($path)) {
            self::needs('js', $name, $minify, false);
            return;
        }
        if ($minify) {
            $packer = new \JavaScriptPacker(file_get_contents($path), 'Normal', true, false);
            file_put_contents($path_new, $packer->pack());
        } else {
            file_put_contents($path_new, file_get_contents($path));
        }
        unlink($path);
        $t2 = microtime(true);
        self::$js_compilation_time += $t2 - $t1;
    }

    /**
     * Common details are same as in minify_js.
     * Minification in done by regulars
     *
     * @param string $name
     * @param bool $minify
     */
    private static function minify_css(string $name, bool $minify) {
        $t1 = microtime(true);
        $path = ROOT.'assets/css_src/'.$name.'.css';
        $path_new = ROOT.'assets/css/'.$name.'.css';
        if (!file_exists($path)) {
            self::needs('css', $name, $minify, false);
            return;
        }
        $css = file_get_contents($path);
        if ($minify) {
            $css = preg_replace('!/\*.*?\*/!s', '', $css);
            $css = preg_replace('/\n\s*\n/', "\n", $css);
            $css = preg_replace('/[\n\r \t]/', ' ', $css);
            $css = preg_replace('/ +/', ' ', $css);
            $css = preg_replace('/ ?([,:;{}]) ?/', '$1', $css);
            $css = preg_replace('/;}/', '}', $css);
        }
        file_put_contents($path_new, $css);
        unlink($path);
        $t2 = microtime(true);
        self::$css_compilation_time += $t2 - $t1;
    }

    /**
     * compiles less by given name. Less parser is included as component by
     * standart Autoloader components api
     * @note: names of css and less files mustn't be same.
     *
     * @param string $name
     * @return string $name
     * @throws Exception - if names exists in css file
     */
    private static function compile_less(string $name) : string {
        $t1 = microtime(true);
        if (!file_exists(ROOT.'assets/css_src/'.$name.'.less')) {
            return $name;
        }
        if (file_exists(ROOT.'assets/css_src/'.$name.'.css'))
            throw new Exception('File with same name and css ext exists.');
        Autoloader::$components['less']->compileFile(
            ROOT.'assets/css_src/'.$name.'.less', ROOT.'assets/css_src/'.$name.'.css'
        );
        unlink(ROOT.'assets/css_src/'.$name.'.less');
        $t2 = microtime(true);
        self::$less_compilation_time += $t2 - $t1;
        return $name;
    }

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
    static function needs(string $type, $name, bool $minify, bool $mode = DEBUG) {
        if (is_array($name)) {
            foreach ($name as $file)
                self::needs($type, $file, $minify);
        }
        else {
            try {
                if ($mode) {
                    switch ($type) {
                        case 'js':
                            self::minify_js($name, $minify);
                            break;
                        case 'css':
                            self::minify_css($name, $minify);
                            break;
                        case 'less':
                            self::minify_css(self::compile_less($name), $minify);
                            break;
                        default:
                            throw new Exception("$type is incorrect type for $name");
                    }
                } else {
                    $type = ($type == 'less') ? 'css' : $type;
                    if ($type != 'css' && $type != 'js')
                        throw new Exception("$type is incorrect type for $name");
                    if (!file_exists(ROOT . 'assets/' . $type . '/' . $name . '.' . $type))
                        throw new Exception("Needed file $name.$type not found in /assets/$type");
                }
            } catch (Exception $e) {
                self::error($e, 'fatal');
            }
            $type = ($type == 'less') ? 'css' : $type;
            Model::$dependencies[$name.$type] = [
                'path' => WEB_ROOT.'assets/'.$type.'/'.$name.'.'.$type,
                'type' => $type
            ];
        }
    }
}

