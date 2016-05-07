<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report run
* @author Abhik Chakraborty
*/
include_once(BASE_PATH.'/modules/Report/CustomReports/ProspectLostToCompetitor/ProspectLostToCompetitor.class.php');
$prospect_lostto_competitor = new ProspectLostToCompetitor();
$user_list = $prospect_lostto_competitor->get_report_user_filter() ;
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

// p and ptg are table alias names used in the report query
$user_where = $prospect_lostto_competitor->get_report_where($selected_user,'p','ptg') ; 
$additional_where = $prospect_lostto_competitor->get_date_filter_where('p','expected_closing_date',$date_filter_type,$report_date_start,$report_date_end) ;
$where = $user_where.$additional_where ;

// get the data for the report
$prospect_lostto_competitor_by_name = $prospect_lostto_competitor->get_prospect_lost_to_competitor($where);
$prospect_lostto_competitor->get_detailed_prospect_lostto_competitor_data('',$selected_user,$date_filter_type,$report_date_start,$report_date_end) ;

// detailed data fields to be displayed
$detailed_data_fields = array('potential_name','potential_type','sales_stage','expected_closing_date','competitor_name','assigned_to','amount') ;
$do_crm_fields = new CRMFields();
$fields_info = $do_crm_fields->get_specific_fields_information($detailed_data_fields,5,true);

// breadcrumbs
$breadcrumb = $prospect_lostto_competitor->get_breadcrumbs($_GET['path']) ;

if (false === $custom_date_filter_values) $date_range_display = 'style="display:none;margin-left:3px;"';
include_once('view/report_view.php');
?>