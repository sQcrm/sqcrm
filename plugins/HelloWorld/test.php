<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
$idmodule = (int)$_GET["idmodule"] ;
$sqcrm_record_id = (int)$_GET["sqrecord"] ;
$plugin_position = (int)$_GET["plugin_position"] ;
$do_module = new Module();
$do_module->getId($idmodule);
$module_name = $do_module->name ;
$do_module_object = new $module_name();
include_once('view/plugin_view.php');
?>