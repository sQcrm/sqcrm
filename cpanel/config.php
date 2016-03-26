<?php
$cfg_eventcontroler = 'eventcontroler.php';
define("RADRIA_EVENT_CONTROLER", $cfg_eventcontroler);
// diseable secure events, will show all the parameters of forms and links.
define("RADRIA_EVENT_SECURE", false);
$cfg_notrefererequestkey = "XX5X5XC7C5CFF7FC7C65FCD7FGGFD7FR22462" ;

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

// include the config stuff and autoloader
include_once('includes/cpanel.conf.inc.php');
include_once('includes/autoload.conf.inc.php');

// session path
$cfg_cpanel_path =  dirname($_SERVER['PHP_SELF']);
if(!preg_match("/\/$/",$cfg_cpanel_path)){
	$cfg_cpanel_path .= "/";
}

// set the session 
session_set_cookie_params(0, $cfg_web_path);
session_start() ;

//include the db config
include_once('../dbconn.php');
?>