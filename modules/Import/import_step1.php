<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* import_step1.php, checks the module for which import to be done and then includes the view
* @author Abhik Chakraborty
*/

$do_import = new Import();
$do_import->sessionPersistent("do_import", "logout.php", TTL);

$allow_import = true ;

if (!isset($_REQUEST["return_module"])) {
	$allow_import = false ;
	$msg = _('No module specified for import !');
}

$returned_module = (int)$_REQUEST["return_module"] ;
if (!in_array($returned_module,$_SESSION["do_import"]->get_allowed_modules_for_import())) {
	$allow_import = false ;
	$msg = _('Import is not allowed for the specified module !');
}

if ($allow_import === true) {
	$_SESSION["do_import"]->set_import_module_id($returned_module);
}
require_once('view/import_step1_view.php');
?>