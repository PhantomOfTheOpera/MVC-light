<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 30/10/16
 * Time: 09:55
 */

namespace MVC_light;

if (!class_exists('Ab_Autoloader')) {


    abstract class Ab_Autoloader
    {
        /**
         * @var array of /core files.
         * Here are stored their names after scandir:
         *      settings.php and this file are not stored here
         * after that, all of them are included in foreach
         */
        protected $files = [];

        /**
         * @var array of component's folders
         * received after scandir
         * on components init we try to include api.component.php in ths folders
         */
        public static $components = [];

        protected $abstracts;

        /**
         * Autoloader constructor.
         *
         * fills files and components variables.
         * All errors must be treated as fatal and if smth was caught
         * script should be stopped and exited.
         * For more info look at MVC_error func
         */
        abstract function __construct();

        /**
         * includes core and components files.
         *
         * @throws Exception - cases when expected files are not presented
         */
        abstract function init();


        /**
         * @param string $address - plugin folder name
         * provide plugin connect api
         */
        abstract static function register(string $address);
    }

}