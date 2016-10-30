<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 19/09/16
 * Time: 22:12
 */

require_once __DIR__.'/../core/settings.php';


class Page {

    // private static $type = 'page';
     private static $type = 'ajax';

    private static $name = 'test';

    private static $database = false;

    private static $template = 'mvcmanager';

    public static function create() {
        $function = (self::$type == 'page') ? 'create_page' : 'create_ajax';
        self::$function();
    }

    static function create_page() {
        $controller_path = ROOT.'application/controller/controller.'.self::$name.'.php';
        $model_path = ROOT.'application/models/model_'.self::$name.'.php';
        $view_path = ROOT.'application/views/'.self::$template.'.twig';
        if (file_exists($controller_path) || file_exists($model_path) || file_exists($view_path))
            die('Such a files exist');
        $name = self::$name;
        $template = self::$template;
        ob_start();
        include __DIR__.'/page_template/controller.php';
        $controller = ob_get_contents();
        ob_end_clean();
        ob_start();
        include __DIR__.'/page_template/model.php';
        $model = ob_get_contents();
        ob_end_clean();
        file_put_contents($controller_path, $controller);
        file_put_contents($model_path, $model);
        file_put_contents($view_path, '');
    }

    static function create_ajax() {
        $name = self::$name;
        $ajax_path = ROOT.'application/controller/ajax/ajax.'.self::$name.'.php';
        ob_start();
        include __DIR__.'/page_template/ajax.php';
        $ajax = ob_get_contents();
        ob_end_clean();
        file_put_contents($ajax_path, $ajax);
    }
}

Page::create();


