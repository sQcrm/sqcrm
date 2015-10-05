<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* CustomView edit 
* @author Abhik Chakraborty
*/  
$do_edit = true ;
if (isset($_REQUEST["sqrecord"]) && (int)$_REQUEST["sqrecord"] > 0) {
	$module_obj = new CustomView();
	$module_obj->getId($_REQUEST["sqrecord"]) ;
	if ($module_obj->getNumRows() > 0) {
		if ($module_obj->iduser != $_SESSION["do_user"]->iduser) {
			$do_edit = false  ;
		} 
		if ($module_obj->is_editable == 0) {
			$do_edit = false  ;
		}
	} else {
		$do_edit = false ;
	}
} else {
	$do_edit = false ;
}

if ($do_edit === false) {
	echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
	echo '<h4>';
	echo _('Access Denied ! ');
	echo '</h4>';
	echo _('You are not authorized to perform this operation.');
	echo '</div>';
} else {
	$do_crmfields = new CRMFields() ;
	$do_custom_view_fields = new CustomViewFields() ;
	$do_custom_view_filter = new CustomViewFilter() ;
	$cv_fields = $do_crmfields->get_fieldinfo_grouped_by_block($module_obj->idmodule,true) ;
	$date_filters = $do_crmfields->get_date_fields($module_obj->idmodule,true) ;
	$date_filter_options = ViewFilterUtils::get_date_filter_otions() ;
	$advanced_filter_options = ViewFilterUtils::get_advanced_filter_options() ;
	$saved_fields = $do_custom_view_fields->get_custom_view_fields($sqcrm_record_id) ;
	$saved_date_filter = $do_custom_view_filter->get_date_filter_information($sqcrm_record_id) ;
	$saved_advanced_filter = $do_custom_view_filter->get_saved_advanced_filter_information($sqcrm_record_id) ;
	require_once('view/customview_edit_view.php') ;
}

?>