<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Cronjob to send event reminder
* Usually it should run every minute round the clock.
* $GLOBALS['cfg_full_path'] is the path to the CRM root so that the cron job could be 
* set outside web access directory which is recomended
* TODO : 
* In the current version it does not care about the individual user time zone before sending the email
* So in the future version need to implement the code such that emails are send as per the user timezone
* @author Abhik Chakraborty
*/


$GLOBALS['cfg_full_path'] = '/var/www/sqcrm/';

include_once($GLOBALS['cfg_full_path'].'config.php');
$email_template = new EmailTemplate("event_reminder");
$emailer = new SQEmailer();
$do_event_reminder = new EventsReminder();
$do_event_reminder->get_reminder_emails_to_send();
$do_calendar = new Calendar();

$now = date("Y-m-d H:i:s");
if ($do_event_reminder->getNumRows() > 0) {
	while ($do_event_reminder->next()) {
		$d = $do_event_reminder->days;
		$h = $do_event_reminder->hours;
		$m = $do_event_reminder->minutes;
		if (strlen($do_event_reminder->email_ids) > 3) {
			$additional_email = explode(",",$do_event_reminder->email_ids);
			if(is_array($additional_email) && count($additional_email) > 0 ){
				$email_to_array = array();
				$email_to_array[0] = $additional_email ;
			}
		} else {
			$email_to_array = array();
		}
    
		$do_calendar->getId($do_event_reminder->idevents);
		if ($do_calendar->getNumRows() > 0) {
			$start_date = $do_calendar->start_date ;
			$start_time = $do_calendar->start_time ;
			$start_date_time = $start_date.' '.$start_time;
			if ($do_calendar->iduser > 0) {
				$do_user = new User();
				$do_user->getId((int)$do_calendar->iduser);
				$email_to_array[(int)$do_calendar->iduser] = array(
					"email"=>$do_user->email,
					"firstname"=>$do_user->firstname,
					"lastname"=>$do_user->lastname
				) ;
			} else {
				$do_group_user_rel = new GroupUserRelation();
				$do_group_user_rel->get_users_related_to_group($do_calendar->idgroup);
				if ($do_group_user_rel->getNumRows() > 0) {
					while ($do_group_user_rel->next()) {
						$email_to_array[] = array(
							"email"=>$do_group_user_rel->email,
							"firstname"=>$do_group_user_rel->firstname,
							"lastname"=>$do_group_user_rel->lastname
						) ;
					}
				}
			}
			$event_url = SITE_URL.NavigationControl::getNavigationLink("Calendar","detail",$do_calendar->idevents);
			$reminder_time = strtotime("- $d days - $h hours - $m minutes ",strtotime($start_date_time)) ;
			if (strtotime($now) >= $reminder_time) {
				foreach ($email_to_array as $key=>$val) {
					if ($key == 0) {
						foreach ($val as $additional_emailids) {
							$email_data = array(
								"firstname"=>"Hi",
								"event_type"=>$do_calendar->event_type,
								"start_time"=>FieldType10::display_value($start_time),
								"start_date"=>FieldType9::display_value($start_date),
								"CRM_NAME"=>CRM_NAME,
								"event_url"=>$event_url,
								"subject"=>$do_calendar->subject
							);
							$emailer->IsSendmail();
							$emailer->setEmailTemplate($email_template);
							$emailer->mergeArray($email_data);
							$emailer->AddAddress($val["email"], $val["firstname"].' '.$val["lastname"]);
							$emailer->send();
							echo "Email Sent to ".$val["email"]."\n";
						}
					} else {
						$email_data = array(
							"firstname"=>$val["firstname"],
							"event_type"=>$do_calendar->event_type,
							"start_time"=>FieldType10::display_value($start_time),
							"start_date"=>FieldType9::display_value($start_date),
							"CRM_NAME"=>CRM_NAME,
							"event_url"=>$event_url,
							"subject"=>$do_calendar->subject
						);
					
						$emailer->IsSendmail();
						$emailer->setEmailTemplate($email_template);
						$emailer->mergeArray($email_data);
						$emailer->AddAddress($val["email"], $val["firstname"].' '.$val["lastname"]); 
						$emailer->send();
						echo "Email Sent to ".$val["email"]."\n";
					}
				}
				$qry = "
				update 
                `events_reminder` 
                set `reminder_send` = 1 
                where `idevents_reminder` = ".$do_event_reminder->idevents_reminder." limit 1";
				$GLOBALS['conn']->getDbConnection()->executeQuery($qry);
			}
		}
	}
}
?>