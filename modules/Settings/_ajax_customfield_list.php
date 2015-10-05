<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field page
* @author Abhik Chakraborty
*/  

if (isset($_GET["cmid"]) && $_GET["cmid"] != '') {
	$cf_module = (int)$_GET["cmid"] ;
} else {
	$cf_module = 3 ; 
}
if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/customfield_entry_view.php');
}
?>