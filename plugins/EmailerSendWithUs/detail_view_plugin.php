<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
use sendwithus\API;
require_once(BASE_PATH.'/plugins/EmailerSendWithUs/libs/sendwithus/vendor/autoload.php');
$sqcrm_record_id = (int)$_GET["sqrecord"];
$emailer = new EmailerSendWithUs();
$contact_detail = $emailer->get_contacts_detail($sqcrm_record_id);
if (count($contact_detail) > 0) {
	$email_found = true;
	$primary_email = '';
	$secondary_email = '';
	if ($contact_detail[0]['email'] == '' && $contact_detail[0]['secondary_email'] == '') $email_found = false;
	if ($contact_detail[0]['email'] != '') $primary_email = $contact_detail[0]['email'];
	if ($contact_detail[0]['secondary_email'] != '') $secondary_email = $contact_detail[0]['secondary_email'];
	$api = $emailer->get_api_instance();
	$err = '';
	
	if (false === $email_found) {
		$err = _('No email found to fetch the activity from sendwithus');
	} else {
		if ($primary_email != '') {
			$primary_email_log = $api->get_customer_logs($primary_email);
			$primary_email_log_data = array();
			if ($primary_email_log->success == 1 && count($primary_email_log->logs) > 0) {
				foreach ($primary_email_log->logs as $key=>$val) {
					$primary_email_log_data[] = array(
						'template_name'=>$val->email_name,
						'status'=>$val->status,
						'sent_at'=>i18nDate::i18n_long_date(date('Y-m-d H:i:s',$val->created),true)
					);
				}
			}
			
		}
		if ($secondary_email != '') {
			$secondary_email_log = $api->get_customer_logs($secondary_email);
			$secondary_email_log_data = array();
			if ($secondary_email_log->success == 1 && count($secondary_email_log->logs) > 0) {
				foreach ($secondary_email_log as $key=>$val) {
					$secondary_email_log_data[] = array(
						'template_name'=>$val->email_name,
						'status'=>$val->status,
						'sent_at'=>i18nDate::i18n_long_date(date('Y-m-d H:i:s',$val->created),true)
					);
				}
			}
		}
	} 
} else {
	$err = _('Missing contact data to fetch the activity from sendwithus');
}
include_once('view/deltailview_plugin_view.php');