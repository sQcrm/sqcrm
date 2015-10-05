<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* data history setting page
* @author Abhik Chakraborty
*/  

$do_datahistory_fld_opt = new DataHistoryFieldOption();

if (isset($_GET["cmid"]) && $_GET["cmid"] != '') {
	$dh_module = (int)$_GET["cmid"] ;
} else {
	$dh_module = 2 ; 
}

$datahistory_modules = $do_datahistory_fld_opt->get_modules_for_datahistory();
$datahistory_fields = $do_datahistory_fld_opt->get_datahistory_field_options($dh_module);

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/datahistory_fields_entry_view.php');
} else {
	require_once('view/datahistory_fields_view.php');
}
?>