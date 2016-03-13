<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report run
* @author Abhik Chakraborty
*/
include_once(BASE_PATH.'/modules/Report/CustomReports/ActivityByTypes/ActivityByTypes.class.php');
$activity = new ActivityByTypes();
$user_list = $activity->get_report_user_filter() ;
$date_filter_options = CommonUtils::get_date_filter_options();
$custom_date_filter_values = false ;
$date_range_display = 'style="display:block;margin-left:3px;"';
$date_filter_type = 15 ;
$selected_user = 0 ;
$crm_global_settings = new CRMGlobalSettings();
$currency = $crm_global_settings->get_setting_data_by_name('currency_setting');
$currency_data = json_decode($currency,true);
$report_date_start = '';
$report_date_end = '' ;
// if submit is clicked with some param then get the values and set to variables
if (isset($_GET['runtime']) && (int)$_GET['runtime'] > 0) {
	$custom_date_filter_values = ((int)$_GET['report_date_filter_type_runtime'] == 1 ? true : false ) ;
	$date_filter_type = (int)$_GET['report_date_filter_type_runtime'] ;
	$report_date_start = (isset($_GET['report_date_start_runtime']) ? $_GET['report_date_start_runtime']:'');
	$report_date_end = (isset($_GET['report_date_start_runtime']) ? $_GET['report_date_start_runtime']:'');
	$selected_user = (isset($_GET['report_user_filter_runtime']) ? (int)$_GET['report_user_filter_runtime']:0);
} 

// e and etg are table alias names used in the report query
$user_where = $activity->get_report_where($selected_user,'e','etg') ; 
$additional_where = $activity->get_date_filter_where('e','start_date',$date_filter_type,$report_date_start,$report_date_end) ;
$where = $user_where.$additional_where ;

// get the data for the report
$activity_types = $activity->get_activity_types() ;
$activities_by_type = $activity->get_activity_by_types($where);
$activity->get_detailed_activity_data('',$selected_user,$date_filter_type,$report_date_start,$report_date_end) ; 

// detailed data fields to be displayed
$detailed_data_fields = array("subject","event_type","start_date","end_date","event_status","related_to","assigned_to");
$do_crm_fields = new CRMFields();
$fields_info = $do_crm_fields->get_specific_fields_information($detailed_data_fields,2,true);

// breadcrumbs
$breadcrumb = $activity->get_breadcrumbs($_GET['path']) ;

if (false === $custom_date_filter_values) $date_range_display = 'style="display:none;margin-left:3px;"';
include_once('view/report_view.php');
?>