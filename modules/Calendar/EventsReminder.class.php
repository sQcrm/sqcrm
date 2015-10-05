<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* class EventsReminder 
* @author Abhik Chakraborty
*/ 
	

class EventsReminder extends DataObject {
	public $table = "events_reminder";
	public $primary_key = "idevents_reminder";
  
	/**
	* function to add the event reminder data 
	* @param integer $idevents
	* @param object $evctl
	*/
	public function add_event_reminder($idevents,$evctl) {
		if ((int)$idevents > 0) {
			$this->insert(
				$this->getTable(),
				array(
					"idevents"=>$idevents,
					"days"=>$evctl->event_alert_day,
					"hours"=>$evctl->event_alert_hrs,
					"minutes"=>$evctl->event_alert_mins,
					"email_ids"=>$evctl->event_alert_email_ids
				)
			);
		}
	}
  
	/**
	* function to get the reminder information by idevents
	* @param integer $idevents
	* @return array if reminder set else false
	*/
	public function get_event_reminder($idevents){
		$this->query("select * from ".$this->getTable()." where idevents = ?",array($idevents));
		if ($this->getNumRows() > 0) {
			$this->next();
			$return_array = array();
			$return_array["idevents_reminder"] = $this->idevents_reminder ;
			$return_array["days"] = $this->days ;
			$return_array["hours"] = $this->hours ;
			$return_array["minutes"] = $this->minutes ;
			$return_array["email_ids"] = $this->email_ids ;
			return $return_array ;
		} else { return false ; }
	}
  
	/**
	* function to update the event reminder
	* @param integer $idevents
	* @param object $evctl
	*/
	public function update_event_reminder($idevents,$evctl) {
		$id = $this->get_event_reminder($idevents);
		if (false === $id) {
			$this->add_event_reminder($idevents,$evctl);
		} else {
			if (is_array($id) && count($id) > 0) {
				$qry = "
				update ".$this->getTable()."
				set
				`days` = ?,
				`hours` = ?,
				`minutes` = ?,
				`email_ids` = ?
				where idevents_reminder = ?";
				$this->query(
					$qry,
					array(
						$evctl->event_alert_day,
						$evctl->event_alert_hrs,
						$evctl->event_alert_mins,
						$evctl->event_alert_email_ids,
						$id["idevents_reminder"]
					)
				);
			}
		}
	}
  
	/**
	* function to delete the event reminder
	* @param integer $idevents
	*/
	public function delete_event_reminder($idevents){
		$this->query("delete from ".$this->table." where `idevents` = ? limit 1",array($idevents));
	}
  
	/**
	* function to get the reminder emails to send for the cron job
	* @see crons/cron_event_reminder.php
	*/
	public function get_reminder_emails_to_send(){
		$qry = "select * from `".$this->getTable()."` where `reminder_send` = 0 ";
		$this->query($qry);
	}
   
}