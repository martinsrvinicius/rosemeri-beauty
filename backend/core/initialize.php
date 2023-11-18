<?php
/**
 * Initialize settings in the project
 */

 defined("DS") ? null : define('DS',DIRECTORY_SEPARATOR);
 defined("SITE_ROOT") ? null : define("SITE_ROOT", DS. 'phprest');
//xamp/www/phprest/includes
 defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT.DS.'includes');
 defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT.DS.'core');

 //load the config file first
// require_once(INC_PATH.DS."config.php");
//require_once('../includes/config.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/includes/config.php');
 
//require_once('/home/vol19_2/infinityfree.com/if0_35370468/htdocs/includes/config.php');
 //core classes
//require_once("../core/post.php");
//require_once("/home/u239364416/domains/vinim.eu/public_html/rosemeri-beauty/core/post.php");
require_once($_SERVER["DOCUMENT_ROOT"].'/core/user.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/core/post.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/core/calendar.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/core/client.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/core/service.php');

?>