<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom report
* @author Abhik Chakraborty
*/
include_once('modules/Report/CustomReport.class.php') ;

$do_custom_report = new CustomReport() ;
$reports = $do_custom_report->get_custom_reports() ;
if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/customreport_view_entry.php');
} elseif (isset($_GET['path']) && isset($_GET['resource'])) {
	$load_report = false ;
	$custom_report_path = BASE_PATH.'/modules/Report/CustomReports/' ;
	if (file_exists($custom_report_path.$_GET['path'].'/'.$_GET['resource'].'.php')) {
		if (file_exists($custom_report_path.$_GET['path'].'/config.json')) {
			$config = file_get_contents($custom_report_path.$_GET['path'].'/config.json') ;
			$config_decoded = json_decode($config);
			if ($config_decoded->enabled == 1) {
				$load_report = true ;
			}
		}
	}
	if (true === $load_report) {
		include_once('modules/Report/CustomReports/'.$_GET['path'].'/'.$_GET['resource'].'.php') ;
	} else {
		echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
		echo '<strong>';
		echo _('Report Does Not Exist ! ');
		echo '</strong>';
		echo _('The report you are trying to access is either disabled or not exists !');
		echo '</div>';
	}	
} else {
	require_once('view/customreport_view.php');
}