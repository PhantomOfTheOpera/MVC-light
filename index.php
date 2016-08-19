<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 04/08/16
 * Time: 23:16
 *  */

require_once 'core/settings.php';

/* this class is active  */
require_once 'core/updater.php';

/* dependencies for core */
require_once 'lib/Twig/Autoloader.php';
require_once 'core/model.php';
require_once 'core/mysqli.php';
require_once 'core/controller.php';
require_once 'core/ajax.php';
require_once 'core/mailer.php';

/* next in Core::__construct() */
require_once 'core/core.php';
