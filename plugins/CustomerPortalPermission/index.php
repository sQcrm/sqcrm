<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
$idmodule = (int)$_GET["idmodule"] ;
$sqcrm_record_id = (int)$_GET["sqrecord"] ;
$do_customerportal_permission = new CustomerPortalPermission() ;
$users = $do_customerportal_permission->get_cpanel_users($sqcrm_record_id) ;
$cpanel_modules = $do_customerportal_permission->get_cpanel_modules($sqcrm_record_id) ;
include_once('view/plugin_view.php');
?>