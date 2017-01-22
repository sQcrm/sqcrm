<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* project permission
* @author Abhik Chakraborty
*/

$idtask = (int)$_REQUEST['idtasks'];
$err = '';
$signed_in_user = $_SESSION["do_user"]->iduser;

if ($idtask > 0) {
	$do_task = new Tasks();
	$do_task->getId($idtask);
	if ($do_task->getNumRows() == 0) {
		$err = _('Task not found !!');
	}
} else {
	$err = _('Missing task id !!');
}

if ($err == '') {
	$do_project = new Project();
	$do_project->getId($sqcrm_record_id);
	$project_members = $do_project->get_project_members($do_project);
	$additional_permissions = $do_project->get_additional_permissions($sqcrm_record_id);
	$priorities = $do_task->get_task_priority();
	$labels = json_encode($do_task->get_task_labels());
	$task_assignees = $do_task->get_task_assignees($idtask);
	$task_labels = $do_task->get_attached_task_labels($idtask);
	
	$task_assignees_ids = array();
	if (is_array($task_assignees) && count($task_assignees) > 0) {
		foreach ($task_assignees as $key=>$val) {
			$task_assignees_ids[] = $val['iduser'];
		}
	}

	$attached_task_labels = array();
	$attached_task_label_ids = array();
	
	if (is_array($task_labels) && count($task_labels) > 0) {
		foreach ($task_labels as $key=>$val) {
			$attached_task_label_ids[] = $val['idtask_labels'];
			$attached_task_labels[] = array(
				'id'=>$val['idtask_labels'],
				'name'=>$val['label']
			);
		}
	}
	
	$project_name = $do_project->project_name;
	$mention_members_but_me = array();
	$mention_member_json = '';
	$allow_task_create = false;
	$allow_task_assignees = false;
	$allow_task_edit = false;
	$allow_task_close = false;
	
	$status = '';
	if ($do_task->task_status == 1) {
		$status = '<span class="label label-success" style="font-size: 16px;">'.$do_task->task_status_name.'</span>';
	} elseif ($do_task->task_status == 2) {
		$status = '<span class="label label-danger" style="font-size: 16px;">'.$do_task->task_status_name.'</span>';
	} elseif ($do_task->task_status == 3) {
		$status = '<span class="label label-info" style="font-size: 16px;">'.$do_task->task_status_name.'</span>';
	}
	
	$priority = $do_task->render_task_priority_display($do_task->priority,$do_task->task_priority);
	
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
	
	$allow_task_create = $do_project->check_additional_permissions(
		array(
			'members'=>$project_members,
			'permissions'=>$additional_permissions
		), 'task_create'
	);
	
	$allow_task_edit = $do_project->check_additional_permissions(
		array(
			'members'=>$project_members,
			'permissions'=>$additional_permissions
		), 'task_edit', $do_task
	);
	
	$allow_task_assignees = $do_project->check_additional_permissions(
		array(
			'members'=>$project_members,
			'permissions'=>$additional_permissions
		), 'task_assignees', $do_task
	);
	
	$allow_task_close = $do_project->check_additional_permissions(
		array(
			'members'=>$project_members,
			'permissions'=>$additional_permissions
		), 'task_close', $do_task
	);
	
	require_once('view/task_detail_view.php');
} else {
	echo '<br /><br />';
	echo '<div class="datadisplay-outer">
			<div class="alert alert-danger">
				<strong>
				'. $err.
				'
				</strong>
			</div>
		</div>';
}

?>