<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report run
* @author Abhik Chakraborty
*/
include_once(BASE_PATH.'/modules/Report/CustomReports/RevenueByInvoicePayments/RevenueByInvoicePayments.class.php');
$do_revenue = new RevenueByInvoicePayments();
$date_filter_options = CommonUtils::get_date_filter_options();
$allowed_date_filter = array(3,6,15);
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

$title = '';
$series_label = array();

if ($date_filter_type == 15) {
	$title = _('Monthly Revenue Report');
	$series_label['current'] = _('Current Month');
	$series_label['previous'] = _('Previous Month');
	$current_range = CommonUtils::get_month_date_range();
	$previous_range = CommonUtils::get_month_date_range('previous');
	$interval = '7 days';
} elseif ($date_filter_type == 6) {
	$title = _('Quarterly Revenue Report');
	$series_label['current'] = _('Current Qtr');
	$series_label['previous'] = _('Previous Qtr');
	$current_range = CommonUtils::get_quarter_date_range();
	$previous_range = CommonUtils::get_quarter_date_range('previous');
	$interval = '15 days';
} elseif ($date_filter_type == 3) {
	$title = _('Yearly Revenue Report');
	$series_label['current'] = _('Current Year');
	$series_label['previous'] = _('Previous Year');
	$current_range = CommonUtils::get_year_date_range();
	$previous_range = CommonUtils::get_year_date_range('previous');
	$interval = '30 days';
}

$current_start_date = $current_range['start'];
$current_end_date = $current_range['end'];
$previous_start_date = $previous_range['start'];
$previous_end_date = $previous_range['end'];
$graph_height = 420;

$current_range_all_days = $do_revenue->get_all_days_in_range($current_range);
$previous_range_all_days = $do_revenue->get_all_days_in_range($previous_range);

$report_data = $do_revenue->revenue_by_invoice_payments($current_range,$previous_range,$selected_user,$date_filter_type);
$current_range_data = array();
$previous_range_data = array();

foreach($current_range_all_days as $date) {
	if (!array_key_exists($date,$report_data['current'])) {
		$current_range_data[$date] = 0.00;
	} else {
		$current_range_data[$date] = $report_data['current'][$date];
	}
}

foreach($previous_range_all_days as $date) {
	if (!array_key_exists($date,$report_data['previous'])) {
		$previous_range_data[$date] = 0.00;
	} else {
		$previous_range_data[$date] = $report_data['previous'][$date];
	}
}

$do_crm_fields = new CRMFields();
$fields_info = array(
	"idinvoice"=>array(
		"table"=>"invoice",
		"field_label"=>_("ID Invoice"),
		"field_type"=>1,
	),
	"invoice_status"=>array(
		"table"=>"invoice",
		"field_label"=>_("Invoice Status"),
		"field_type"=>5,
	),
	"date_added"=>array(
		"table"=>"paymentlog",
		"field_label"=>_("Payment Date"),
		"field_type"=>9,
	),
	"amount"=>array(
		"table"=>"paymentlog",
		"field_label"=>_("Amount"),
		"field_type"=>30,
	),
	"ref_num"=>array(
		"table"=>"paymentlog",
		"field_label"=>_("Ref Num"),
		"field_type"=>1,
	),
	"transaction_type"=>array(
		"table"=>"paymentlog",
		"field_label"=>_("Type"),
		"field_type"=>1,
	),
	"mode_name"=>array(
		"table"=>"payment_mode",
		"field_label"=>_("Type"),
		"field_type"=>1,
	),
);

// detailed date
$detail_data_current = new RevenueByInvoicePayments();
$detail_data_previous = new RevenueByInvoicePayments();
$detail_data_current->query($do_revenue->get_detailed_data($current_range));
$detail_data_previous->query($do_revenue->get_detailed_data($previous_range));

// breadcrumbs
$breadcrumb = $do_revenue->get_breadcrumbs($_GET['path']) ;

if (false === $custom_date_filter_values) $date_range_display = 'style="display:none;margin-left:3px;"';
include_once('view/report_view.php');
?>