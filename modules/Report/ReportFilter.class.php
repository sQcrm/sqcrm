<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ReportFilter 
* @author Abhik Chakraborty
*/ 
	

class ReportFilter extends DataObject {
	public $table = "report_filter";
	public $primary_key = "idreport_filter";

	/**
	* get date filter fields for the report
	* @param integer $idprimary_module
	* @param integer $idsecondary_module
	* @return array
	*/
	public function get_date_filter_fields($idprimary_module,$idsecondary_module=0) {
		$qry = "
		select
		idfields,
		field_name,
		field_label
		from fields 
		where 
		field_type = 9
		and idmodule in (".(int)$idprimary_module.",".(int)$idsecondary_module.")
		";
		$this->query($qry);
		$return_array = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$data = array("idfields"=>$this->idfields,"field_label"=>$this->field_label);
				$return_array[] = $data ;
			}
		}
		return $return_array ; 
	}
	
	/**
	* get date filter options
	* @return array
	*/
	public function get_date_filter_options() {
		return array(
			1=>_('Custom'),
			2=>_('Previous FY'),
			3=>_('Current FY'),
			4=>_('Next FY'),
			5=>_('Previous FQ'),
			6=>_('Current FQ'),
			7=>_('Next FQ'),
			8=>_('Yesterday'),
			9=>_('Today'),
			10=>_('Tomorrow'),
			11=>_('Last Week'),
			12=>_('This Week'),
			13=>_('Next Week'),
			14=>_('Last Month'),
			15=>_('This Month'),
			16=>_('Next Month'),
			17=>_('Last 7 days'),
			18=>_('Last 30 days'),
			19=>_('Last 60 days'),
			20=>_('Last 90 days'),
			21=>_('Next 7 days'),
			22=>_('Next 30 days'),
			23=>_('Next 60 days'),
			24=>_('Next 90 days')
		);
	}
	
	/**
	* get advanced filter options
	* @return array
	*/
	public function get_advanced_filter_options() {
		return array(
			0=>_('none'),
			1=>_('equal'),
			2=>_('not equal'),
			3=>_('contains'),
			4=>_('does not contains'),
			5=>_('less than'),
			6=>_('greater than'),
			7=>_('less than or equal'),
			8=>_('greater than or equal')
		);
	}
	
	/**
	* add report filter
	* @param integer $idreport
	* @param array $filter_options
	*/
	public function add_report_filter($idreport,$filter_options) {
		if (count($filter_options["date_filter_options"]) > 0 ) {
			$this->add_report_date_filter($idreport,$filter_options["date_filter_options"]);
		}
		if (count($filter_options["advanced_filter_options"]) > 0) {
			$this->add_report_advanced_filter($idreport,$filter_options["advanced_filter_options"]);
		}
	}
	
	/**
	* function update report filter
	* @param integer $idreport
	* @param array $filter_options
	*/
	public function update_report_filter($idreport,$filter_options) {
		$this->update_report_date_filter($idreport,$filter_options["date_filter_options"]);
		$this->update_report_advanced_filter($idreport,$filter_options["advanced_filter_options"]);
	}
	
	/**
	* add report date filter
	* @param integer $idreport
	* @param array $date_filter
	*/
	public function add_report_date_filter($idreport,$date_filter) {
		$report_date_start = '';
		$report_date_end = '';
		if ($date_filter["report_date_start"] !='') {
			$report_date_start = $date_filter["report_date_start"];
		}
		if ($date_filter["report_date_end"] !='') {
			$report_date_end = $date_filter["report_date_end"];
		}
		if ((int)$date_filter["report_date_field"] > 0) {
			$this->insert(
			"report_date_filter",
				array(
					"idreport"=>$idreport,
					"idfield"=>$date_filter["report_date_field"],
					"filter_type"=>$date_filter["report_date_field_type"],
					"start_date"=>$report_date_start,
					"end_date"=>$report_date_end
				)
			);
		}
	}
	
	/**
	* update report date filter
	* @param integer $idreport
	* @param array $date_filter
	*/
	public function update_report_date_filter($idreport,$date_filter) {
		$this->query("select * from `report_date_filter` where `idreport` = ?",array($idreport));
		if ($this->getNumRows() > 0) {
			if ((int)$date_filter["report_date_field"] > 0) {
				$qry = "
				update `report_date_filter` 
				set `idfield` =  ?,
				`filter_type` = ?,
				`start_date` = ?,
				`end_date` = ?
				where `idreport` = ?";
				$this->query(
					$qry,
					array(
						$date_filter["report_date_field"],
						$date_filter["report_date_field_type"],
						$date_filter["report_date_start"],
						$date_filter["report_date_end"],
						$idreport
					)
				);		
			}
		} else {
			$this->add_report_date_filter($idreport,$date_filter);
		}
	}
	
	/**
	* add report advanced filter
	* @param integer $idreport
	* @param array $adv_filter
	*/
	public function add_report_advanced_filter($idreport,$adv_filter) {
		if ($adv_filter["report_adv_fields_1"] !='' && $adv_filter["report_adv_fields_type_1"] !='0' && $adv_filter["report_adv_fields_val_1"] != '') {
			$this->add_each_report_advanced_filter($idreport,$adv_filter["report_adv_fields_1"],$adv_filter["report_adv_fields_type_1"],$adv_filter["report_adv_fields_val_1"]);
		}
		if ($adv_filter["report_adv_fields_2"] !='' && $adv_filter["report_adv_fields_type_2"] !='0' && $adv_filter["report_adv_fields_val_2"] != '') {
			$this->add_each_report_advanced_filter($idreport,$adv_filter["report_adv_fields_2"],$adv_filter["report_adv_fields_type_2"],$adv_filter["report_adv_fields_val_2"]);
		}
		if ($adv_filter["report_adv_fields_3"] !='' && $adv_filter["report_adv_fields_type_3"] !='0' && $adv_filter["report_adv_fields_val_3"] != '') {
			$this->add_each_report_advanced_filter($idreport,$adv_filter["report_adv_fields_3"],$adv_filter["report_adv_fields_type_3"],$adv_filter["report_adv_fields_val_3"]);
		}
		if ($adv_filter["report_adv_fields_4"] !='' && $adv_filter["report_adv_fields_type_4"] !='0' && $adv_filter["report_adv_fields_val_4"] != '') {
			$this->add_each_report_advanced_filter($idreport,$adv_filter["report_adv_fields_4"],$adv_filter["report_adv_fields_type_4"],$adv_filter["report_adv_fields_val_4"]);
		}
		if ($adv_filter["report_adv_fields_5"] !='' && $adv_filter["report_adv_fields_type_5"] !='0' && $adv_filter["report_adv_fields_val_5"] != '') {
			$this->add_each_report_advanced_filter($idreport,$adv_filter["report_adv_fields_5"],$adv_filter["report_adv_fields_type_5"],$adv_filter["report_adv_fields_val_5"]);
		}
	}
	
	/**
	* update report advanced filter
	* @param integer $idreport
	* @param array $adv_filter
	*/
	public function update_report_advanced_filter($idreport,$adv_filter) {
		$qry = "delete from `".$this->getTable()."` where `idreport` = ? ";
		$this->query($qry,array($idreport));
		$this->add_report_advanced_filter($idreport,$adv_filter);
	}
	
	/**
	* add individual report advanced filter
	* @param integer $idreport
	* @param integer $field
	* @param integer $type
	* @param mix $val
	*/
	public function add_each_report_advanced_filter($idreport,$field,$type,$val) {
		$this->addNew();
		$this->idreport = $idreport;
		$this->filter_field = $field;
		$this->filter_type = $type;
		$this->filter_value = $val;
		$this->add();
	}
	
	/**
	* get saved filter date filters
	* @return string
	*/
	public function get_saved_date_filter() {
		$qry = "
		select 
		rdf.*, 
		f.field_name,
		f.field_label,
		f.table_name,
		f.field_type,
		f.idmodule
		from report_date_filter rdf 
		join fields f on f.idfields = rdf.idfield
		where idreport = ?";
		return $qry;
	}
	
	/**
	* parse the date filter for the query
	* @param integer $idreport
	* @param array $data
	* @return string
	*/
	public function get_parsed_date_filter($idreport,$data=array()) { 
		$date_where = '';
		if (count($data) > 0) {
			$filter_type = $data["filter_type"]; 
			$do_fields = new CRMFields();
			$do_fields->getId((int)$data["idfield"]);
			$where_field = $do_fields->table_name.'.'.$do_fields->field_name;
			if ($data["start_date"] != '') {
				$start_date = FieldType9::convert_before_save($data["start_date"]);
			}
			if ($data["end_date"] != '') {
				$end_date = FieldType9::convert_before_save($data["end_date"]);
			}
		} else {
			$qry = $this->get_saved_date_filter();
			$this->query($qry,array($idreport));
			if ($this->getNumRows() > 0) { 
				$this->next();
				$filter_type = $this->filter_type ;
				$field_name = $this->field_name;
				$where_field = $this->table_name.'.'.$field_name ;
				if ($this->start_date != '' && $this->start_date !='0000-00-00') {
					$start_date = $this->start_date;
				}
				if ($this->end_date != '' && $this->end_date != '0000-00-00') {
					$end_date = $this->end_date;
				}
			}
		}
		if ($filter_type > 0) { 
			switch ($filter_type) {
				case '1':
					if ($start_date != '' && $start_date !='0000-00-00' && $end_date != '' && $end_date != '0000-00-00') {
						$date_where = " AND ".$where_field." between '".$start_date."' AND '".$end_date."'";
					}
					break;
				case '2':
					$date_range = CommonUtils::get_year_date_range('previous');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '3':
					$date_range = CommonUtils::get_year_date_range('current');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '4':
					$date_range = CommonUtils::get_year_date_range('next');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '5':
					$date_range = CommonUtils::get_quarter_date_range('previous');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '6':
					$date_range = CommonUtils::get_quarter_date_range('current');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '7':
					$date_range = CommonUtils::get_quarter_date_range('next');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '8':
					$date_where = " AND ".$where_field." between concat(date_sub(curdate(),interval 1 day),' 00:00:00') and concat(date_sub(curdate(),interval 1 day),' 23:59:59')";
					break;
				case '9':
					$date_where = " AND ".$where_field." between concat(curdate(),' 00:00:00') and concat(curdate(),' 23:59:59')";
					break;
				case '10':
					$date_where = " AND ".$where_field." between concat(date_add(curdate(),interval 1 day),' 00:00:00') and concat(date_add(curdate(),interval 1 day),' 23:59:59')";
					break;	
				case '11':
					$date_range = CommonUtils::get_week_date_range('previous');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '12':
					$date_range = CommonUtils::get_week_date_range('current');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '13':
					$date_range = CommonUtils::get_week_date_range('next');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '14':
					$date_range = CommonUtils::get_month_date_range('previous');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '15':
					$date_range = CommonUtils::get_month_date_range('current');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '16':
					$date_range = CommonUtils::get_month_date_range('next');
					$date_where = " AND ".$where_field." between '".$date_range["start"]."' AND '".$date_range["end"]."'";
					break;
				case '17':
					$date_where = " AND ".$where_field." between date_sub(curdate(),interval 7 day) and date_sub(curdate(),interval 1 day)";
					break;
				case '18':
					$date_where = " AND ".$where_field." between date_sub(curdate(),interval 30 day) and date_sub(curdate(),interval 1 day)";
					break;
				case '19':
					$date_where = " AND ".$where_field." between date_sub(curdate(),interval 60 day) and date_sub(curdate(),interval 1 day)";
					break;
				case '20':
					$date_where = " AND ".$where_field." between date_sub(curdate(),interval 90 day) and date_sub(curdate(),interval 1 day)";
					break;
				case '21':
					$date_where = " AND ".$where_field." between date_add(curdate(),interval 1 day) and date_add(curdate(),interval 7 day)";
					break;
				case '22':
					$date_where = " AND ".$where_field." between date_add(curdate(),interval 1 day) and date_add(curdate(),interval 30 day)";
					break;
				case '23':
					$date_where = " AND ".$where_field." between date_add(curdate(),interval 1 day) and date_add(curdate(),interval 60 day)";
					break;
				case '24':
					$date_where = " AND ".$where_field." between date_add(curdate(),interval 1 day) and date_add(curdate(),interval 90 day)";
					break;
			} 
		}
		return $date_where;
	}
	
	/**
	* parse the advanced filter for the report query
	* @param integer $idreport
	* @return array
	*/
	public function get_parsed_adv_filter($idreport) {
		$qry = "
		select 
		rf.*,
		f.field_name,
		f.field_label,
		f.table_name,
		f.field_type,
		f.idmodule
		from report_filter rf
		join fields f on f.idfields = rf.filter_field
		where rf.idreport = ?
		";
		$this->query($qry,array($idreport));
		if ($this->getNumRows() > 0) {
			$adv_where = '';
			$bind_params = array();
			$do_report_module_rel = new ReportModuleRel();
			$report_modules = $do_report_module_rel->get_report_modules($idreport);
			while ($this->next()) {
				if ((int)$this->filter_field > 0 && (int)$this->filter_type >0 && $this->filter_value != '') {
					$adv_filter_field = '';
					$condition = $this->get_adv_filter_conditions($this->filter_type);
					if ($this->field_type == 15) {
						if ($this->filter_type == 1 || $this->filter_type == 3) {
							if ($this->idmodule == $report_modules["primary"]["idmodule"]) {
								$adv_where .= " AND ( `user`.`user_name` $condition OR `group`.`group_name` $condition ) ";
								if ($this->filter_type == 3) {
									$bind_params[] = '%'.$this->filter_value.'%';
									$bind_params[] = '%'.$this->filter_value.'%';
								} else {
									$bind_params[] = $this->filter_value;
									$bind_params[] = $this->filter_value;
								}
							} else {
								$adv_where .= " AND ( `user1`.`user_name` $condition OR `group1`.`group_name` $condition ) ";
								if ($this->filter_type == 3) {
									$bind_params[] = '%'.$this->filter_value.'%';
									$bind_params[] = '%'.$this->filter_value.'%';
								} else {
									$bind_params[] = $this->filter_value;
									$bind_params[] = $this->filter_value;
								}
							}
						} elseif ($this->filter_type == 2 || $this->filter_type == 4) {
							if ($this->idmodule == $report_modules["primary"]["idmodule"]) {
								$adv_where .= " AND ( `user`.`user_name` $condition AND `group`.`group_name` $condition ) ";
								if ($this->filter_type == 4) {
									$bind_params[] = '%'.$this->filter_value.'%';
									$bind_params[] = '%'.$this->filter_value.'%';
								} else {
									$bind_params[] = $this->filter_value;
									$bind_params[] = $this->filter_value;
								}
							} else {
								$adv_where .= " AND ( `user1`.`user_name` $condition AND `group1`.`group_name` $condition ) ";
								if ($this->filter_type == 4) {
									$bind_params[] = '%'.$this->filter_value.'%';
									$bind_params[] = '%'.$this->filter_value.'%';
								} else {
									$bind_params[] = $this->filter_value;
									$bind_params[] = $this->filter_value;
								}
							}
						}
					} elseif ($this->field_type == 130) {
						if ($this->field_name == 'reports_to') {
							$adv_where .= " AND concat(`cnt2`.`firstname`,' ',`cnt2`.`lastname`) $condition ";
							if ($this->filter_type == 3 || $this->filter_type == 4) {
								$bind_params[] = '%'.$this->filter_value.'%';
							} else {
								$bind_params[] = $this->filter_value;
							}
						}
					} elseif ($this->field_type == 131) {
						if ($this->field_name == 'idorganization') {
							if ($this->idmodule == $report_modules["primary"]["idmodule"]) {
								$adv_where .= " AND `organization`.`organization_name` $condition ";
								if ($this->filter_type == 3 || $this->filter_type == 4) {
									$bind_params[] = '%'.$this->filter_value.'%';
								} else {
									$bind_params[] = $this->filter_value;
								}
							} else {
								$adv_where .= " AND `organization`.`organization_name` $condition ";
								if ($this->filter_type == 3 || $this->filter_type == 4) {
									$bind_params[] = '%'.$this->filter_value.'%';
								} else {
									$bind_params[] = $this->filter_value;
								}
							}
						} elseif ($this->field_name == 'member_of') {
							$adv_where .= " AND `org2`.`organization_name` $condition ";
							if ($this->filter_type == 3 || $this->filter_type == 4) {
								$bind_params[] = '%'.$this->filter_value.'%';
							} else {
								$bind_params[] = $this->filter_value;
							}
						}
					} elseif ($this->field_type == 133) {
						if ($this->idmodule == $report_modules["primary"]["idmodule"]) {
							$adv_where .= " AND `potentials`.`potential_name` $condition ";
						} else {
							$adv_where .= " AND `potentials1`.`potential_name` $condition ";
						}
						if ($this->filter_type == 3 || $this->filter_type == 4) {
							$bind_params[] = '%'.$this->filter_value.'%';
						} else {
							$bind_params[] = $this->filter_value;
						}
					} elseif ($this->field_type == 141) {
						if ($this->idmodule == $report_modules["primary"]["idmodule"]) {
							$adv_where .= " AND `organization`.`organization_name` $condition ";
						} else {
							$adv_where .= " AND `organization1`.`organization_name` $condition ";
						}
					} elseif ($this->field_type == 142) {
						if ($this->idmodule == $report_modules["primary"]["idmodule"]) {
							$adv_where .= " AND concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) $condition ";
						} else {
							$adv_where .= " AND concat(`contacts1`.`firstname`,' ',`contacts1`.`lastname`) $condition ";
						}
						if ($this->filter_type == 3 || $this->filter_type == 4) {
							$bind_params[] = '%'.$this->filter_value.'%';
						} else {
							$bind_params[] = $this->filter_value;
						}
					} elseif ($this->field_type == 143) {
						$adv_where .= " AND concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) $condition ";
						if ($this->filter_type == 3 || $this->filter_type == 4) {
							$bind_params[] = '%'.$this->filter_value.'%';
						} else {
							$bind_params[] = $this->filter_value;
						}
					} elseif ($this->field_type == 150) {
						if ($this->filter_type == 1 || $this->filter_type == 3) {
							$adv_where .= " AND ( `sqorg`.`organization_name` $condition OR concat(sqcnt.firstname,' ',sqcnt.lastname) $condition ) ";
							if ($this->filter_type == 3) {
								$bind_params[] = '%'.$this->filter_value.'%';
								$bind_params[] = '%'.$this->filter_value.'%';
							} else {
								$bind_params[] = $this->filter_value;
								$bind_params[] = $this->filter_value;
							}
						} elseif ($this->filter_type == 2 || $this->filter_type == 4) {
							$adv_where .=  " AND ( `sqorg`.`organization_name` $condition AND concat(sqcnt.firstname,' ',sqcnt.lastname) $condition ) ";
							if ($this->filter_type == 4) {
								$bind_params[] = '%'.$this->filter_value.'%';
								$bind_params[] = '%'.$this->filter_value.'%';
							} else {
								$bind_params[] = $this->filter_value;
								$bind_params[] = $this->filter_value;
							}
						}
					} elseif ($this->field_type == 151) {
						if ($this->filter_type == 1 || $this->filter_type == 3) {
							$adv_where .= " AND ( sqorg.organization_name $condition OR concat(sqcnt.firstname,' ',sqcnt.lastname) $condition OR concat(sqleads.firstname,' ',sqleads.lastname) $condition OR sqpot.potential_name $condition )";
							if ($this->filter_type == 3) {
								$bind_params[] = '%'.$this->filter_value.'%';
								$bind_params[] = '%'.$this->filter_value.'%';
								$bind_params[] = '%'.$this->filter_value.'%';
								$bind_params[] = '%'.$this->filter_value.'%';
							} else {
								$bind_params[] = $this->filter_value;
								$bind_params[] = $this->filter_value;
								$bind_params[] = $this->filter_value;
								$bind_params[] = $this->filter_value;
							}
						} elseif ($this->filter_type == 2 || $this->filter_type == 4) {
							$adv_where .= " AND ( sqorg.organization_name $condition AND concat(sqcnt.firstname,' ',sqcnt.lastname) $condition AND concat(sqleads.firstname,' ',sqleads.lastname) $condition AND sqpot.potential_name $condition )";
							if ($this->filter_type == 4) {
								$bind_params[] = '%'.$this->filter_value.'%';
								$bind_params[] = '%'.$this->filter_value.'%';
								$bind_params[] = '%'.$this->filter_value.'%';
								$bind_params[] = '%'.$this->filter_value.'%';
							} else {
								$bind_params[] = $this->filter_value;
								$bind_params[] = $this->filter_value;
								$bind_params[] = $this->filter_value;
								$bind_params[] = $this->filter_value;
							}
						}
					} elseif ($this->field_type == 160) {
						if ($report_modules["primary"]["idmodule"] == 16 && $report_modules["secondary"]["idmodule"] == 12) {
							$adv_where .= " AND `vendor1`.`vendor_name` $condition ";
						} else {
							$adv_where .= " AND `vendor`.`vendor_name` $condition ";
						}
						if ($this->filter_type == 3 || $this->filter_type == 4) {
							$bind_params[] = '%'.$this->filter_value.'%';
						} else {
							$bind_params[] = $this->filter_value;
						}
					} else {
						$adv_where .= " AND `".$this->table_name."`.`".$this->field_name."` $condition ";
						if ($this->filter_type == 3 || $this->filter_type == 4) {
							$bind_params[] = '%'.$this->filter_value.'%';
						} else {
							$bind_params[] = $this->filter_value;
						}
					}
				}
			}
		}		
		if ($adv_where != '') {
			return array("where"=>$adv_where,"bind_params"=>$bind_params);
		} else { return false ; }
	}
	
	/**
	* get advanced filter condition
	* @param integer $filter_type
	* @return string
	*/
	public function get_adv_filter_conditions($filter_type) {
		$condition = '';
		switch ((int)$filter_type) {
			case 1 : 
				$condition = " = ? ";
				break;
			case 2 : 
				$condition = " <> ? ";
				break;
			case 3 : 
				$condition = " like ? ";
				break;
			case 4 : 
				$condition = " not like ? ";
				break;
			case 5 : 
				$condition = " < ? ";
				break;
			case 6 : 
				$condition = " > ? ";
				break;
			case 7 : 
				$condition = " <= ? ";
				break;
			case 8 : 
				$condition = " >= ? ";
				break;
		}
		return $condition ;
	}
	
	/**
	* get the saved date filter details
	* @param integer $idreport
	* @return array
	*/
	public function get_saved_filter_details($idreport) {
		$return_array = array();
		$qry = "select * from `report_date_filter` where `idreport` = ? ";
		$this->query($qry,array($idreport));
		if ($this->getNumRows() > 0) {
			$return_array["date_filter_options"] = array(
				"report_date_field"=>$this->idfield,
				"report_date_field_type"=>$this->report_date_field_type,
				"report_date_start"=>$this->start_date,
				"report_date_end"=>$this->end_date
			);
		}
		return $return_array;
	}
	
	/**
	* get saved advanced filter options
	* @param integer $idreport
	* @return array
	*/
	public function get_saved_adv_filter_options($idreport) {
		$adv_filter = array();
		$return_array = array();
		$qry = "select * from `report_filter` where `idreport` = ? ";
		$this->query($qry,array($idreport));
		if ($this->getNumRows() > 0) {
			$cnt= 1;
			while ($this->next()) {
				$return_array["report_adv_fields_$cnt"] = $this->filter_field;
				$return_array["report_adv_fields_type_$cnt"] = $this->filter_type;
				$return_array["report_adv_fields_val_$cnt"] = $this->filter_value;
				$cnt++;
			}
			$adv_filter["advanced_filter_options"]=$return_array;
		}
		return $adv_filter ;
	}
}