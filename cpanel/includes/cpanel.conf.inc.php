<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* CRM constatants and application configurations
* @author Abhik Chakraborty
*/
// Constants
$theme_path = '/themes/bootstrap/';

$theme_image_path = '/themes/bootstrap/img/';

$standard_permissions = array(1,2,3);

define('TTL_SHORT', 7200); 
define('TTL', 86400);
define('TTL_LONG', 864000);

define('CPANEL_PATH',str_replace('/includes','',dirname(__FILE__)));
$GLOBALS['CPANEL_PATH'] = CPANEL_PATH;

$cpanel_path = (preg_match("/\/$/", CPANEL_PATH) ? CPANEL_PATH : CPANEL_PATH.'/');

define('BASE_PATH',$cpanel_path.'../');
$GLOBALS['BASE_PATH'] = BASE_PATH ;


define('AVATAR_PATH',BASE_PATH.'/cache/thumb');
$GLOBALS['AVATAR_PATH'] = AVATAR_PATH ;

define('AVATAR_DISPLAY_PATH','/cache/thumb');
$GLOBALS['AVATAR_DISPLAY_PATH'] = AVATAR_DISPLAY_PATH ;

define('FILE_UPLOAD_PATH',BASE_PATH.'/cache/uploads');
$GLOBALS['FILE_UPLOAD_PATH'] = FILE_UPLOAD_PATH ;

define('FILE_UPLOAD_DISPLAY_PATH','/cache/uploads');
$GLOBALS['FILE_UPLOAD_DISPLAY_PATH'] = FILE_UPLOAD_DISPLAY_PATH ;

define('CSV_IMPORT_PATH',BASE_PATH.'/cache/imports');
$GLOBALS['CSV_IMPORT_PATH'] = CSV_IMPORT_PATH ;

define('OUTBOUND_PATH',BASE_PATH.'/cache/outbound');
$GLOBALS['OUTBOUND_PATH'] = OUTBOUND_PATH ;


/*define('NOSQL_DB',false);
$GLOBALS['NOSQL_DB'] = false ;
*/

define('SITE_URL','http://sqcrm.localhost');
$GLOBALS['SITE_URL'] = 'http://sqcrm.localhost' ;

// days to keep live feed
define('DAYS_TO_KEEP_FEED',30);
$GLOBALS['DAYS_TO_KEEP_FEED'] = 30 ;

define('CRM_NAME','sQcrm.com');
$GLOBALS['CRM_NAME'] = 'sQcrm.com' ;

// Max recently viewed
define('RECENT_VIEW_TAB',false);
$GLOBALS['RECENT_VIEW_TAB'] = false ;
define('MAX_RECENT_VIEW',10);
$GLOBALS['MAX_RECENT_VIEW'] = 10 ;

define('THIRD_PARTY_LIB_PATH','sqlibs');
$GLOBALS['THIRD_PARTY_LIB_PATH'] = THIRD_PARTY_LIB_PATH ;

//number of items per page default in list view - available options are 10,25,50,100
define('LIST_VIEW_PAGE_LENGTH',50);
$GLOBALS['LIST_VIEW_PAGE_LENGTH'] = LIST_VIEW_PAGE_LENGTH ;

//  Log errors in the pas_errro.log file:
define("RADRIA_LOG_ERROR", false);
//  Display errors in generated web pages:
define("RADRIA_DISPLAY_ERROR", false);
//  Log general message/debug log in the pas_run.log:
define("RADRIA_LOG_RUNLOG", false);
//  Display message/debug log in generated web pages:
define("RADRIA_DISPLAY_RUNLOG", false);

define("CPANEL_EVENTCONTROLER_PATH","../../");
?>