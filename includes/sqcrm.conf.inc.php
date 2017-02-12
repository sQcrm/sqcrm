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

define('BASE_PATH',str_replace('/includes','',dirname(__FILE__)));
$GLOBALS['BASE_PATH'] = BASE_PATH ;
//echo BASE_PATH;

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

define('SITE_URL',getenv('SITE_URL'));
$GLOBALS['SITE_URL'] = getenv('SITE_URL') ;

define('PORTAL_URL',getenv('PORTAL_URL'));

// days to keep live feed
define('DAYS_TO_KEEP_FEED',30);
$GLOBALS['DAYS_TO_KEEP_FEED'] = 30 ;

define('CRM_NAME',getenv('CRM_NAME'));
$GLOBALS['CRM_NAME'] = getenv('CRM_NAME') ;

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

// datatable plugin versions
define('DATATABLE_AUTOFILL_VERSION','2.1.2');
define('DATATABLE_BUTTONS_VERSION','1.2.1');
define('DATATABLE_COLREORDER_VERSION','1.3.2');
define('DATATABLE_VERSION','1.10.12');
define('DATATABLE_FIXEDCOLUMNS_VERSION','3.2.2');
define('DATATABLE_FIXEDHEADER_VERSION','3.1.2');
define('DATATABLE_KEYTABLE_VERSION','2.1.2');
define('DATATABLE_RESPONSIVE_VERSION','2.1.0');
define('DATATABLE_ROWRENDER_VERSION','1.1.2');
define('DATATABLE_SCOLLER_VERSION','1.4.2');
define('DATATABLE_SELECT_VERSION','1.2.0');
?>