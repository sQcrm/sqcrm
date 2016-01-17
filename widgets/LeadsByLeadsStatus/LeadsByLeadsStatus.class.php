<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class LeadsByLeadsStatus 
* @author Abhik Chakraborty
*/ 
	

class LeadsByLeadsStatus extends DashboardWidgetProcessor {
	public $table = "";
	public $primary_key = "";

	function __construct() {
		$this->set_widget_title(_('Leads By Leads Status'));
	}
	
	/**
	* function to get the leads by leads status for graph
	* @return array $result
	*/
	public function get_leads_by_status_graph() {
		$result = array();
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition("leads",3);
		$qry = "
		select count(*) as `tot_leads`,
		`lead_status`,
		`leads_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `leads`
		left join `user` on `user`.`iduser` = `leads`.`iduser`
		left join `leads_to_grp_rel` on `leads_to_grp_rel`.`idleads` = `leads`.`idleads`
		left join `group` on `group`.`idgroup` = `leads_to_grp_rel`.`idgroup`
		where `leads`.`deleted` = 0 AND `leads`.`converted` = 0
		".$security_where."
		group by `leads`.`lead_status`
		";
		$this->query($qry);
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$result[$this->lead_status] = $this->tot_leads;
			}
		}
		return $result ;
	}
    
}