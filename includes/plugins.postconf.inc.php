<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* load the active plugins and then include the objects
*/
$do_crm_plugins = new CRMPluginBase() ;
$do_crm_plugins->load_active_plugins() ;
$active_plugins = $do_crm_plugins->get_active_plugins();
if (is_array($active_plugins) && count($active_plugins) >0) {
	foreach ($active_plugins as $key=>$plugins) {
		include_once($cfg_project_directory.'plugins/'.$plugins["name"].'/'.$plugins["name"].'.class.php');
	}
}

?>