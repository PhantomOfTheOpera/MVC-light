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
class Service extends Ab_Service {

     static function multi_explode (array $delimiters = [], string $string) : array {

        if (!is_array($delimiters) && DEBUG)
            die('Delimiters should be array in '.__FILE__.' on '.__LINE__);
        $ready = str_replace($delimiters, $delimiters[0], $string);
        return explode($delimiters[0], $ready);
    }

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

    public static function error(string $e, string $type = 'usual') {
        Controller::$status = 500;
        if (DEBUG)
            die("<pre>$e</pre>");
        if ($type == 'fatal' && !DEBUG) {
            $_SESSION['error'] = $e;
            header('Location: /500');
            exit;
        } else {
            error_log($e);
        }
    }

    public static function filter(string $value) : string {
        return addslashes(htmlspecialchars($value));
    }

    static function minify_js(string $name, bool $minify) {
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
    }

    static function minify_css(string $name, bool $minify) {
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

     static function compile_less(string $name) : string {
        if (!file_exists(ROOT.'assets/css_src/'.$name.'.less')) {
            return $name;
        }
        if (file_exists(ROOT.'assets/css_src/'.$name.'.css'))
            throw new Exception('File with same name and css ext exists.');
        Autoloader::$components['less']->compileFile(
            ROOT.'assets/css_src/'.$name.'.less', ROOT.'assets/css_src/'.$name.'.css'
        );
        unlink(ROOT.'assets/css_src/'.$name.'.less');
        return $name;
    }

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

    static function register_app(string $name) : Module {
        try {
            $path = ROOT.'vendor/apps/'.$name.'/app.index.php';
            if (!file_exists($path))
                throw new \Exception("Couldn't start app by path $path");
            require_once $path;
            $app = new \MVC_light\Module();
            return $app;
        } catch (\Exception $e) {
            Service::error($e, 'fatal');
        }
    }
}

