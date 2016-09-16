<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 17:41
 */

/**
 * Class Service
 * has some helper funcs
 * realises minificaton and less translation
 *
 * @author ~/killer
 */
class Service {

    /**
     * makes explosion by given array of delimiters.
     * @author cool guy from php.net
     *
     * @param array $delimiters
     * @param $string
     * @return array
     */
    private static function multi_explode ($delimiters = [], $string) {

        if (!is_array($delimiters) && DEBUG)
            die('Delimiters should be array in '.__FILE__.' on '.__LINE__);
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    /**
     * Converts given url into array of meaning parts
     * @note: all '-' are replaced with letter T. Name controllers so
     *
     * @param $url
     * @return array
     */
    static function convert_url($url) {
        $url = str_replace('-', 'T', $url);
        return array_diff(self::multi_explode(['/', '?'], $url), [""]);
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
    static function error($e, $type = 'usual') {
        Controller::$status = 500;
        if (DEBUG || $type = 'fatal')
            die($e);
        else {
            error_log($e);
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
    private static function minify_js($name, $minify) {
        $path = ROOT.'assets/js_src/'.$name.'.js';
        $path_new = ROOT.'assets/js/'.$name.'.js';
        if (!file_exists($path)) {
            self::needs('js', $name, $minify, false);
            return;
        }
        if ($minify) {
            Autoloader::$components['phpcc']->add(ROOT."assets/js_src/$name.js")
                ->exec("../../assets/js/$name.js");
            Autoloader::$components['phpcc']->reset();
        } else {
            file_put_contents($path_new, file_get_contents($path));
        }
        unlink($path);
    }

    /**
     * Common details are same as in minify_js.
     * Minification in done by regulars
     *
     * @param string $name
     * @param bool $minify
     */
    private static function minify_css($name, $minify) {
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
    private static function compile_less($name) {
        if (file_exists(ROOT.'assets/css_src/'.$name.'.css'))
            throw new Exception('File with same name and css ext exists.');
        Autoloader::$components['less']->parse(
            file_get_contents(ROOT.'assets/css_src/'.$name.'.less')
        );
        file_put_contents(ROOT.'assets/css_src/'.$name.'.css',
            Autoloader::$components['less']->getCss()
        );
        unlink(ROOT.'assets/css_src/'.$name.'.less');
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
    static function needs($type, $name, $minify, $mode = DEBUG) {
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

