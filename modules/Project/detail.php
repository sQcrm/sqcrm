<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Project detail 
* @author Abhik Chakraborty
*/  

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$module_obj = new Project();
$module_obj->getId($sqcrm_record_id);

$project_members = $module_obj->get_project_members($module_obj);
$additional_permissions = $module_obj->get_additional_permissions($sqcrm_record_id);
$allowed_actions = array();
$signed_in_user = $_SESSION["do_user"]->iduser;
$allowed_actions['task_create'] = false;
$allowed_actions['project_members'] = false ;

// who can create the task
if ($additional_permissions['task_create'] == 2) {
	$allowed_actions['task_create'] = true;
} else {
	if (array_key_exists($signed_in_user,$project_members['assigned_to'])) {
		$allowed_actions['task_create'] = true;
	}
}

// who can add more users into the Project
if ($additional_permissions['project_members'] == 2) {
	$allowed_actions['project_members'] = true;
} else {
	if (array_key_exists($signed_in_user,$project_members['assigned_to'])) {
		$allowed_actions['project_members'] = true;
	}
}

//updates detail, just add and last updated
$do_crmentity = new CRMEntity();
$update_history = $do_crmentity->get_last_updates($sqcrm_record_id,$module_id,$module_obj);

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/detail_view_entry.php');
} else {
	require_once('view/detail_view.php');
}
?>