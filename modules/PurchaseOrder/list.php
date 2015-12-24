<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* List view data 
* @author Abhik Chakraborty
*/  
$custom_view_allowed = true ;
if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/listview_entry.php');
} else { 
	$do_custom_view = new CustomView();
	if (true === $do_custom_view->has_custom_view($module_id)) {
		$custom_view_data = $do_custom_view->get_custom_views($module_id);
		$default_custom_view = $do_custom_view->get_default_custom_view($module_id);
	} else {
		$custom_view_allowed = false ;
	}
	require_once('view/listview.php');
}
?>