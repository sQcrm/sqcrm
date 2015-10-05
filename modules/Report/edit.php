<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* edit report
* @author Abhik Chakraborty
*/

$edit = 1 ;
if(!isset($_GET["step"])){
	$step = 'step1';
	if(isset($_SESSION["report_type"])){
		$report_type = $_SESSION["report_type"] ;
	}else{
		$do_report = new Report();
		$do_report->getId($sqcrm_record_id);
	}
}else{
	if((int)$_GET["step"] == 1){
		$step = 'step1';
		if(isset($_SESSION["report_type"])){
			$report_type = $_SESSION["report_type"] ;
		}else{
			$do_report = new Report();
			$do_report->getId($sqcrm_record_id);
		}
	}elseif((int)$_GET["step"] == 2){
		$step = 'step2';
		if(isset($_SESSION["primary_module"])){ 
			$primary_module = $_SESSION["primary_module"] ;
		}
	}elseif((int)$_GET["step"] == 3){
		$step = 'step3';
		if(isset($_SESSION["secondary_module"])){
			$secondary_module = $_SESSION["secondary_module"] ;
		}
	}elseif((int)$_GET["step"] == 4){
		$step = 'step4';
		if(isset($_SESSION["report_fields"])){
			$report_fields = $_SESSION["report_fields"] ;
		}
	}elseif((int)$_GET["step"] == 5){
		$step = 'step5';
		if(isset($_SESSION["report_order_by"])){
			$report_fields = $_SESSION["report_order_by"] ;
		}
	}elseif((int)$_GET["step"] == 6){
		$step = 'step6';
		if(isset($_SESSION["report_filter"])){
			$report_filter = $_SESSION["report_filter"] ;
		}
	}elseif((int)$_GET["step"] == 7){
		$step = 'step7';
		if(isset($_SESSION["report_details"])){
			$report_details = $_SESSION["report_details"] ;
		}
	}
}
require_once('view/create_report_'.$step.'_view.php');
?>