<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Login audit
* @author Abhik Chakraborty
*/  

$fields_info = array(
	"action"=>array(
		"table"=>"login_audit",
		"field_label"=>_("Action"),
		"field_type"=>1,
	),
	"action_date"=>array(
		"table"=>"login_audit",
		"field_label"=>_("Date & Time"),
		"field_type"=>91,
	),
	"ip_address"=>array(
		"table"=>"login_audit",
		"field_label"=>_("IP Address"),
		"field_type"=>1,
	)
);
              
if (!is_object($_SESSION["LoginAudit"])) {
	$do_login_audit = new LoginAudit();
	$do_login_audit->sessionPersistent("LoginAudit","logout.php", TTL);
}
$lp_mid = $module_id;
$list_special = true;
$list_special_object = "LoginAudit";
$_SESSION["LoginAudit"]->list_view_field_information = $fields_info;
$method = "get_login_audit";
$method_param = json_encode(array($sqcrm_record_id));
require_once('view/listview_entry.php');
?>