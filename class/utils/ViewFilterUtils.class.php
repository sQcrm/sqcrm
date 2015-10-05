<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class ViewFilterUtils for custom view and reports
* @author Abhik Chakraborty
*/
	

class ViewFilterUtils {
	
	/**
	* get the date filter options 
	* @return array
	*/
	public static function get_date_filter_otions() {
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
	* get the advanced filter options
	* @return array
	*/
	public static function get_advanced_filter_options() {
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
	* parse the date filter options and generate the query condition
	* @param integer $id
	* @param object $obj 
	* @param array $data
	* @return string
	*/
	public static function get_parsed_date_filter($id,$obj,$data = array()) {
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
			$qry = $obj->get_saved_date_filter();
			$obj->query($qry,array($id));
			if ($obj->getNumRows() > 0) { 
				$obj->next();
				$filter_type = $obj->filter_type ;
				$field_name = $obj->field_name;
				$where_field = $obj->table_name.'.'.$field_name ;
				if ($obj->start_date != '' && $obj->start_date !='0000-00-00') {
					$start_date = $obj->start_date;
				}
				if ($obj->end_date != '' && $obj->end_date != '0000-00-00') {
					$end_date = $obj->end_date;
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
	* get the advanced filter condition by type 
	* @param integer $filter_type 
	* @return string
	*/
	public static function get_adv_filter_conditions($filter_type) {
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
	* parse the advanced filter options and generate the query condition
	* @param object $obj 
	* @return mix
	*/
	public static function parse_advanced_filter($obj) {
		$return_array = array();
		if (is_object($obj)) {
			if ($obj->getNumRows() > 0 ) {
				$adv_where = '';
				while ($obj->next()) {
					if ((int)$obj->filter_field > 0 && (int)$obj->filter_type >0 && $obj->filter_value != '') {
						$adv_filter_field = '';
						$condition = ViewFilterUtils::get_adv_filter_conditions($obj->filter_type);
						if ($obj->field_type == 15) {
							$adv_where .= " AND ( `user`.`user_name` $condition OR `group`.`group_name` $condition) ";
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 131) {
							if ($obj->idmodule == 6) {
								$adv_where .= " AND (`org2`.`organization_name` $condition) ";
							} elseif ($obj->idmodule == 4) {
								$adv_where .= " AND (`organization`.`organization_name` $condition) ";
							}
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 130) {
							$adv_where .= " AND (concat(`cnt2`.`firstname`,' ',`cnt2`.`lastname`) $condition) " ; 
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 133) {
							$adv_where .= " AND `potentials`.`potential_name` $condition ";
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 141) {
							$adv_where .= " AND `organization`.`organization_name` $condition ";
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 142) {
							$adv_where .= " AND concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) $condition ";
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 143) {
							$adv_where .= " AND concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) $condition ";
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 150) {
							$adv_where .= " AND ( `sqorg`.`organization_name` $condition OR concat(sqcnt.firstname,' ',sqcnt.lastname) $condition ) ";
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 151) {
							$adv_where .= " AND ( sqorg.organization_name $condition OR concat(sqcnt.firstname,' ',sqcnt.lastname) $condition OR concat(sqleads.firstname,' ',sqleads.lastname) $condition OR sqpot.potential_name $condition )";
							if ($obj->filter_type == 3 ) {
								$bind_params[] = '%'.$obj->filter_value.'%';
								$bind_params[] = '%'.$obj->filter_value.'%';
								$bind_params[] = '%'.$obj->filter_value.'%';
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
								$bind_params[] = $obj->filter_value;
								$bind_params[] = $obj->filter_value;
								$bind_params[] = $obj->filter_value;
							}
						} elseif ($obj->field_type == 160) {
							$adv_where .= " AND `vendor`.`vendor_name` $condition ";
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
							}
						} else {
							$adv_where .= " AND `".$obj->table_name."`.`".$obj->field_name."` $condition ";
							if ($obj->filter_type == 3 || $obj->filter_type == 4) {
								$bind_params[] = '%'.$obj->filter_value.'%';
							} else {
								$bind_params[] = $obj->filter_value;
							}
						}
					}
				}
				if ($adv_where != '') {
					return array("where"=>$adv_where,"bind_params"=>$bind_params);
				} else { return false ; }
			} else { return false ; }
		} else { return false ; }
	}
}