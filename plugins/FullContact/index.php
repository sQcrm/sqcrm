<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
$idmodule = (int)$_GET["idmodule"] ;
$sqcrm_record_id = (int)$_GET["sqrecord"] ;

$module_name = $modules_info[$idmodule]['name'] ;
$do_module_object = new $module_name();
/** 
* if the viewing module is Contacts then use the person api
* @link https://api.fullcontact.com/v2/person.json?email=< contact's email id >&apiKey=< your api key >
* 
* if the viewing module is Organization then use the company api 
* @link https://api.fullcontact.com/v2/company/lookup.json?domain=< domain_name >&apiKey=< your api key >
*/
if ($idmodule == 4) {
	$contact_emails = array() ;
	$email_ids = $do_module_object->get_contact_emails($sqcrm_record_id,false) ;
	if (is_array($email_ids) && count($email_ids) > 0) {
		$contact_emails = $email_ids[0]['email'] ;
	}
	$contact_info_fullcontact = array();
	$msg = _('Social Information');
} else {
	$do_full_contact = new FullContact() ;
	$org_websites = $do_full_contact->get_organization_websites($sqcrm_record_id) ;
	$organization_info_fullcontact = array();
	$msg = _('Social Information');
	if (strlen($json) > 4) {
		$organization_info_fullcontact = json_decode($json,true) ;
	}
}
include_once('view/plugin_view.php');
?>