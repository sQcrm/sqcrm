<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Calendar add 
* @author Abhik Chakraborty
*/ 

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);
/**
* check if the add request is from related module (Contact or Organization or lead etc)
* then autofil the related to
*/
$add_from_relatedto = false ;
$show_add_page = true ;
if (isset($_GET["related_to"]) && isset($_GET["related_to_module"])) {
	$related_to_id = (int)$_GET["related_to"];
	$related_to_module_id = (int)$_GET["related_to_module"];
	$module_info = $_SESSION["do_module"]->get_modules_with_full_info();
	$related_module_name = $module_info[$related_to_module_id]["name"];
	$cancel_return = NavigationControl::getNavigationLink($related_module_name,"detail",$related_to_id);
	if ($_SESSION["do_crm_action_permission"]->action_permitted('view',$related_to_module_id,$related_to_id) === true) {
		$add_from_related = true ;
	} else {
		$show_add_page = false ;
	}
} else {
	$cancel_return = NavigationControl::getNavigationLink($module,"list");
}
// set event start/end date time
$start_end_date = TimeZoneUtil::get_user_timezone_date();
$start_time = TimeZoneUtil::get_user_timezone_time();
$end_time = date("H:i:s",strtotime("+1 hour",strtotime($start_time)));
//Recurrent event info
$recurrent_events = new RecurrentEvents();
$text_options = $recurrent_events->get_text_options();
$days_in_week = $recurrent_events->get_days_in_week();

if($show_add_page === true) {
	require_once('view/calendar_add_view.php');
} else {
	echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
	echo '<h4>';
	echo _('Access Denied ! ');
	echo '</h4>';
	echo _('You are not authorized to perform this operation.');
	echo '</div>';
}
?>