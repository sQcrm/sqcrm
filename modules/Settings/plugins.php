<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Plugin settings page
* @author Abhik Chakraborty
*/  
$do_plugin_settings = new PluginSettings();
$plugins = $do_plugin_settings->get_available_plugins();
$activated_plugins = $do_plugin_settings->get_activated_plugins();
$activated_plugin_names = array();
$detail_view_plugins = array() ;
$action_view_plugins = array() ;
if (is_array($activated_plugins) && count($activated_plugins) > 0) {
	foreach ($activated_plugins as $key=>$val) {
		$activated_plugin_names[$key] = $val["name"] ;
		if ((int)$val["action_priority"] > 0) {
			$action_view_plugins[$val["action_priority"]] = array("id"=>$key,"name"=>$val["name"]) ;
		} 
		
		if ((int)$val["display_priority"] > 0) {
			$detail_view_plugins[$val["display_priority"]] = array("id"=>$key,"name"=>$val["name"]) ;
		}
	}
}

if (count($action_view_plugins) > 0) ksort($action_view_plugins);
if (count($detail_view_plugins) > 0) ksort($detail_view_plugins);

require_once('view/plugin_settings_view.php');
?>