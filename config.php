<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* MySQL Main configuration page
* Include that file in all the files that will uses PAS objects.
*/

//if (!isset($GLOBALS['cfg_full_path'])) { $GLOBALS['cfg_full_path'] = ''; }
//set_include_path(get_include_path() . PATH_SEPARATOR . $GLOBALS['cfg_full_path']);
//$cfg_project_directory = $GLOBALS['cfg_full_path'];
if (isset($GLOBALS['cfg_full_path'])) {
	set_include_path(get_include_path() . PATH_SEPARATOR . $GLOBALS['cfg_full_path']);
	$cfg_project_directory = $GLOBALS['cfg_full_path'];
} else {
	$cfg_project_directory = dirname(__FILE__).'/';
}
$cfg_local_db = 'mysql';
$GLOBALS['cfg_local_db'] = 'mysql';
$cfg_eventcontroler = 'eventcontroler.php';
define("RADRIA_EVENT_CONTROLER", $cfg_eventcontroler);

$cfg_lang = 'us';
// diseable secure events, will show all the parameters of forms and links.
define("RADRIA_EVENT_SECURE", false);
define("RADRIA_LOCAL_DB", $cfg_local_db);

//  Change the default events parameters times out
//  $cfg_event_param_garbage_time_out = 3600;
//  $cfg_event_param_garbage_interval = 3400;

// Change this key. This is the key that authorized event execution coming from not local domain.
$cfg_notrefererequestkey = "XX5X5XC7C5CFF7FC7C65FCD7FGGFD7FR22462" ;

//Radria anonymous usage statistics:
$cfg_radria_stat_usage = true;

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL);
//ini_set('display_errors', '1');


if (file_exists($GLOBALS['cfg_full_path'].'includes/extraconfig.inc.php')) {
	include_once($GLOBALS['cfg_full_path'].'includes/extraconfig.inc.php');
}

$cfg_web_path =  dirname($_SERVER['PHP_SELF']);
if(!preg_match("/\/$/",$cfg_web_path)){
	$cfg_web_path .= "/";
}
session_set_cookie_params(0, $cfg_web_path);
session_start() ;

//DB setup using Doctrine
use Doctrine\Common\ClassLoader;
require dirname(__FILE__).'/Doctrine/Doctrine/Common/ClassLoader.php';
$classLoader = new ClassLoader('Doctrine', dirname(__FILE__).'/Doctrine');
$classLoader->register();
$config = new \Doctrine\DBAL\Configuration();

$connectionParams = array(
    'dbname' => 'sqcrm',
    'user' => 'sqcrmuser',
    'password' => '5ql6rm',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);

$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
$GLOBALS['conn'] = $conn ;
	
include(dirname(__FILE__)."/includes/globalvar.inc.php") ;

if (file_exists(dirname(__FILE__)."/includes/extraconfig_postdb.inc.php")) {
	include_once(dirname(__FILE__)."/includes/extraconfig_postdb.inc.php") ;
};
?>