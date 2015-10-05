<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class HomePageGraphs 
* @author Abhik Chakraborty
*/ 
	

class HomePageGraphs extends DataObject {
	public $table = "";
	public $primary_key = "";

	function __construct(sqlConnect $conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	/**
	* function to the get the prospect by sales stage for graph
	* @return array $result
	*/
	public function get_prospect_by_sales_stage_graph_data() {
		$result = array();
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition("potentials",5);
		$qry = "
		select count(*) as `total_potentials`, 
		`sales_stage` ,
		`pot_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`
		from `potentials`
		left join `user` on `user`.`iduser` = `potentials`.`iduser`
		left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
		left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
		where `potentials`.`deleted` = 0 
		".$security_where."
		group by `potentials`.`sales_stage`";
		$this->query($qry);
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$result[$this->sales_stage] = (int)$this->total_potentials;
			}
		}
		return $result ;
	}
    
	/**
	* function to get the prospect pipeline by sales stage
	* @return array $result
	*/
	public function get_prospect_pipeline_by_sales_stage_graph() {
		$result = array();
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition("potentials",5);
		$qry = "
		select sum(amount) as `total_amount`, 
		`sales_stage` ,
		`pot_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`
		from `potentials`
		left join `user` on `user`.`iduser` = `potentials`.`iduser`
		left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
		left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
		where `potentials`.`deleted` = 0 
		".$security_where."
		group by `potentials`.`sales_stage`";
		$this->query($qry);
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$result[$this->sales_stage] = $this->total_amount;
			}
		}
		return $result ;
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