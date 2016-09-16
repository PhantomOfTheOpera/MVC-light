<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 15/09/16
 * Time: 17:41
 */


class tools {

    static function multi_explode ($delimiters,$string) {

        if (!is_array($delimiters) && DEBUG)
            die('Delimiters should be array in '.__FILE__.' on '.__LINE__);
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    static function convert_url($url) {

        $url = str_replace('-', 'T', $url);

        return array_diff(self::multi_explode(['/', '?'], $url), [""]);

    }

    static function error($e) {
        Controller::$status = 500;
        if (DEBUG)
            die($e);
        else {
            error_log($e);
        }
    }
}