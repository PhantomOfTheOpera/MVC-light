<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 10/09/16
 * Time: 19:02
 */

class Model_Main extends Model {

    function get_main() {

        return [
            'template' => 'main',
            'js_specific' => [
                [
                    'url' => 'main1.js',
                    'defer' => true
                ]
            ]
        ];

    }

}