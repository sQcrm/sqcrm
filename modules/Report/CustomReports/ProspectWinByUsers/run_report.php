<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report run
* @author Abhik Chakraborty
*/
include_once(BASE_PATH.'/modules/Report/CustomReports/ProspectWinByUsers/ProspectWinByUsers.class.php');
$prospect_win = new ProspectWinByUsers();
$user_list = $prospect_win->get_report_user_filter() ;
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
$user_where = $prospect_win->get_report_where($selected_user,'p','ptg') ; 
$additional_where = $prospect_win->get_date_filter_where('p','expected_closing_date',$date_filter_type,$report_date_start,$report_date_end) ;
$where = $user_where.$additional_where ;

// get the data for the report
$prospect_win_by_amount = $prospect_win->get_propect_win($where);
$users_and_groups = $prospect_win->get_users_and_groups($selected_user, false, 'assigned_to');
$group_users = array() ;
if (array_key_exists('data',$prospect_win_by_amount) && count($prospect_win_by_amount['data']) > 0) {
	if (count($users_and_groups) > 0) {
		if (array_key_exists('users',$users_and_groups) && count($users_and_groups['users']) > 0) {
			foreach ($users_and_groups['users'] as $key=>$val) {
				if (!array_key_exists($key,$prospect_win_by_amount['data'])) {
					$prospect_win_by_amount['data'][$key] = 0.00 ;
				}
			}
		}
		if (array_key_exists('groups',$users_and_groups) && count($users_and_groups['groups']) > 0) {
			$do_group_users = new GroupUserRelation();
			foreach ($users_and_groups['groups'] as $key=>$val) {
				$do_group_users->get_users_related_to_group($val["idgroup"]);
				if ($do_group_users->getNumRows() > 0) {
					while ($do_group_users->next()) {
						$group_users[$key][] = array(
							"user_name"=>$do_group_users->user_name,
							"firstname"=>$do_group_users->firstname,
							"lastname"=>$do_group_users->lastname
							);
					}
				}
				if (!array_key_exists($key,$prospect_win_by_amount['data'])) {
					$prospect_win_by_amount['data'][$key] = 0.00 ;
				}
			}
		}
	}
}

$graph_height = 350;
$height_offset = 5 ;
$record_count = count($prospect_win_by_amount['data']) ;
if ($record_count > $height_offset) {
	$diff = $record_count - $height_offset ;
	$graph_height = $graph_height + $diff*70 ;
} elseif ($record_count < $height_offset) {
	$graph_height = 70*$record_count ;
}

$prospect_win->get_detailed_win_data('',$selected_user,$date_filter_type,$report_date_start,$report_date_end) ;

// detailed data fields to be displayed
$detailed_data_fields = array('potential_name','potential_type','sales_stage','expected_closing_date','assigned_to','amount') ;
$do_crm_fields = new CRMFields();
$fields_info = $do_crm_fields->get_specific_fields_information($detailed_data_fields,5,true);

// breadcrumbs
$breadcrumb = $prospect_win->get_breadcrumbs($_GET['path']) ;

if (false === $custom_date_filter_values) $date_range_display = 'style="display:none;margin-left:3px;"';
include_once('view/report_view.php');
?>