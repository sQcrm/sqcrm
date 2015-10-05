<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Products detail 
* @author Abhik Chakraborty
*/  

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$module_obj = new Products();
$module_obj->getId($sqcrm_record_id);

if ((int)$module_obj->idproducts > 0) {
//updates detail, just add and last updated
	$do_crmentity = new CRMEntity();
	$update_history = $do_crmentity->get_last_updates($sqcrm_record_id,$module_id,$module_obj);

	if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
		require_once('view/detail_view_entry.php');
	} else {
		require_once('view/detail_view.php');
	}
} else {
	echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
	echo '<strong>';
	echo _('Access Denied ! ');
	echo '</strong>';
	echo _('You are not authorized to perform this operation.');
	echo '</div>';
}

?>