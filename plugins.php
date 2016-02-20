<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* plugin request handler
* All the plugins which includes HTML output are routed via this file 
* @see includes/modules.footer.inc.php load_detail_view_plugin()
* @author Abhik Chakraborty
*/

include_once("config.php") ;
$idmodule = (int)$_GET["idmodule"] ;
$plugin_name = $_GET["plugin_name"] ;
$resource_name = $_GET["resource_name"] ;

if (!is_object($_SESSION["do_module"])) {
	$do_module = new Module();
	$do_module->sessionPersistent("do_module", "logout.php", TTL);
	$_SESSION["do_module"]->load_active_modules();
}
$modules_info = $_SESSION["do_module"]->get_modules_with_full_info();

if (!is_object($_SESSION["do_user"])) {
	$show_login_on_session_expire = true ;
	$session_expire_message = _('Your session has been expired, please login again.');
}
if (is_object($_SESSION['do_user'])) {
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
if ($show_login_on_session_expire === true) {
	$login_next_url = 'current_page';
	$_SESSION["do_crm_messages"]->set_message('error',$session_expire_message);
	include("includes/header.inc.php") ;
	include("includes/pagetop.inc.php") ;
	require('modules/User/login.php');
	include("includes/footer.inc.php");
	exit;
} else {
	$allow_disp = $_SESSION["do_crm_action_permission"]->action_permitted_user('view',$module_id) ;
	if (true === $allow_disp) {
		require('plugins/'.$plugin_name.'/'.$resource_name);
	} else {
		echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:100px;margin-left:200px;margin-right:200px;">';
		echo '<strong>';
		echo _('Access Denied ! ');
		echo '</strong>';
		echo _('You are not authorized to perform this operation.');
		echo '</div>';
	}
}
?>