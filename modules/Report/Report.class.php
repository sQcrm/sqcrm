<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Report 
* @author Abhik Chakraborty
*/ 
	

class Report extends DataObject {
	public $table = "report";
	public $primary_key = "idreport";
	
	//-- holds the report fields
	protected $report_fields = array();
	
	//--holds order by
	protected $order_by = '';
	
	//-- holds adv filter
	protected $report_adv_filter = '';
	
	//-- holds the adv filter param values for the query
	protected $report_adv_filter_params = array();
	
	//-- holds the report date filter
	protected $report_date_filter = '';
	
	//-- holds report modules
	protected $report_modules = array();
	
	//-- holds if report query has a group by clause
	protected $report_group_by_clause = '';
	
	public $popup_selection_return_field = "name";
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
	
	/**
	* function to set the report fields
	* @param array $fields
	*/
	public function set_report_fields($fields) {
		$this->report_fields = $fields;
	}

	/**
	* function to get the report fields
	* @return array report_fields
	*/
	public function get_report_fields() {
		return $this->report_fields ;
	}
	
	/**
	* function to set the report order by
	* @param array $order_by
	*/
	public function set_report_order_by($order_by) {
		$this->order_by = $order_by ;
	}
	
	/**
	* function to get report order by
	* @return array order_by
	*/
	public function get_report_order_by() {
		return $this->order_by ;
	}
	
	/**
	* function to set the report filter
	* @param array $report_filter
	*/
	public function set_report_adv_filter($report_filter) {
		$this->report_adv_filter = $report_filter ;
	}
	
	/**
	* function to get the report filter
	* @return report_filter
	*/
	public function get_report_adv_filter() {
		return $this->report_adv_filter ;
	}
	
	/**
	* set advance filter options
	* @param array $param
	*/
	public function set_report_adv_filter_params($param) {
		$this->report_adv_filter_params = $param ;
	}
	
	/**
	* get the report advanced filter param
	* @return array
	*/
	public function get_report_adv_filter_params() {
		return $this->report_adv_filter_params ;
	}
	
	/**
	* set report module
	* @param array $report_modules
	*/
	public function set_report_modules($report_modules) {
		$this->report_modules = $report_modules;
	}
	
	/**
	* get report modules
	* @return array
	*/
	public function get_report_modules() {	
		return $this->report_modules;
	}
	
	/**
	* set report date filter
	* @param array $date_filter
	*/
	public function set_report_date_filter($date_filter) {
		$this->report_date_filter = $date_filter ;
	}
	
	/**
	* function get report date filter
	* @return array
	*/
	public function get_report_date_filter() {
		return $this->report_date_filter ; 
	}
	
	/**
	* function to set the report group by clause
	* @param string $group_by 
	*/
	public function set_report_group_by_clause($group_by) {
		$this->report_group_by_clause = $group_by ;
	}
	
	/**
	* function to get the report group by clause
	* @return string report_group_by_clause
	*/
	public function get_report_group_by_clause() {
		return $this->report_group_by_clause ;
	}
	
	/**
	* over-riding the DataObject getId() method
	* @param integer $id
	*/
	public function getId($id) {
		$qry = "select * from `".$this->getTable()."`  where idreport = ? ";
		$this->query($qry,array($id));
		$this->next();
	}
	
	/**
	* get reports by folder id
	* @param integer $idfolder
	* @return array
	*/
	public function get_reports_by_folder($idfolder) {
		$return_array = array();
		$qry = "select * from `".$this->getTable()."` where `idreport_folder` = ? and `deleted` =  0";
		$this->query($qry,array($idfolder));
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[] = array(
					"idreport"=>$this->idreport,
					"name"=>$this->name,
					"description"=>$this->description,
					"iduser"=>$this->iduser
				);
			}
		}
		return $return_array;
	}
	
	/**
	* event function to set the form data step wise on add/edit
	* sets the values in $_SESSION
	* @param object $evctl
	*/
	public function eventSetReportData(EventControler $evctl) {
		$step = (int)$evctl->step ;
		switch ($step) {
			case 1 :
				$next_step = $step+1; 
				$_SESSION["report_type"] = $evctl->report_type ;
				$_SESSION["primary_module"] = '';
				$_SESSION["secondary_module"] = '';
				$_SESSION["report_fields"] = '';
				$_SESSION["report_fields_data"] = '';
				if ($evctl->mode == "edit") {
					$next_page = NavigationControl::getNavigationLink("Report","edit");
					$dis = new Display($next_page); 
					$dis->addParam("sqrecord",$evctl->sqrecord);
				} else {
					$next_page = NavigationControl::getNavigationLink("Report","add");
					$dis = new Display($next_page); 
				}
				$dis->addParam("step",$next_step);
				$evctl->setDisplayNext($dis);
				break;
			case 2 :
				$next_step = $step+1; 
				$_SESSION["primary_module"] = $evctl->primary_module ;
				$_SESSION["secondary_module"] = '';
				$_SESSION["report_fields"] = '';
				$_SESSION["report_fields_data"] = '';
				if ($evctl->mode == "edit") {
					$next_page = NavigationControl::getNavigationLink("Report","edit");
					$dis = new Display($next_page); 
					$dis->addParam("sqrecord",$evctl->sqrecord);
				} else {
					$next_page = NavigationControl::getNavigationLink("Report","add");
					$dis = new Display($next_page); 
				}
				$dis->addParam("step",$next_step);
				$evctl->setDisplayNext($dis);
				break;
			case 3 :
				$next_step = $step+1;
				$_SESSION["secondary_module"] = $evctl->secondary_module ;
				$_SESSION["report_fields"] = '';
				$_SESSION["report_fields_data"] = '';
				if ($evctl->mode == "edit") {
					$next_page = NavigationControl::getNavigationLink("Report","edit");
					$dis = new Display($next_page); 
					$dis->addParam("sqrecord",$evctl->sqrecord);
				} else {
					$next_page = NavigationControl::getNavigationLink("Report","add");
					$dis = new Display($next_page); 
				}
				$dis->addParam("step",$next_step);
				$evctl->setDisplayNext($dis);
				break;
			case 4 :
				$next_step = $step+1;
				$_SESSION["report_fields"] = $evctl->report_fields ;
				$_SESSION["report_order_by"] = '';
				if ($evctl->mode == "edit") {
					$next_page = NavigationControl::getNavigationLink("Report","edit");
					$dis = new Display($next_page); 
					$dis->addParam("sqrecord",$evctl->sqrecord);
				} else {
					$next_page = NavigationControl::getNavigationLink("Report","add");
					$dis = new Display($next_page); 
				}
				$dis->addParam("step",$next_step);
				$evctl->setDisplayNext($dis);
				break;
			case 5 :
				$next_step = $step+1;
				$report_order_by = array(
					"order_by_1"=>array("order_by_field"=>$evctl->report_order_by_1,"order_by_type"=>$evctl->report_order_by_type_1),
					"order_by_2"=>array("order_by_field"=>$evctl->report_order_by_2,"order_by_type"=>$evctl->report_order_by_type_2),
					"order_by_3"=>array("order_by_field"=>$evctl->report_order_by_3,"order_by_type"=>$evctl->report_order_by_type_3)
				);
				$_SESSION["report_order_by"] = $report_order_by ;
				if ($evctl->mode == "edit") {
					$next_page = NavigationControl::getNavigationLink("Report","edit");
					$dis = new Display($next_page); 
					$dis->addParam("sqrecord",$evctl->sqrecord);
				} else {
					$next_page = NavigationControl::getNavigationLink("Report","add");
					$dis = new Display($next_page); 
				}
				$dis->addParam("step",$next_step);
				$evctl->setDisplayNext($dis);
				break;
			case 6:
				$next_step = $step+1;
				$report_filter_options = array(
					"date_filter_options"=>array(
						"report_date_field"=>$evctl->report_date_field,
						"report_date_field_type"=>$evctl->report_date_field_type,
						"report_date_start"=>FieldType9::convert_before_save($evctl->report_date_start),
						"report_date_end"=>FieldType9::convert_before_save($evctl->report_date_end)
					),
					"advanced_filter_options"=>array(
						"report_adv_fields_1"=>$evctl->report_adv_fields_1,
						"report_adv_fields_type_1"=>$evctl->report_adv_fields_type_1,
						"report_adv_fields_val_1"=>$_POST["report_adv_fields_val_1"],
						"report_adv_fields_2"=>$evctl->report_adv_fields_2,
						"report_adv_fields_type_2"=>$evctl->report_adv_fields_type_2,
						"report_adv_fields_val_2"=>$_POST["report_adv_fields_val_2"],
						"report_adv_fields_3"=>$evctl->report_adv_fields_3,
						"report_adv_fields_type_3"=>$evctl->report_adv_fields_type_3,
						"report_adv_fields_val_3"=>$_POST["report_adv_fields_val_3"],
						"report_adv_fields_4"=>$evctl->report_adv_fields_4,
						"report_adv_fields_type_4"=>$evctl->report_adv_fields_type_4,
						"report_adv_fields_val_4"=>$_POST["report_adv_fields_val_4"],
						"report_adv_fields_5"=>$evctl->report_adv_fields_5,
						"report_adv_fields_type_5"=>$evctl->report_adv_fields_type_5,
						"report_adv_fields_val_5"=>$_POST["report_adv_fields_val_5"]
					)
				);
				
				$_SESSION["report_filter"] = $report_filter_options ;
				if ($evctl->mode == "edit") {
					$next_page = NavigationControl::getNavigationLink("Report","edit");
					$dis = new Display($next_page); 
					$dis->addParam("sqrecord",$evctl->sqrecord);
				} else {
					$next_page = NavigationControl::getNavigationLink("Report","add");
					$dis = new Display($next_page); 
				}
				$dis->addParam("step",$next_step);
				$evctl->setDisplayNext($dis);
				break;
		}
	}
	
	/**
	* event function save/update 
	* @param object $evctl
	*/
	public function eventSaveReport(EventControler $evctl) {
		if ($evctl->name == '') {
			$_SESSION["do_crm_messages"]->set_message('error',_('Please add a report name before saving !'));
		} else {
			if ($evctl->mode == 'add') {
				$idreport = $this->save_report($evctl);
			} else {
				$idreport = $this->update_report($evctl);
			}
			$next_page = NavigationControl::getNavigationLink("Report","run_report");
			$dis = new Display($next_page); 
			$dis->addParam("sqrecord",$idreport);
			$evctl->setDisplayNext($dis);
		}
	}
	
	/**
	* function save report data
	* @param object $evctl
	* @return integer
	*/
	public function save_report($evctl) {
		$this->addNew();
		$this->name = $evctl->name;
		$this->description = $evctl->description;
		$this->idreport_folder = $evctl->idreport_folder;
		$this->iduser = $_SESSION["do_user"]->iduser;
		$this->report_type = $_SESSION["report_type"];
		$this->add();
		$idreport = $this->getInsertId();
		// add report module rel
		$do_report_module_rel = new ReportModuleRel();
		$do_report_module_rel->add_report_module_rel($idreport,$_SESSION["primary_module"],$_SESSION["secondary_module"]);
		// add report fields
		$do_report_fields = new ReportFields();
		$do_report_fields->add_report_fields($idreport,$_SESSION["report_fields"]);
		// add report sorting
		$do_report_sorting = new ReportSorting();
		$do_report_sorting->add_report_sort_fields($idreport,$_SESSION["report_order_by"]);
		// add report filter
		$do_report_filter = new ReportFilter();
		$do_report_filter->add_report_filter($idreport,$_SESSION["report_filter"]);
		return $idreport ;
	}
	
	/**
	* function update report data
	* @param object $evctl
	* @return integer
	*/
	public function update_report($evctl) {
		$idreport = $evctl->sqrecord ; 
		$qry = "
		update `".$this->getTable()."`
		set `name` = ?,
		`description` = ?,
		`idreport_folder` = ?,
		`report_type` = ?
		where `idreport` = ?
		";
		$this->query($qry,array($evctl->name,$evctl->description,$evctl->idreport_folder,$_SESSION["report_type"],$idreport));
		$do_report_module_rel = new ReportModuleRel();
		$do_report_module_rel->update_report_module_rel($idreport,$_SESSION["primary_module"],$_SESSION["secondary_module"]);
		$do_report_fields = new ReportFields();
		$do_report_fields->update_report_fields($idreport,$_SESSION["report_fields"]);
		$do_report_sorting = new ReportSorting();
		$do_report_sorting->update_report_sort_fields($idreport,$_SESSION["report_order_by"]);
		$do_report_filter = new ReportFilter();
		$do_report_filter->update_report_filter($idreport,$_SESSION["report_filter"]);
		return $idreport;
	}
	
	/**
	* function to execute the report
	* @param integer $idreport
	*/
	public function execute_report($idreport) {
		$this->getId($idreport);
		return $this->get_report_query($this->report_type);
	}
	
	/**
	* parse select fields for the report query
	* @param array $fields
	* @param integer $report_type
	* @param array $report_modules
	* @return string
	*/
	public function parse_select_fields($fields,$report_type=1,$report_modules=array()) {
		if (count($report_modules) == 0 ) $report_modules = $this->get_report_modules();
		$select = "select ";
		foreach ($fields as $id=>$info) {
			if ($info["field_type"] == 130) {
				if ($info["field_name"] == 'reports_to') {
					$select .= " concat(`cnt2`.`firstname`,' ',`cnt2`.`lastname`) as `contact_report_to`,";
				}
			} elseif ($info["field_type"] == 131) {
				if ($info["field_name"] == 'idorganization') {
					if ($info["idmodule"] == $report_modules["primary"]["idmodule"]) {
						$select .= "`organization`.`organization_name` as `org_name`,";
					} else {
						$select .= "`organization`.`organization_name` as `".$report_modules["secondary"]["module_name"]."_org_name`,";
					}
				} elseif ($info["field_name"] == 'member_of') {
					$select .= "`org2`.`organization_name` as `organization_member_of`,";
				}
			} elseif ($info["field_type"] == 133) {
				if ($info["idmodule"] == $report_modules["primary"]["idmodule"]) {
					$select .="
					`potentials`.`potential_name` as `".$report_modules["primary"]["module_name"]."_potential_name_133`,
					";
				} else {
					$select .="
					`potentials1`.`potential_name` as `".$report_modules["secondary"]["module_name"]."_potential_name_133`,
					";
				}
			} elseif ($info["field_type"] == 150) {
				if ($info["idmodule"] == $report_modules["primary"]["idmodule"] || $report_modules["primary"]["idmodule"] == 2) {
					$select .= "
					case 
					when potentials_related_to.related_to not like ''
					Then
					(
						case 
						when sqorg.organization_name not like '' then sqorg.organization_name
						when concat(sqcnt.firstname,' ',sqcnt.lastname) not like '' then concat(sqcnt.firstname,' ',sqcnt.lastname)
						end
					)
					else ''
					end
					as `potentials_related_to_value`, 
					`potentials_related_to`.`idmodule` as `potentials_related_to_idmodule`,";
				}else{
					if($report_modules["primary"]["idmodule"] == 4){ // contacts
						$select .= "
						concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `potentials_related_to_value`,
						'4' as `potentials_related_to_idmodule`,
						";
					}elseif($report_modules["primary"]["idmodule"] == 6){ // organization
						$select .= "`organization`.`organization_name` as `potentials_related_to_value`, '6' as `potentials_related_to_idmodule`";
					}else{
						$select .= "
						case 
						when potentials_related_to.related_to not like ''
						Then
						(
							case 
							when sqorg.organization_name not like '' then sqorg.organization_name
							when concat(sqcnt.firstname,' ',sqcnt.lastname) not like '' then concat(sqcnt.firstname,' ',sqcnt.lastname)
							end
						)
						else ''
						end
						as `potentials_related_to_value`, 
						`potentials_related_to`.`idmodule` as `potentials_related_to_idmodule`,";
					}
				}
			} elseif ($info["field_type"] == 151) {
				if ($info["idmodule"] == $report_modules["primary"]["idmodule"]) {
					$select .= "
					case 
					when events_related_to.related_to not like ''
					Then
					(
						case 
						when sqorg.organization_name not like '' then sqorg.organization_name
						when concat(sqcnt.firstname,' ',sqcnt.lastname) not like '' then concat(sqcnt.firstname,' ',sqcnt.lastname)
						when concat(sqleads.firstname,' ',sqleads.lastname) not like '' then concat(sqleads.firstname,' ',sqleads.lastname)
						when sqpot.potential_name not like '' then sqpot.potential_name
						end
					)
					else ''
					end
					as `events_related_to_value`,
					`events_related_to`.idmodule as `events_related_to_idmodule`,";
				}
			} elseif ($info["field_type"] == 15) {
				if ($info["idmodule"] == $report_modules["primary"]["idmodule"]) {
					continue;
				} else {
					$select .= "
					case when (`user1`.`user_name` not like '')
					then
					`user1`.`user_name` 
					else
					`group1`.`group_name` end
					as `".$report_modules["secondary"]["module_name"]."_assigned_to`, ";
				}
			} elseif ($info["field_type"] == 165) {
				$select .="
				group_concat(concat(products_tax.tax_name,'::',products_tax.tax_value)) as product_tax_values,
				";
			} elseif ($info["field_type"] == 160) {
				if ($report_modules["primary"]["idmodule"] == 16 && $report_modules["secondary"]["idmodule"] == 12) {
					$select .="
					`vendor1`.`vendor_name` as `product_vendor_name`,
					";
				} else {
					$select .="
					`vendor`.`vendor_name` as `vendor_name`,
					";
				}
			} elseif ($info["field_type"] == 142) {
				if ($info["idmodule"] == $report_modules["primary"]["idmodule"]) {
					$select .="
					concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `".$report_modules["primary"]["module_name"]."_cnt_name_142`,
					";
				} else {
					$select .="
					concat(`contacts1`.`firstname`,' ',`contacts1`.`lastname`) as `".$report_modules["secondary"]["module_name"]."_cnt_name_142`,
					";
				}
			} elseif ($info["field_type"] == 143) {
				$select .="
				concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `contact_name`,	
				";
			} elseif ($info["field_type"] == 141) {
				if ($info["idmodule"] == $report_modules["primary"]["idmodule"]) {
					$select .="
					`organization`.`organization_name` as `".$report_modules["primary"]["module_name"]."_org_name_141`,
					";
				} else {
					$select .="
					`organization1`.`organization_name` as `".$report_modules["secondary"]["module_name"]."_org_name_141`,
					";
				}
			} else {
				if ($info["idmodule"] == $report_modules["primary"]["idmodule"]) {
					$select .= "`".$info['table_name']."`.`".$info['field_name']."` ,";
				} else {
					$select .= "
					`".$info['table_name']."`.`".$info['field_name']."` 
					as 	".$report_modules["secondary"]["module_name"]."_".$info['field_name'].",";
				}
			}
		}
		$select .= "
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to` ";
		return $select;
	}
	
	/**
	* get report query
	* @param integer $report_type
	* @param integer $idreport
	* @return string
	*/
	public function get_report_query($report_type=1,$idreport=0) {
		if ($idreport > 0) {
			$this->getId($idreport);
			$report_type = $this->report_type ;
		}
		$report_modules = $this->get_report_modules();
		$report_fields = $this->get_report_fields();
		$select = $this->parse_select_fields($report_fields,$report_type,$report_modules);
		$order_by = $this->get_report_order_by();
		$qry_primary_module = $this->get_report_query_primary_module($report_modules);
		$security_where = '';
		$where = '';
		$group_by = '';
		$primary_module_obj = new $report_modules["primary"]["module_name"]();
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition($primary_module_obj->getTable(),$report_modules["primary"]["idmodule"]);
		$where = " where `".$primary_module_obj->getTable()."`.`deleted` = 0 ".$security_where;
		$date_filter = $this->get_report_date_filter();
		$adv_filter = $this->get_report_adv_filter();
		$group_by = $this->get_report_group_by_clause() ;
		$query = $select.$qry_primary_module.$where.$date_filter.$adv_filter.$group_by.$order_by ;
		return $query ;
	}
	
	public function run_report() {
		echo $this->parse_select_fields($this->execute_report());
	}
  
	/**
	* get report join part between primary and secondary modules
	* @param array $report_modules
	* @return string
	*/
	public function get_report_query_primary_module($report_modules=array()) {
		if (count($report_modules) == 0 ) $report_modules = $this->get_report_modules();
		$qry = "";
		switch ($report_modules["primary"]["module_name"]) {
			case "Leads":
				$qry = "
				from `leads`
				inner join `leads_address` on `leads_address`.`idleads` = `leads`.`idleads`
				inner join `leads_custom_fld` on `leads_custom_fld`.`idleads` = `leads`.`idleads`
				left join `user` on `user`.`iduser` = `leads`.`iduser`
				left join `leads_to_grp_rel` on `leads_to_grp_rel`.`idleads` = `leads`.`idleads`
				left join `group` on `group`.`idgroup` = `leads_to_grp_rel`.`idgroup`" ;
				break;
				
			case "Contacts" :
				$qry = "
				from `contacts` 
				inner join `contacts_address` on `contacts_address`.`idcontacts` = `contacts`.`idcontacts`
				inner join `contacts_custom_fld` on `contacts_custom_fld`.`idcontacts` = `contacts`.`idcontacts`
				left join `user` on `user`.`iduser` = `contacts`.`iduser`
				left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
				left join `group` on `group`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
				left join `organization` on `organization`.`idorganization` = `contacts`.`idorganization`
				left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` 
				AND `contacts`.`reports_to` <> 0
				" ;
				if ($report_modules["secondary"]["module_name"] == 'Organization') {
					$qry .= "	
					inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
					inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
					left join `user` `user1` on `user1`.`iduser` = `organization`.`iduser`
					left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
					left join `group` `group1` on `group1`.`idgroup` = `org_to_grp_rel`.`idgroup`
					left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` 
					AND `organization`.`member_of` <> 0
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Potentials') {
					$qry .= "	
					inner join `potentials_related_to` on `potentials_related_to`.`related_to` = `contacts`.`idcontacts` 
					AND `potentials_related_to`.`idmodule` = 4
					inner join `potentials` on `potentials`.`idpotentials` = `potentials_related_to`.`idpotentials`
					inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
					left join `user` `user1` on `user1`.`iduser` = `potentials`.`iduser`
					left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
					left join `group` `group1` on `group1`.`idgroup` = `pot_to_grp_rel`.`idgroup`
					";
				}
				break;
				
			case "Organization" :
				$qry = "
				from `organization`
				inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
				inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
				left join `user` on `user`.`iduser` = `organization`.`iduser`
				left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
				left join `group` on `group`.`idgroup` = `org_to_grp_rel`.`idgroup`
				left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` 
				AND `organization`.`member_of` <> 0
				" ;
				if ($report_modules["secondary"]["module_name"] == 'Potentials') {
					$qry .= "	
					inner join `potentials_related_to` on `potentials_related_to`.`related_to` = `organization`.`idorganization` 
					AND `potentials_related_to`.`idmodule` = 6
					inner join `potentials` on `potentials`.`idpotentials` = `potentials_related_to`.`idpotentials`
					left join `user` `user1` on `user1`.`iduser` = `potentials`.`iduser`
					left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
					left join `group` `group1` on `group1`.`idgroup` = `pot_to_grp_rel`.`idgroup`
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Contacts') {
					$qry .= "	
					inner join `contacts` on `contacts`.`idorganization` = `organization`.`idorganization`
					left join `user` `user1` on `user1`.`iduser` = `contacts`.`iduser`
					left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
					left join `group` `group1` on `group1`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
					left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` 
					AND `contacts`.`reports_to` <> 0
					";
				}
				break;
				
			case "Potentials" :
				if ($report_modules["secondary"]["module_name"] == 'Contacts') {
					$qry = "
					from `potentials`
					inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
					left join `user` on `user`.`iduser` = `potentials`.`iduser`
					left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
					left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
					left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
					inner join `contacts` on `contacts`.`idcontacts` = `potentials_related_to`.`related_to` 
					and `potentials_related_to`.`idmodule` = 4
					left join `user` `user1` on `user1`.`iduser` = `contacts`.`iduser`
					left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
					left join `group` `group1` on `group1`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
					left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` 
					left join `organization` on `organization`.`idorganization` = `contacts`.`idorganization`
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Organization') {
					$qry = "
					from `potentials`
					inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
					left join `user` on `user`.`iduser` = `potentials`.`iduser`
					left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
					left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
					left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
					inner join `organization` on `organization`.`idorganization` = `potentials_related_to`.`related_to` 
					and `potentials_related_to`.`idmodule` = 6
					left join `user` `user1` on `user1`.`iduser` = `organization`.`iduser`
					left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
					left join `group` `group1` on `group1`.`idgroup` = `org_to_grp_rel`.`idgroup`
					left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` 
					AND `organization`.`member_of` <> 0
					";
				} else {
					$qry = "
					from `potentials`
					inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
					left join `user` on `user`.`iduser` = `potentials`.`iduser`
					left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
					left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
					left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
					left join `contacts` as sqcnt on sqcnt.idcontacts = `potentials_related_to`.`related_to` 
					and `potentials_related_to`.`idmodule` = 4
					left join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` 
					and `potentials_related_to`.`idmodule` = 6
					";
				}
				break;
				
			case "Calendar" :
				if ($report_modules["secondary"]["module_name"] == 'Leads') {
					$qry = "
					from `events`
					inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
					left join `user` on `user`.`iduser` = `events`.`iduser`
					left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
					left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
					inner join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
					inner join `leads` on `leads`.`idleads` = `events_related_to`.`related_to` 
					and `events_related_to`.`idmodule` =3
					inner join `leads_address` on `leads_address`.`idleads` = `leads`.`idleads`
					inner join `leads_custom_fld` on `leads_custom_fld`.`idleads` = `leads`.`idleads`
					left join `user` `user1` on `user1`.`iduser` = `leads`.`iduser`
					left join `leads_to_grp_rel` on `leads_to_grp_rel`.`idleads` = `leads`.`idleads`
					left join `group` `group1` on `group1`.`idgroup` = `leads_to_grp_rel`.`idgroup`
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Contacts') {
					$qry = "
					from `events`
					inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
					left join `user` on `user`.`iduser` = `events`.`iduser`
					left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
					left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
					inner join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
					inner join `contacts` on `contacts`.`idcontacts` = `events_related_to`.`related_to` 
					and `events_related_to`.`idmodule` = 4
					left join `organization` on `organization`.`idorganization` = `contacts`.`idorganization`
					left join `user` `user1` on `user1`.`iduser` = `contacts`.`iduser`
					left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
					left join `group` `group1` on `group1`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
					left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` 
					AND `contacts`.`reports_to` <> 0
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Organization') {
					$qry = "
					from `events`
					inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
					left join `user` on `user`.`iduser` = `events`.`iduser`
					left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
					left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
					inner join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
					inner join `organization` on `organization`.`idorganization` = `events_related_to`.`related_to` 
					and `events_related_to`.`idmodule` = 6
					left join `user` `user1` on `user1`.`iduser` = `organization`.`iduser`
					left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
					left join `group` `group1` on `group1`.`idgroup` = `org_to_grp_rel`.`idgroup`
					left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` 
					AND `organization`.`member_of` <> 0
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Potentials') {
					$qry = "
					from `events`
					inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
					left join `user` on `user`.`iduser` = `events`.`iduser`
					left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
					left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
					inner join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
					inner join `potentials` on `potentials`.`idpotentials` = `events_related_to`.`related_to` 
					and `events_related_to`.`idmodule` = 5
					left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
					left join `contacts` as sqcnt on sqcnt.idcontacts = `potentials_related_to`.`related_to` 
					and `potentials_related_to`.`idmodule` = 4
					left join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` 
					and `potentials_related_to`.`idmodule` = 6
					";
				} else {
					$qry = "
					from `events`
					inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
					left join `user` on `user`.`iduser` = `events`.`iduser`
					left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
					left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
					left join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
					left join `leads` as sqleads on sqleads.idleads = `events_related_to`.`related_to` 
					and  `events_related_to`.`idmodule` =3
					left join `contacts` as sqcnt on sqcnt.idcontacts = `events_related_to`.`related_to` 
					and `events_related_to`.`idmodule` = 4
					left join `organization` as sqorg on sqorg.idorganization = `events_related_to`.`related_to` 
					and `events_related_to`.`idmodule` = 6
					left join `potentials` `sqpot` on `sqpot`.`idpotentials` = `events_related_to`.`related_to` 
					and `events_related_to`.`idmodule` = 5";
				}
				break;
				
			case "Vendor" :
				if ($report_modules["secondary"]["module_name"] == 'Products') {
					$qry = "
					from `products`
					inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
					inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
					inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
					left join `user` on `user`.`iduser` = `products`.`iduser`
					left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
					inner join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
					left join `group` on `group`.`idgroup` = `products_to_grp_rel`.`idgroup`
					left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
					inner join `vendor_address` on `vendor_address`.`idvendor` = `vendor`.`idvendor`
					inner join `vendor_custom_fld` on `vendor_custom_fld`.`idvendor` = `vendor`.`idvendor`
					left join `user` `user1` on `user1`.`iduser` = `vendor`.`iduser`
					left join `vendor_to_grp_rel` on `vendor_to_grp_rel`.`idvendor` = `vendor`.`idvendor`
					left join `group` `group1` on `group1`.`idgroup` = `vendor_to_grp_rel`.`idgroup`
					";
					//--set the group by since it would be needed for product 
					$this->set_report_group_by_clause(' group by `products`.`idproducts`');
				} elseif ($report_modules["secondary"]["module_name"] == 'PurchaseOrder') {
					$qry = "
					from `vendor`
					inner join `vendor_address` on `vendor_address`.`idvendor` = `vendor`.`idvendor`
					inner join `vendor_custom_fld` on `vendor_custom_fld`.`idvendor` = `vendor`.`idvendor`
					left join `user` on `user`.`iduser` = `vendor`.`iduser`
					left join `vendor_to_grp_rel` on `vendor_to_grp_rel`.`idvendor` = `vendor`.`idvendor`
					left join `group` on `group`.`idgroup` = `vendor_to_grp_rel`.`idgroup`
					inner join `purchase_order` on `purchase_order`.`idvendor` = `vendor`.`idvendor`
					inner join `purchase_order_address` on `purchase_order_address`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					inner join `purchase_order_custom_fld` on `purchase_order_custom_fld`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					left join `user` `user1` on `user1`.`iduser` = `purchase_order`.`iduser`
					left join `purchase_order_to_grp_rel` on `purchase_order_to_grp_rel`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					left join `group` `group1` on `group1`.`idgroup` = `purchase_order_to_grp_rel`.`idgroup`
					left join `contacts` on `contacts`.`idcontacts` = `purchase_order`.`idcontacts`
					";
				} else {
					$qry = "
					from `vendor`
					inner join `vendor_address` on `vendor_address`.`idvendor` = `vendor`.`idvendor`
					inner join `vendor_custom_fld` on `vendor_custom_fld`.`idvendor` = `vendor`.`idvendor`
					left join `user` on `user`.`iduser` = `vendor`.`iduser`
					left join `vendor_to_grp_rel` on `vendor_to_grp_rel`.`idvendor` = `vendor`.`idvendor`
					left join `group` on `group`.`idgroup` = `vendor_to_grp_rel`.`idgroup`
					";
				}
				break;
					
			case "Products":
				if ($report_modules["secondary"]["module_name"] == 'Vendor') {
					$qry = "
					from `products`
					inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
					inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
					inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
					left join `user` on `user`.`iduser` = `products`.`iduser`
					left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
					inner join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
					left join `group` on `group`.`idgroup` = `products_to_grp_rel`.`idgroup`
					left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
					inner join `vendor_address` on `vendor_address`.`idvendor` = `vendor`.`idvendor`
					inner join `vendor_custom_fld` on `vendor_custom_fld`.`idvendor` = `vendor`.`idvendor`
					left join `user` `user1` on `user1`.`iduser` = `vendor`.`iduser`
					left join `vendor_to_grp_rel` on `vendor_to_grp_rel`.`idvendor` = `vendor`.`idvendor`
					left join `group` `group1` on `group1`.`idgroup` = `vendor_to_grp_rel`.`idgroup`
					";
					//--set the group by since it would be needed for product 
					$this->set_report_group_by_clause(' group by `products`.`idproducts`');
				} else {
					$qry = "
					from `products`
					inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
					inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
					inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
					left join `user` on `user`.`iduser` = `products`.`iduser`
					left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
					left join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
					left join `group` on `group`.`idgroup` = `products_to_grp_rel`.`idgroup`
					left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
					";
					//--set the group by since it would be needed for product 
					$this->set_report_group_by_clause(' group by `products`.`idproducts`');
				}
				break ;
						
			case "PurchaseOrder":
				if ($report_modules["secondary"]["module_name"] == 'Vendor') {
					$qry="
					from `purchase_order`
					inner join `purchase_order_address` on `purchase_order_address`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					inner join `purchase_order_custom_fld` on `purchase_order_custom_fld`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					left join `user` on `user`.`iduser` = `purchase_order`.`iduser`
					left join `purchase_order_to_grp_rel` on `purchase_order_to_grp_rel`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					inner join `vendor` on `vendor`.`idvendor` = `purchase_order`.`idvendor`
					left join `group` on `group`.`idgroup` = `purchase_order_to_grp_rel`.`idgroup`
					left join `contacts` on `contacts`.`idcontacts` = `purchase_order`.`idcontacts`
					inner join `vendor_address` on `vendor_address`.`idvendor` = `vendor`.`idvendor`
					inner join `vendor_custom_fld` on `vendor_custom_fld`.`idvendor` = `vendor`.`idvendor`
					left join `user` `user1` on `user1`.`iduser` = `vendor`.`iduser`
					left join `vendor_to_grp_rel` on `vendor_to_grp_rel`.`idvendor` = `vendor`.`idvendor`
					left join `group` `group1` on `group1`.`idgroup` = `vendor_to_grp_rel`.`idgroup`
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Products') {
					$qry="
					from `purchase_order`
					inner join `purchase_order_address` on `purchase_order_address`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					inner join `purchase_order_custom_fld` on `purchase_order_custom_fld`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					left join `user` on `user`.`iduser` = `purchase_order`.`iduser`
					left join `purchase_order_to_grp_rel` on `purchase_order_to_grp_rel`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					left join `vendor` on `vendor`.`idvendor` = `purchase_order`.`idvendor`
					left join `group` on `group`.`idgroup` = `purchase_order_to_grp_rel`.`idgroup`
					left join `contacts` on `contacts`.`idcontacts` = `purchase_order`.`idcontacts`
					inner join `lineitems` on `lineitems`.`recordid` = `purchase_order`.`idpurchase_order` 
					and `lineitems`.`idmodule` = 16 and `lineitems`.`item_type` = 'product'
					inner join `products` on `products`.`idproducts` = `lineitems`.`item_value`
					inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
					inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
					inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
					left join `user` `user1` on `user1`.`iduser` = `products`.`iduser`
					left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
					left join `vendor` `vendor1` on `vendor1`.`idvendor` = `products`.`idvendor`
					left join `group` `group1` on `group1`.`idgroup` = `products_to_grp_rel`.`idgroup`
					left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
					";
					//--set the group by since it would be needed for product 
					$this->set_report_group_by_clause(' group by `lineitems`.`idlineitems`');
				} else {
					$qry="
					from `purchase_order`
					inner join `purchase_order_address` on `purchase_order_address`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					inner join `purchase_order_custom_fld` on `purchase_order_custom_fld`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					left join `user` on `user`.`iduser` = `purchase_order`.`iduser`
					left join `purchase_order_to_grp_rel` on `purchase_order_to_grp_rel`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
					left join `vendor` on `vendor`.`idvendor` = `purchase_order`.`idvendor`
					left join `group` on `group`.`idgroup` = `purchase_order_to_grp_rel`.`idgroup`
					left join `contacts` on `contacts`.`idcontacts` = `purchase_order`.`idcontacts`
					";
				}
				break ;
							
			case "Quotes":
				if ($report_modules["secondary"]["module_name"] == 'Potentials') {
					$qry="
					from `quotes`
					inner join `quotes_address` on `quotes_address`.`idquotes` = `quotes`.`idquotes`
					inner join `quotes_custom_fld` on `quotes_custom_fld`.`idquotes` = `quotes`.`idquotes`
					left join `user` on `user`.`iduser` = `quotes`.`iduser`
					left join `quotes_to_grp_rel` on `quotes_to_grp_rel`.`idquotes` = `quotes`.`idquotes`
					left join `organization` on `organization`.`idorganization` = `quotes`.`idorganization`
					left join `group` on `group`.`idgroup` = `quotes_to_grp_rel`.`idgroup`
					inner join `potentials` on `potentials`.`idpotentials` = `quotes`.`idpotentials`
					inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
					left join `user` `user1` on `user1`.`iduser` = `potentials`.`iduser`
					left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
					left join `group` `group1` on `group1`.`idgroup` = `pot_to_grp_rel`.`idgroup`
					left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
					left join `contacts` as sqcnt on sqcnt.idcontacts = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 4
					left join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 6
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Organization') {
					$qry="
					from `quotes`
					inner join `quotes_address` on `quotes_address`.`idquotes` = `quotes`.`idquotes`
					inner join `quotes_custom_fld` on `quotes_custom_fld`.`idquotes` = `quotes`.`idquotes`
					left join `user` on `user`.`iduser` = `quotes`.`iduser`
					left join `quotes_to_grp_rel` on `quotes_to_grp_rel`.`idquotes` = `quotes`.`idquotes`
					inner join `organization` on `organization`.`idorganization` = `quotes`.`idorganization`
					left join `group` on `group`.`idgroup` = `quotes_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `quotes`.`idpotentials`
					inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
					inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
					left join `user` `user1` on `user1`.`iduser` = `organization`.`iduser`
					left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
					left join `group` `group1` on `group1`.`idgroup` = `org_to_grp_rel`.`idgroup`
					left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` AND `organization`.`member_of` <> 0
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Products') {
					$qry="
					from `quotes`
					inner join `quotes_address` on `quotes_address`.`idquotes` = `quotes`.`idquotes`
					inner join `quotes_custom_fld` on `quotes_custom_fld`.`idquotes` = `quotes`.`idquotes`
					left join `user` on `user`.`iduser` = `quotes`.`iduser`
					left join `quotes_to_grp_rel` on `quotes_to_grp_rel`.`idquotes` = `quotes`.`idquotes`
					left join `organization` on `organization`.`idorganization` = `quotes`.`idorganization`
					left join `group` on `group`.`idgroup` = `quotes_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `quotes`.`idpotentials`
					inner join `lineitems` on `lineitems`.`recordid` = `quotes`.`idquotes` 
					and `lineitems`.`idmodule` = 13 and `lineitems`.`item_type` = 'product'
					inner join `products` on `products`.`idproducts` = `lineitems`.`item_value`
					inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
					inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
					inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
					left join `user` `user1` on `user1`.`iduser` = `products`.`iduser`
					left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
					left join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
					left join `group` `group1` on `group1`.`idgroup` = `products_to_grp_rel`.`idgroup`
					left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
					";
					//--set the group by since it would be needed for product 
					$this->set_report_group_by_clause(' group by `lineitems`.`idlineitems`');
				} else {
					$qry="
					from `quotes`
					inner join `quotes_address` on `quotes_address`.`idquotes` = `quotes`.`idquotes`
					inner join `quotes_custom_fld` on `quotes_custom_fld`.`idquotes` = `quotes`.`idquotes`
					left join `user` on `user`.`iduser` = `quotes`.`iduser`
					left join `quotes_to_grp_rel` on `quotes_to_grp_rel`.`idquotes` = `quotes`.`idquotes`
					left join `organization` on `organization`.`idorganization` = `quotes`.`idorganization`
					left join `group` on `group`.`idgroup` = `quotes_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `quotes`.`idpotentials`
					";
				}
				break;
								
			case "SalesOrder":
				if ($report_modules["secondary"]["module_name"] == 'Organization') {
					$qry="
					from `sales_order`
					inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
					inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
					left join `user` on `user`.`iduser` = `sales_order`.`iduser`
					left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
					inner join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
					left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
					left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
					left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
					inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
					inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
					left join `user` `user1` on `user1`.`iduser` = `organization`.`iduser`
					left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
					left join `group` `group1` on `group1`.`idgroup` = `org_to_grp_rel`.`idgroup`
					left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` AND `organization`.`member_of` <> 0
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Potentials') { 
					$qry="
					from `sales_order`
					inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
					inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
					left join `user` on `user`.`iduser` = `sales_order`.`iduser`
					left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
					left join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
					left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
					inner join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
					left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
					left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
					inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
					left join `user` `user1` on `user1`.`iduser` = `potentials`.`iduser`
					left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
					left join `group` `group1` on `group1`.`idgroup` = `pot_to_grp_rel`.`idgroup`
					left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
					left join `contacts` as sqcnt on sqcnt.idcontacts = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 4
					left join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 6
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Contacts') {
					$qry="
					from `sales_order`
					inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
					inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
					left join `user` on `user`.`iduser` = `sales_order`.`iduser`
					left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
					left join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
					left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
					left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
					inner join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
					inner join `contacts_address` on `contacts_address`.`idcontacts` = `contacts`.`idcontacts`
					inner join `contacts_custom_fld` on `contacts_custom_fld`.`idcontacts` = `contacts`.`idcontacts`
					left join `user` `user1` on `user1`.`iduser` = `contacts`.`iduser`
					left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
					left join `group` `group1` on `group1`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
					left join `organization` `organization1` on `organization1`.`idorganization` = `contacts`.`idorganization`
					left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` AND `contacts`.`reports_to` <> 0
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Quotes') {
					$qry="
					from `sales_order`
					inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
					inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
					left join `user` on `user`.`iduser` = `sales_order`.`iduser`
					left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
					left join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
					left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
					inner join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
					left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
					inner join `quotes_address` on `quotes_address`.`idquotes` = `quotes`.`idquotes`
					inner join `quotes_custom_fld` on `quotes_custom_fld`.`idquotes` = `quotes`.`idquotes`
					left join `user` `user1` on `user1`.`iduser` = `quotes`.`iduser`
					left join `quotes_to_grp_rel` on `quotes_to_grp_rel`.`idquotes` = `quotes`.`idquotes`
					left join `organization` `organization1` on `organization1`.`idorganization` = `quotes`.`idorganization`
					left join `group` `group1` on `group1`.`idgroup` = `quotes_to_grp_rel`.`idgroup`
					left join `potentials` `potentials1` on `potentials1`.`idpotentials` = `quotes`.`idpotentials`
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Products') {
					$qry="
					from `sales_order`
					inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
					inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
					left join `user` on `user`.`iduser` = `sales_order`.`iduser`
					left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
					left join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
					left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
					left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
					left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
					inner join `lineitems` on `lineitems`.`recordid` = `sales_order`.`idsales_order` 
					and `lineitems`.`idmodule` = 14 and `lineitems`.`item_type` = 'product'
					inner join `products` on `products`.`idproducts` = `lineitems`.`item_value`
					inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
					inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
					inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
					left join `user` `user1` on `user1`.`iduser` = `products`.`iduser`
					left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
					left join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
					left join `group` `group1` on `group1`.`idgroup` = `products_to_grp_rel`.`idgroup`
					left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
					";
					//--set the group by since it would be needed for product 
					$this->set_report_group_by_clause(' group by `lineitems`.`idlineitems`');
				} else {
					$qry="
					from `sales_order`
					inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
					inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
					left join `user` on `user`.`iduser` = `sales_order`.`iduser`
					left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
					left join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
					left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
					left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
					left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
					";
				}
				break;
			
			case "Invoice":
				if ($report_modules["secondary"]["module_name"] == 'Organization') {
					$qry="
					from `invoice`
					inner join `invoice_address` on `invoice_address`.`idinvoice` = `invoice`.`idinvoice`
					inner join `invoice_custom_fld` on `invoice_custom_fld`.`idinvoice` = `invoice`.`idinvoice`
					left join `user` on `user`.`iduser` = `invoice`.`iduser`
					left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
					inner join `organization` on `organization`.`idorganization` = `invoice`.`idorganization`
					left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `invoice`.`idpotentials`
					left join `sales_order` on `sales_order`.`idsales_order` = `invoice`.`idsales_order`
					left join `contacts` on `contacts`.`idcontacts` = `invoice`.`idcontacts`
					inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
					inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
					left join `user` `user1` on `user1`.`iduser` = `organization`.`iduser`
					left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
					left join `group` `group1` on `group1`.`idgroup` = `org_to_grp_rel`.`idgroup`
					left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` AND `organization`.`member_of` <> 0
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Potentials') {
					$qry="
					from `invoice`
					inner join `invoice_address` on `invoice_address`.`idinvoice` = `invoice`.`idinvoice`
					inner join `invoice_custom_fld` on `invoice_custom_fld`.`idinvoice` = `invoice`.`idinvoice`
					left join `user` on `user`.`iduser` = `invoice`.`iduser`
					left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
					left join `organization` on `organization`.`idorganization` = `invoice`.`idorganization`
					left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
					inner join `potentials` on `potentials`.`idpotentials` = `invoice`.`idpotentials`
					left join `sales_order` on `sales_order`.`idsales_order` = `invoice`.`idsales_order`
					left join `contacts` on `contacts`.`idcontacts` = `invoice`.`idcontacts`
					inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
					left join `user` `user1` on `user1`.`iduser` = `potentials`.`iduser`
					left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
					left join `group` `group1` on `group1`.`idgroup` = `pot_to_grp_rel`.`idgroup`
					left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
					left join `contacts` as sqcnt on sqcnt.idcontacts = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 4
					left join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 6
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Contacts') {
					$qry="
					from `invoice`
					inner join `invoice_address` on `invoice_address`.`idinvoice` = `invoice`.`idinvoice`
					inner join `invoice_custom_fld` on `invoice_custom_fld`.`idinvoice` = `invoice`.`idinvoice`
					left join `user` on `user`.`iduser` = `invoice`.`iduser`
					left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
					left join `organization` on `organization`.`idorganization` = `invoice`.`idorganization`
					left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `invoice`.`idpotentials`
					left join `sales_order` on `sales_order`.`idsales_order` = `invoice`.`idsales_order`
					inner join `contacts` on `contacts`.`idcontacts` = `invoice`.`idcontacts`
					inner join `contacts_address` on `contacts_address`.`idcontacts` = `contacts`.`idcontacts`
					inner join `contacts_custom_fld` on `contacts_custom_fld`.`idcontacts` = `contacts`.`idcontacts`
					left join `user` `user1` on `user1`.`iduser` = `contacts`.`iduser`
					left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
					left join `group` `group1` on `group1`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
					left join `organization` `organization1` on `organization1`.`idorganization` = `contacts`.`idorganization`
					left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` AND `contacts`.`reports_to` <> 0
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'SalesOrder') {
					$qry="
					from `invoice`
					inner join `invoice_address` on `invoice_address`.`idinvoice` = `invoice`.`idinvoice`
					inner join `invoice_custom_fld` on `invoice_custom_fld`.`idinvoice` = `invoice`.`idinvoice`
					left join `user` on `user`.`iduser` = `invoice`.`iduser`
					left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
					left join `organization` on `organization`.`idorganization` = `invoice`.`idorganization`
					left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `invoice`.`idpotentials`
					inner join `sales_order` on `sales_order`.`idsales_order` = `invoice`.`idsales_order`
					left join `contacts` on `contacts`.`idcontacts` = `invoice`.`idcontacts`
					inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
					inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
					left join `user` `user1` on `user1`.`iduser` = `sales_order`.`iduser`
					left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
					left join `organization` `organization1` on `organization1`.`idorganization` = `sales_order`.`idorganization`
					left join `group` `group1` on `group1`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
					left join `potentials` `potentials1` on `potentials1`.`idpotentials` = `sales_order`.`idpotentials`
					left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
					left join `contacts` `contacts1` on `contacts1`.`idcontacts` = `sales_order`.`idcontacts`
					";
				} elseif ($report_modules["secondary"]["module_name"] == 'Products') {
					$qry="
					from `invoice`
					inner join `invoice_address` on `invoice_address`.`idinvoice` = `invoice`.`idinvoice`
					inner join `invoice_custom_fld` on `invoice_custom_fld`.`idinvoice` = `invoice`.`idinvoice`
					left join `user` on `user`.`iduser` = `invoice`.`iduser`
					left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
					left join `organization` on `organization`.`idorganization` = `invoice`.`idorganization`
					left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `invoice`.`idpotentials`
					left join `sales_order` on `sales_order`.`idsales_order` = `invoice`.`idsales_order`
					left join `contacts` on `contacts`.`idcontacts` = `invoice`.`idcontacts`
					inner join `lineitems` on `lineitems`.`recordid` = `invoice`.`idinvoice` 
					and `lineitems`.`idmodule` = 15 and `lineitems`.`item_type` = 'product'
					inner join `products` on `products`.`idproducts` = `lineitems`.`item_value`
					inner join `products_pricing` on `products_pricing`.`idproducts` = `products`.`idproducts`
					inner join `products_custom_fld` on `products_custom_fld`.`idproducts` = `products`.`idproducts`
					inner join `products_stock` on `products_stock`.`idproducts` = `products`.`idproducts`
					left join `user` `user1` on `user1`.`iduser` = `products`.`iduser`
					left join `products_to_grp_rel` on `products_to_grp_rel`.`idproducts` = `products`.`idproducts`
					left join `vendor` on `vendor`.`idvendor` = `products`.`idvendor`
					left join `group` `group1` on `group1`.`idgroup` = `products_to_grp_rel`.`idgroup`
					left join `products_tax` on `products_tax`.`idproducts` = `products`.`idproducts`
					";
					//--set the group by since it would be needed for product 
					$this->set_report_group_by_clause(' group by `lineitems`.`idlineitems`');
				} else {
					$qry="
					from `invoice`
					inner join `invoice_address` on `invoice_address`.`idinvoice` = `invoice`.`idinvoice`
					inner join `invoice_custom_fld` on `invoice_custom_fld`.`idinvoice` = `invoice`.`idinvoice`
					left join `user` on `user`.`iduser` = `invoice`.`iduser`
					left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
					left join `organization` on `organization`.`idorganization` = `invoice`.`idorganization`
					left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
					left join `potentials` on `potentials`.`idpotentials` = `invoice`.`idpotentials`
					left join `sales_order` on `sales_order`.`idsales_order` = `invoice`.`idsales_order`
					left join `contacts` on `contacts`.`idcontacts` = `invoice`.`idcontacts`
					";
				}
				break;
		}
		return $qry ;
	}  
}