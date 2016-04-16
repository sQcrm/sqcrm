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
include_once('config.php');

if (!is_object($_SESSION["do_cpanel_messages"])) {
	$do_crm_messages = new CRMMessages();
	$do_crm_messages->sessionPersistent("do_cpanel_messages", "logout.php", TTL);
}

if (!is_object($_SESSION["do_cpanel_action_permission"])) {
	$do_action_permission = new cpanel_actionpermissions\CRMActionPermission();
	$do_action_permission->sessionPersistent("do_cpanel_action_permission", "logout.php", TTL);
}
$_SESSION["do_cpanel_action_permission"]->load_cpanel_modules() ;

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
	$sqcrm_record_id = 0;
	if (isset($_REQUEST["sqrecord"]) && $_REQUEST["sqrecord"] != '') {
		$sqcrm_record_id = (int)$_REQUEST["sqrecord"];
	}
	
	/**
	* Check if the login got expired or not
	* If the session has expired then load the login page
	* If user logs out by clicking the logout link then it will redirect to login page and we will exclude the logout page to include the 
	* logout page again
	*/
	$show_login_on_session_expire = false ;
	$login_next_url = '';
	$show_login_on_session_expire = false ;
	$login_next_url = '';
	if (!is_object($_SESSION["do_cpaneluser"]) && $current_file != 'login') {
		$show_login_on_session_expire = true ;
		$session_expire_message = _('Your session has been expired, please login again.');
	}
	if (is_object($_SESSION['do_cpaneluser']) && $current_file != 'login') {
		try {
			if (!$_SESSION['do_cpaneluser']->idcpanel_user) {
				$show_login_on_session_expire = true ;
				$session_expire_message = _('Error getting the user information, looks like your session has been expired. Please login again.');
			}
		} catch (Exception $e) {
			$show_login_on_session_expire = true ;
			$session_expire_message = _('Your session has been expired, please login again.');
		}
	}
	
	// Check the permission 
	$allow_disp = false ; 
	if ($current_file == '') { $action = 'index'; } else { $action = $current_file ; }
	$module_id = $_SESSION["do_cpanel_action_permission"]->cpanel_modules[$module] ;
	if ($module_id == 7  && $current_file == 'login') {
		$allow_disp = true ;
	} else {
		$allow_disp = $_SESSION["do_cpanel_action_permission"]->action_permitted('view',$module_id,$sqcrm_record_id) ;
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
		$_SESSION["do_cpanel_messages"]->set_message('error',$session_expire_message);
		include("includes/header.inc.php") ;
		include("includes/pagetop.inc.php") ;
		require('modules/User/login.php');
		include("includes/footer.inc.php");
		exit;
	}
    
	// For any _ajax_.* and ajaxreq files will not have the header added in the response
	if (preg_match("#^_ajax_(.*)$#i",$current_file) == 0 && ( !isset($_REQUEST['ajaxreq']) || $_REQUEST['ajaxreq'] != true )) {
		$pageTitle = "sQcrm :: cPanel";
		$Author = "sQcrm";
		$Keywords = "sQcrm,CRM,Opensource CRM,Lead,Contact,Prospect";
		$Description = "Opensource CRM for maintaining Leads,Contacts,Prospects";
		include("includes/header.inc.php") ;
		include("includes/pagetop.inc.php") ;
	}
	
	//$allow_disp = true ;
	if ($module_id !== false) {
		if ($allow_disp === true) {
			require('modules/'.$module.'/'.$action.'.php');
		} else {
			echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
			echo '<strong>';
			echo _('Access Denied ! ');
			echo '</strong>';
			echo _('You are not authorized to perform this operation.');
			echo '</div>';
		}
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