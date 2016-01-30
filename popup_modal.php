<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* popup modal handler.
* @see .htaccess
* @author Abhik Chakraborty
*/
$modal_file = '';
include_once("config.php") ;

$sqcrm_record_id = '';
$allow_disp = false ;
$admin_modules = false ;
$is_admin = false ;
$m = $_REQUEST["m"];
$action = $_REQUEST["action"];


if (isset($_REQUEST["modalname"]) && $_REQUEST["modalname"] != '') {
	$modal_file = $_REQUEST["modalname"];
} else {
	$e_popup_header = _('Missing File');
	$e_popup_message = _('No file found to load as popup');
	require('popups/error_modal.php');
	exit;
}
  
if ($m=='') {
	$e_popup_header = _('Missing Module');
	$e_popup_message = _('Module name is missing');
	require('popups/error_modal.php');
	exit;
}
  
if (isset($_REQUEST["sqrecord"]) && $_REQUEST["sqrecord"] != '') {
	$sqcrm_record_id = (int)$_REQUEST["sqrecord"];
}
  
if (is_object($_SESSION["do_user"]) && $_SESSION["do_user"]->iduser > 0 && $_SESSION["do_user"]->is_admin == 1) {
	$is_admin = true ; 
}
  
if ($m == 'Settings') {
	$admin_modules = true ;
    if ($is_admin === true) {
		$allow_disp = true ;
		if ($sqcrm_record_id > 0) { 
			$obj_entity =  new $_GET["classname"]();
			$obj_entity->getId($sqcrm_record_id); 
			if ($obj_entity->getNumRows() == 0) { 
				$e_popup_header = _('Record does not exist');
				$e_popup_message = _('Record you are trying to access does not exist.');
				require('popups/error_modal.php');
				exit;
			}
		}
	}
}
  
$module_id = $_SESSION["do_module"]->get_idmodule_by_name($m,$_SESSION["do_module"]);    
$modules_info = $_SESSION["do_module"]->get_modules_with_full_info();
  
if ($module_id !== false && $admin_modules === false) {
	if ($module_id == 7) {
		if ($is_admin === true || $modal_file == 'change_user_avatar') {
			$allow_disp = true ;
        }
	} else {
		switch ($action) {
			case 'index' :
				$allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted('view',$module_id,$sqcrm_record_id);
                break;
                
			case 'list' :
				$allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted('view',$module_id,$sqcrm_record_id);
                break;
                
			case 'add' :
				$allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted('add',$module_id,$sqcrm_record_id);
                break;
                
			case 'edit' :
				$allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id,$sqcrm_record_id);
				break;
				
			case 'delete' :
				$allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id,$sqcrm_record_id);
				break;
				
			default :
                $allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted('view',$module_id,$sqcrm_record_id);
                break;
		}
	}
	
	if ($allow_disp === true) {
		require('popups/'.$modal_file.'.php');
	} else {
		$e_popup_header = _('Unauthorized access');
		$e_popup_message = _('You are not authorized to perform this operation');
		require('popups/error_modal.php');
		exit;
	}
} elseif ($admin_modules === true && $allow_disp === true) {
	require('popups/'.$modal_file.'.php');
} elseif ($module_id === false || $allow_disp !== true) {
	$e_popup_header = _('Module Not Found');
	$e_popup_message = _('The module you are trying to access is ether not available or is disabled for accessing.');
	require('popups/error_modal.php');
	exit;
}  
?>