<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* project permission
* @author Abhik Chakraborty
*/  

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	$do_project = new Project();
	$do_project->getId($sqcrm_record_id);
	$permissions = $do_project->get_additional_permissions($sqcrm_record_id);
	$project_members = $do_project->get_project_members($do_project);
	$current_user = $_SESSION["do_user"]->iduser;
	$allow_action = false;
	$permission_changer = array();
	
	if (trim($permissions['permission_changer']) !==null && trim($permissions['permission_changer']) != '') {
		$permission_changer = explode(',',$permissions['permission_changer']);
	}
	
	if (array_key_exists($current_user,$project_members['assigned_to'])) {
		$allow_action = true;
	} else {
		if (is_array($permission_changer) && count($permission_changer) > 0) {
			foreach ($permission_changer as $iduser) {
				if (array_key_exists($iduser,$project_members['other_assignee']) && $current_user == $iduser) {
					$allow_action = true;
					break;
				}
			}
		}
	}
	
	if (true === $allow_action) {
		$do_user = new User();
		$do_user->get_all_users();
		$permission_changer_data = array();
		$assigned_users = array();
		
		while ($do_user->next()) {
			if (count($permission_changer) > 0 && in_array($do_user->iduser,$permission_changer)) {
				$permission_changer_data['assigned'][] = array(
					'firstname'=>$do_user->firstname,
					'lastname'=>$do_user->lastname,
					'user_name'=>$do_user->user_name,
					'email'=>$do_user->email,
					'iduser'=>$do_user->iduser,
					'user_avatar'=>$do_user->user_avatar
				);
				$assigned_users[] = $do_user->iduser;
			} 
			
			if (!in_array($do_user->iduser,$assigned_users) && array_key_exists($do_user->iduser,$project_members['other_assignee'])) {
				$permission_changer_data['not_assigned'][] = array(
					'firstname'=>$do_user->firstname,
					'lastname'=>$do_user->lastname,
					'user_name'=>$do_user->user_name,
					'email'=>$do_user->email,
					'iduser'=>$do_user->iduser,
					'user_avatar'=>$do_user->user_avatar
				);
			}
		}
		require_once('view/project_permission_view.php');
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

} 
?>