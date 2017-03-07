<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* project members
* @author Abhik Chakraborty
*/  

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	if ((int)$sqcrm_record_id > 0) {
		$do_project = new Project();
		$members = $do_project->get_users_to_be_assigned($sqcrm_record_id);
		require_once('view/project_members_entry_view.php');
	}
} 
?>