<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 20:38
 */

namespace MVC_light;

class Model extends Ab_Model {

    function __construct(MVC_Core $MVC_core) {
        $this->MVC_core = $MVC_core;
    }

    protected function get_common() : array {
        Service::needs('css', 'main', true);
        if (CSRF_SAFE)
            $this->common['token'] = Service::generate_token();
        $this->common['version'] = VERSION;
        $this->common['site_name'] = SITE_NAME;
        return $this->common;
    }

    protected function get_js_css() : array {
        return [
            'includes' => self::$dependencies
        ];
    }

    function get_data(string $next_method) : array {
        $specific_params = $this->$next_method();
        $common_params = $this->get_common();
        $includes = $this->get_js_css();
        return array_merge($common_params, $specific_params, $includes);
    }

}