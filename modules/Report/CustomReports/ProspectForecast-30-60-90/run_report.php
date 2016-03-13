<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report run
* @author Abhik Chakraborty
*/
include_once(BASE_PATH.'/modules/Report/CustomReports/ProspectForecast-30-60-90/ProspectForecast.class.php');
$prospect_forecast = new ProspectForecast();
$user_list = $prospect_forecast->get_report_user_filter() ;
$selected_user = 0 ;
$crm_global_settings = new CRMGlobalSettings();
$currency = $crm_global_settings->get_setting_data_by_name('currency_setting');
$currency_data = json_decode($currency,true);
// if submit is clicked with some param then get the values and set to variables
if (isset($_GET['runtime']) && (int)$_GET['runtime'] > 0) {
	$selected_user = (isset($_GET['report_user_filter_runtime']) ? (int)$_GET['report_user_filter_runtime']:0);
} 

// p and ptg are table alias names used in the report query
$user_where = $prospect_forecast->get_report_where($selected_user,'p','ptg') ; 

// get the data for the report
$prospect_forecast_data = $prospect_forecast->get_prospect_forecast($where,$selected_user);


$detailed_data_fields = array('potential_name','potential_type','sales_stage','expected_closing_date','assigned_to','amount') ;
$do_crm_fields = new CRMFields();
$fields_info = $do_crm_fields->get_specific_fields_information($detailed_data_fields,5,true);

$prospect_forecast->get_detailed_forecast_data('',$selected_user);

// breadcrumbs
$breadcrumb = $prospect_forecast->get_breadcrumbs($_GET['path']) ;

if (false === $custom_date_filter_values) $date_range_display = 'style="display:none;margin-left:3px;"';
include_once('view/report_view.php');
?>