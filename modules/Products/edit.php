<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Products edit 
* @author Abhik Chakraborty
*/  
    
$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$module_obj = new Products();
$module_obj->getId($sqcrm_record_id);
if ((int)$module_obj->idproducts > 0) {
	if (isset($_GET["return_page"]) && $_GET["return_page"] != '') {
		$return = $_GET["return_page"] ;
		$cancel_return = NavigationControl::getNavigationLink($module,$return,$sqcrm_record_id);
	} else {
		$cancel_return = NavigationControl::getNavigationLink($module,"list");
	}
	//Assigned to iduser or group ?
	if ($module_obj->iduser > 0) {
		$assigned_to = 'user_'.$module_obj->iduser;
	} elseif ($module_obj->idgroup > 0) {
		$assigned_to = 'group_'.$module_obj->idgroup;
	}
	require_once('view/edit_view.php');
} else {
	echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
	echo '<strong>';
	echo _('Access Denied ! ');
	echo '</strong>';
	echo _('You are not authorized to perform this operation.');
	echo '</div>';
}
?>