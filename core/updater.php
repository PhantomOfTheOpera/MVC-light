<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 12/08/16
 * Time: 11:37
 */

class Updater {

    const CSS_SRC_PATH = 'assets/css_src';
    const CSS_PATH = 'assets/css';

    const JS_SRC_PATH = 'assets/js_src';
    const JS_PATH = 'assets/js';

    function compileLess() {
        require ROOT.'lib/Less/lessc.inc.php';
        $less = array_diff(scandir(ROOT . self::CSS_SRC_PATH, 1), array('.', '..'));
        if (empty($less))
            return;
        $real_less = [];
        // real css could be in folder, separate them from less
        foreach ($less as $file_name) {
            $file_ext = explode('.', $file_name);
            if ($file_ext[1] == 'less')
                $real_less[] = $file_name;
        }
        if (empty($real_less))
            return;
        // done
        foreach ($real_less as $less) {
            $out_name = explode('.', $less);
            $out_name = $out_name[0].'.css';
            $out_path = ROOT.self::CSS_SRC_PATH.'/'.$out_name;
            $lc = new Less_Parser();
            try {
                $lc->parse(file_get_contents(ROOT.self::CSS_SRC_PATH.'/'.$less));
                $style = $lc->getCss();
            } catch (exception $ex) {
                $error = "LESSC FEHLER:".$ex->getMessage();
                die($error);
                exit;
            }
            unlink(ROOT.self::CSS_SRC_PATH.'/'.$less);
            file_put_contents($out_path, $style);
        }
    }

    function minifyJS() {
        require ROOT . 'lib/MinifyJS/class.JavaScriptPacker.php';
        $js = array_diff(scandir(ROOT.self::JS_SRC_PATH, 1), array('.', '..'));
        if (empty($js))
            return;
        foreach ($js as $file_name) {
            $js_full_path = ROOT.self::JS_SRC_PATH.'/'.$file_name;
            $script = file_get_contents($js_full_path);
            unlink($js_full_path);
            $packer = new JavaScriptPacker($script, 'Normal', true, false);
            $packed = $packer->pack();
            file_put_contents(ROOT.self::JS_PATH.'/'.$file_name, $packed);
        }
    }

    function minifyCSS() {
        $css = array_diff(scandir(ROOT.self::CSS_SRC_PATH, 1), array('.', '..'));
        if (empty($css))
            return;
        foreach ($css as $file_name) {
            $css_full_path = ROOT.self::CSS_SRC_PATH.'/'.$file_name;
            $string = file_get_contents($css_full_path);
            unlink($css_full_path);
            $string = preg_replace('!/\*.*?\*/!s','', $string);
            $string = preg_replace('/\n\s*\n/',"\n", $string);
            $string = preg_replace('/[\n\r \t]/',' ', $string);
            $string = preg_replace('/ +/',' ', $string);
            $string = preg_replace('/ ?([,:;{}]) ?/','$1',$string);
            $string = preg_replace('/;}/','}',$string);
            file_put_contents(ROOT.self::CSS_PATH.'/'.$file_name, $string);
        }
    }

    function __construct() {
        $this->compileLess();
        $this->minifyJS();
        $this->minifyCSS();
    }

}
$updater = new Updater();