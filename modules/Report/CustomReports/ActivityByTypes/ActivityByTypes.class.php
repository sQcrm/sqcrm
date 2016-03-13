<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* class ActivityByTypes
* @author Abhik Chakraborty
*/
class ActivityByTypes Extends CustomReport {

	/**
	* function to get the avtivity count by type and user
	* @param string $where
	* @param integer $iduser
	* @param integer $date_filter_type
	* @param string $start_date
	* @param string $end_date
	* @return array
	*/
	public function get_activity_by_types($where = '',$iduser=0,$date_filter_type=15,$start_date='',$end_date='') {
		if ($where == '') {
			if ((int)$iduser == 0) {
				$iduser = $_SESSION["do_user"]->iduser ;
			}
			$where = '';
			$user_where = $this->get_report_where($iduser,'e','etg') ;
			$date_where = $this->get_date_filter_where('p','start_date',$date_filter_type,$start_date='',$end_date='') ;
			$where = $user_where.$date_where ;
		}

		$return_array = array() ;
		$qry = "
		select e.event_type,count(*) as total ,
		case when (u.user_name not like '')
		then
		u.user_name
		else
		g.group_name
		end
		as `assigned_to`
		from events e 
		left join `user` u on `u`.`iduser` = `e`.`iduser`
		left join `events_to_grp_rel` etg on `etg`.`idevents` = `e`.`idevents`
		left join `group` g on `g`.`idgroup` = `etg`.`idgroup`
		where e.deleted = 0
		$where
		group by e.event_type,assigned_to
		" ;
		$this->query($qry) ;
		
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[$this->assigned_to][$this->event_type] = $this->total ;
			}
		}
		return $return_array ;
	}

	/**
	* function to get the different activity types used in the combo 
	* @return array
	*/
	public function get_activity_types() {
		$qry = "select cv.* from combo_values cv 
		join fields f on f.idfields = cv.idfields 
		where f.field_name = 'event_type' 
		and f.table_name = 'events' ;
		";
		$this->query($qry) ;
		$event_types = array() ;
		if ($this->getNumRows() > 0) {
			while($this->next()) {
				$event_types[] = $this->combo_value ;
			}
		}
		return $event_types ;
	}
	
	/**
	* function to get the detailed activity data 
	* @param string $where
	* @param integer $iduser
	* @param integer $date_filter_type
	* @param string $start_date
	* @param string $date_end
	* @return array
	*/
	public function get_detailed_activity_data($where='',$iduser=0,$date_filter_type,$start_date='',$end_date='') {
		$do_calendar = new Calendar() ;
		$do_calendar->get_list_query() ;
		if ($where == '') {
			$user_where = $this->get_report_where($iduser,'events','events_to_grp_rel') ;
			$date_where = $this->get_date_filter_where('events','start_date',$date_filter_type,$start_date='',$end_date='') ;
			$where = $user_where.$date_where ;
		}
		$qry = $do_calendar->getSqlQuery() ;
		$qry .= $where ;
		$this->query($qry) ;
	}
}