<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Customview add 
* @author Abhik Chakraborty
*/ 
$do_crmfields = new CRMFields();
if (isset($_REQUEST["target_module_id"]) && (int)$_REQUEST["target_module_id"] > 0) {
	$target_module_id = (int)$_REQUEST["target_module_id"] ;
	$cv_fields = $do_crmfields->get_fieldinfo_grouped_by_block($target_module_id,true);
	$date_filters = $do_crmfields->get_date_fields($target_module_id,true);
	$date_filter_options = ViewFilterUtils::get_date_filter_otions();
	$advanced_filter_options = ViewFilterUtils::get_advanced_filter_options();
	require_once('view/customview_create_view.php');
}
?>