<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field page
* @author Abhik Chakraborty
*/  
$do_custom_field = new CustomFields();

if (isset($_GET["cmid"]) && $_GET["cmid"] != '') {
	$cf_module = (int)$_GET["cmid"] ;
} else {
	$cf_module = 3 ; 
}

//$modules_info = $_SESSION["do_module"]->get_modules_with_full_info();
$module_with_customfield = $do_custom_field->get_customfield_module_info();
$lead_moule_mappings = $_SESSION["do_module"]->get_lead_mapping_modules();

$do_custom_field->get_custom_fields($cf_module);

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/customfield_entry_view.php');
} else {
	require_once('view/customfield_view.php');
}
?>