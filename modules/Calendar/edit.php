<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Calendar edit 
* @author Abhik Chakraborty
*/  
    
$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$module_obj = new Calendar();
$module_obj->getId($sqcrm_record_id);
if (isset($_GET["return_page"]) && $_GET["return_page"] != '') {
	$return = $_GET["return_page"] ;
	$cancel_return = NavigationControl::getNavigationLink($module,$return,$sqcrm_record_id);
} else {
	$cancel_return = NavigationControl::getNavigationLink($module,"list");
}
// Recurrent event info
$recurrent_events = new RecurrentEvents();
$recurrent_events_pattern = $recurrent_events->has_recurrent_events($sqcrm_record_id);
$recurrent_events_pattern = json_decode($recurrent_events_pattern,true);
$text_options = $recurrent_events->get_text_options();
$days_in_week = $recurrent_events->get_days_in_week();

//event reminder info
$do_events_reminder = new EventsReminder() ;
$reminder = $do_events_reminder->get_event_reminder($sqcrm_record_id);

//Assigned to iduser or group ?
if ($module_obj->iduser > 0) {
	$assigned_to = 'user_'.$module_obj->iduser;
} elseif ($module_obj->idgroup > 0) {
	$assigned_to = 'group_'.$module_obj->idgroup;
}
require_once('view/calendar_edit_view.php');
?>