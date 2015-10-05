<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report module list file
* @author Abhik Chakraborty
*/
$do_report = new Report();
$do_report_folder = new ReportFolder();
$report_folder = $do_report_folder->get_report_folders();
$left = array();
$right = array();
$cnt = 1 ;
foreach ($report_folder as $key=>$val) {
	if ($cnt == 1 || $cnt%2 == 0 ) {
		$left[] = array(
			"idreport_folder"=>$val["idreport_folder"],
			"name"=>$val["name"],
			"description"=>$val["description"]
		);
	} else {
		$right[] = array(
			"idreport_folder"=>$val["idreport_folder"],
			"name"=>$val["name"],
			"description"=>$val["description"]
		);
	}
	$cnt++;
}
if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	$folder_id = $_REQUEST["folderid"];
	$folder_name = $_REQUEST["foldername"];
	$reports = $do_report->get_reports_by_folder($folder_id);
	require_once('view/reportlist_view_entry.php');
} else {
	require_once('view/reportlist_view.php');
}
?>