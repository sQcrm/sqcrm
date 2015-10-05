<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Detail page 
* @author Abhik Chakraborty
*/  

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$module_obj = new Leads();
$module_obj->getId($sqcrm_record_id);
$converted_lead = false ;

//updates detail, just add and last updated
$do_crmentity = new CRMEntity();
$update_history = $do_crmentity->get_last_updates($sqcrm_record_id,$module_id,$module_obj);

if ($module_obj->converted == 1) { 
	$converted_lead = true ; 
	$lead_conversion = new LeadConversion();
	$lead_conversion_matrix = $lead_conversion->get_conversion_matrix($sqcrm_record_id) ;
}
if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/detail_view_entry.php');
} else {
	require_once('view/detail_view.php');
}
?>