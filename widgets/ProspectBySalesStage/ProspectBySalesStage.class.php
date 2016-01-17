<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ProspectBySalesStage 
* @author Abhik Chakraborty
*/ 
	

class ProspectBySalesStage extends DashboardWidgetProcessor {
	public $table = "";
	public $primary_key = "";

	function __construct() {
		$this->set_widget_title(_('Prospects By Sales Stage'));
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
    
}