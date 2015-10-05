<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* module.php , handling the module request
* checks for the sequrity and then load the requested file
* @author Abhik Chakraborty 
*/

$sqcrm_record_id = '';
$module = '';
$current_file = '';
include_once("config.php") ;
  
/**
* Create a persistent object for the CRMActionPermission Object
* @see class/CRMActionPermission.class.php
*/
if (!is_object($_SESSION["do_crm_action_permission"])) {
	$do_crm_action_permission = new CRMActionPermission();
	$do_crm_action_permission->sessionPersistent("do_crm_action_permission", "logout.php", TTL);
}
  
/**
* Create a persistent object for the Module Object 
* @see modules/Settings/Modules.class.php
*/
if (!is_object($_SESSION["do_module"])) {
	$do_module = new Module();
	$do_module->sessionPersistent("do_module", "logout.php", TTL);
	$_SESSION["do_module"]->load_active_modules();
}
$modules_info = $_SESSION["do_module"]->get_modules_with_full_info();

/**
* Create a persistent object for the CRMMessages to display the messages
* @see class/CRMMessages.class.php
* @see includes/pagetop.inc.php
*/
if (!is_object($_SESSION["do_crm_messages"])) {
	$do_crm_messages = new CRMMessages();
	$do_crm_messages->sessionPersistent("do_crm_messages", "logout.php", TTL);
}
  
/**
* Check the request params which are defined in the mod rewrite in .htaccess
* sfmodname :: The module name
* sqrecord :: record id of an entity for a module
* the values are stored in variables $module and $sqcrm_record_id so that it could be used throughout with the getting 
* them again from the query string
* 
*/
if (isset($_REQUEST["sfmodname"]) && $_REQUEST["sfmodname"] != '') {
	$module = $_REQUEST["sfmodname"];
	$current_file = $_REQUEST["sfaction"];
	$sqcrm_record_id = '';
	if (isset($_REQUEST["sqrecord"]) && $_REQUEST["sqrecord"] != '') {
		$sqcrm_record_id = (int)$_REQUEST["sqrecord"];
	}

	// Check the permission 
	$allow_disp = false ; 
	$admin_modules = false ;
	$is_admin = false ;
	if (is_object($_SESSION["do_user"]) && $_SESSION["do_user"]->iduser > 0 && $_SESSION["do_user"]->is_admin == 1) {
		$is_admin = true ; 
	}
	if ($module == 'Settings') {
		$admin_modules = true ;
		if ($is_admin === true) {
			$allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted_settings($current_file,$sqcrm_record_id) ;
		}
	}

	if ($current_file == '') { $action = 'index'; } else { $action = $current_file ; }
	$module_id = $_SESSION["do_module"]->get_idmodule_by_name($module,$_SESSION["do_module"]);    

	/**
	* Check if the login got expired or not
	* If the session has expired then load the login page
	* If user logs out by clicking the logout link then it will redirect to login page and we will exclude the logout page to include the 
	* logout page again
	*/
	$show_login_on_session_expire = false ;
	$login_next_url = '';
	if (!is_object($_SESSION["do_user"]) && $current_file != 'login') {
		$show_login_on_session_expire = true ;
		$session_expire_message = _('Your session has been expired, please login again.');
	}
	if (is_object($_SESSION['do_user']) && $current_file != 'login') {
		try {
			if (!$_SESSION['do_user']->iduser) {
				$show_login_on_session_expire = true ;
				$session_expire_message = _('Error getting the user information, looks like your session has been expired. Please login again.');
			}
		} catch (Exception $e) {
			$show_login_on_session_expire = true ;
			$session_expire_message = _('Your session has been expired, please login again.');
		}
	}
	
	/**
	* if session expired other than logout page then include the login page 
	* the next page after login is set as current page or null
	* if the its current page then after login the redirect will happen to the same page where
	* the session expired else to home page
	* @see view/login_view.php
	*/
	if ($show_login_on_session_expire === true) {
		$login_next_url = 'current_page';
		$_SESSION["do_crm_messages"]->set_message('error',$session_expire_message);
		include("includes/header.inc.php") ;
		include("includes/pagetop.inc.php") ;
		require('modules/User/login.php');
		include("includes/footer.inc.php");
		exit;
	}
    
	// For any _ajax_.* and ajaxreq files will not have the header added in the response
	if (preg_match("#^_ajax_(.*)$#i",$current_file) == 0 && ( !isset($_REQUEST['ajaxreq']) || $_REQUEST['ajaxreq'] != true )) {
		$pageTitle = "sQcrm :: ".$_SESSION["do_module"]->modules_full_details[$module_id]["label"];
		$Author = "sQcrm";
		$Keywords = "sQcrm,CRM,Opensource CRM,Lead,Contact,Prospect";
		$Description = "Opensource CRM for maintaining Leads,Contacts,Prospects";
		include("includes/header.inc.php") ;
		include("includes/pagetop.inc.php") ;
	}

	if ($module_id !== false && $admin_modules === false) {
		if ($module_id == 7) {
			if ($is_admin === true) {
				$allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted_user($action,$sqcrm_record_id) ;
			} else {
				if($current_file == 'login') $allow_disp = true ;
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
			//include top block containing other info like breadcrum and all
			if (preg_match("#^_ajax_(.*)$#i",$current_file) == 0 && ( !isset($_REQUEST['ajaxreq']) || $_REQUEST['ajaxreq'] != true ) && $module_id !=7) {
				include("includes/topblocks.inc.php") ;
			}
			require('modules/'.$module.'/'.$action.'.php');
		} else {
			echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
			echo '<strong>';
			echo _('Access Denied ! ');
			echo '</strong>';
			echo _('You are not authorized to perform this operation.');
			echo '</div>';
		}
	} elseif ($admin_modules === true && $allow_disp === true) {
		require('modules/'.$module.'/'.$action.'.php');
	} elseif ($admin_modules === true && $allow_disp === false) {
		echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
		echo '<h4>';
		echo _('Access Denied ! ');
		echo '</h4>';
		echo _('You are not authorized to perform this operation.');
		echo '</div>';
	} elseif ( $module_id === false || $allow_disp !== true) {
		echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
		echo '<h4>';
		echo _('Module Not Found ! ');
		echo '</h4>';
		echo _('The module you are trying to access is ether not available or is disabled for accessing.');
		echo '</div>';
	}   
} else {
	echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
	echo '<strong>';
	echo _('Module Name Missing ! ');
	echo '</strong>';
	echo _('The module name is missing to perform any operation.');
	echo '</div>';
}

// For any _ajax_.* and ajaxreq files will not have the footer added in the response
if (preg_match("#^_ajax_(.*)$#i",$current_file) == 0 && ( !isset($_REQUEST['ajaxreq']) || $_REQUEST['ajaxreq'] != true )) {
	include("includes/footer.inc.php");
}
?>