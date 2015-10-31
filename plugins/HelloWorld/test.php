<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
$idmodule = (int)$_GET["idmodule"] ;
$sqcrm_record_id = (int)$_GET["sqrecord"] ;
$do_module = new Module();
$do_module->getId($idmodule);
$module_name = $do_module->name ;
include_once('view/plugin_view.php');
?>