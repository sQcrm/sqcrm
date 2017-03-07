<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* project permission
* @author Abhik Chakraborty
*/  
$module_obj = new Project();
$module_obj->getId($sqcrm_record_id);

$do_task = new Tasks();

$project_members = $module_obj->get_project_members($module_obj);
$additional_permissions = $module_obj->get_additional_permissions($sqcrm_record_id);
$priorities = $do_task->get_task_priority();
$labels = json_encode($do_task->get_task_labels());

$signed_in_user = $_SESSION["do_user"]->iduser;
$project_name = $module_obj->project_name;
$allow_task_create = false;
$allow_task_assignees = false;
$mention_members_but_me = array();
$mention_member_json = '';

// generate the json for the mention @user in note, ignore the current user in the list
foreach ($project_members['assigned_to'] as $key=>$val) {
	if ($key == $signed_in_user) continue;
	
	$mention_members_but_me[] = $val["user_name"].'('.$val["firstname"].' '.$val["lastname"].')';
}

if (count($project_members['other_assignee']) > 0) {
	foreach ($project_members['other_assignee'] as $key=>$val) {
		if ($key == $signed_in_user) continue;
	
		$mention_members_but_me[] = $val["user_name"].'('.$val["firstname"].' '.$val["lastname"].')';
	}
}

$mention_member_json = json_encode($mention_members_but_me);

$allow_task_create = $module_obj->check_additional_permissions(
	array(
		'members'=>$project_members,
		'permissions'=>$additional_permissions
	),'task_create'
);

$allow_task_assignees = $module_obj->check_additional_permissions(
	array(
		'members'=>$project_members,
		'permissions'=>$additional_permissions
	),'task_assignees'
);

if (true === $allow_task_create) {
	$cancel_referrer = '';
	if (isset($_SESSION['task_add_referrer']) && $_SESSION['task_add_referrer'] != '') {
		$cancel_referrer = $_SESSION['task_add_referrer'];
	} else {
		$cancel_referrer = 'Project/'.$sqcrm_record_id.'/task/list';
	}
	require_once('view/task_create_view.php');
} else {
	echo '<br /><br />';
	echo '<div class="datadisplay-outer">
			<div class="alert alert-danger">
				<strong>
				'. _('You are not authorized to perform this operation.').
				'
				</strong>
			</div>
		</div>';
}
?>