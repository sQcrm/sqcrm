<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* User detail 
* @author Abhik Chakraborty
*/  

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$module_obj = new User();
$module_obj->getId($sqcrm_record_id);
if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/detail_view_entry.php');
} else {
	require_once('view/user_detail_view.php');
}
?>