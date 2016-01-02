<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class CallsAndEvents 
* @author Abhik Chakraborty
*/ 
	

class CallsAndEvents extends DashboardWidgetProcessor {
	public $table = "";
	public $primary_key = "";

	function __construct() {
		$this->set_widget_title(_('Calls And Meetings'));
	}
	
	/**
	* function to get the events count by day
	* @param integer $year
	* @param integer $month
	* @param integer $iduser
	* @return array
	*/
	public function get_events_count_by_day($year,$month,$iduser="") {
		$return_array = array();
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition("events",2,false);
		$qry = "
		select count(*) as tot_events,
		DATE_FORMAT(`start_date`,'%d') as `day`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`
		from `events` 
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		where `events`.`deleted` = 0 
		AND start_date like ?
		".$security_where."
		group by DATE_FORMAT(`start_date`,'%d')";
		$this->query($qry,array('%'.$year.'-'.$month.'%'));
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[(int)$this->day] = $this->tot_events ;
			}
		}
		return $return_array ;
	}
	
	/**
	* function to get all events by day
	* @param integer $year
	* @param integer $month
	* @param integer $iduser
	*/
	public function get_all_events_by_day($year,$month,$day,$iduser = "") {
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition("events",2,false);
		if (strlen($month) == 1) $month = '0'.$month;
		if (strlen($day) == 1) $day = '0'.$day;
		$event_date = $year."-".$month."-".$day;
		$qry = "
		select `events`.* ,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`
		from
		`events`
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		where `events`.`deleted` = 0 
		AND start_date = ? 
		".$security_where."
		order by `start_time`
		";
		$this->query($qry,array($event_date));
	}
    
}