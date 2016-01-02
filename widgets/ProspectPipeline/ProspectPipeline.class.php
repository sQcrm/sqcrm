<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ProspectPipeline 
* @author Abhik Chakraborty
*/ 
	

class ProspectPipeline extends DashboardWidgetProcessor {
	public $table = "";
	public $primary_key = "";

	function __construct() {
		$this->set_widget_title(_('Prospect Pipeline By Sales Stage'));
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
    
}