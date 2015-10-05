<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Data History
* @author Abhik Chakraborty
*/  

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$start = 0 ;
$end = 0 ;

if (isset($_GET["start"]) && $_GET["start"] != '') {
	$start = (int)$_GET["start"] ;
}

if (isset($_GET["max"]) && $_GET["max"] != '') {
	$max = (int)$_GET["max"] ;
}

$do_datahistory_display = new DataHistoryDisplay();
$data_history = $do_datahistory_display->get_data_history_user($sqcrm_record_id,$start,$max);

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	if (isset($_GET['ajax_load_more']) && $_GET['ajax_load_more'] == true) {
		if ($data_history === false) {
			echo false ;
		} else {
			require_once('view/history_view_entry.php');
		}
	} else {
		require_once('view/history_view.php');
	}
} else {
	require_once('view/history_view.php');
}
?>