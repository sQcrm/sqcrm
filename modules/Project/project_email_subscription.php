<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* project members
* @author Abhik Chakraborty
*/  

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	if ((int)$sqcrm_record_id > 0) {
		$do_project = new Project();
		$email_subscription = $do_project->get_email_subscription_for_project_by_user($sqcrm_record_id);
		require_once('view/project_email_subscription_entry_view.php');
	}
} 
?>