<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class CustomReport 
* @author Abhik Chakraborty
*/ 
	

class CustomReport extends DataObject {
	public $table = "";
	public $primary_key = "";
	
	/**
	* function to get the custom reports 
	* @return array
	*/
	public function get_custom_reports() {
		$return_array = array() ;
		$custom_report_path = BASE_PATH.'/modules/Report/CustomReports/' ;
		$custom_reports = array_diff(scandir($custom_report_path,1), array('..', '.')) ;
		if (count($custom_reports) > 0) {
			foreach ($custom_reports as $key=>$val) {
				if (file_exists($custom_report_path.$val.'/config.json')) {
					$report_config = file_get_contents($custom_report_path.$val.'/config.json') ;
					$report_config_decoded = json_decode($report_config) ;
					if ($report_config_decoded->enabled == 1) {
						$default_resource = ( property_exists($report_config_decoded,'default_resource') ? $report_config_decoded->default_resource : 'index') ;
						$return_array[] = array(
							"title" => $report_config_decoded->title ,
							"description" => $report_config_decoded->description ,
							"default_resource" => $default_resource ,
							"path" => $val 
						) ;
					}
				}
			}
		}
		if (count($return_array) > 0) {
			usort($return_array, function($a, $b) {
				return strcasecmp($a['title'], $b['title']);
			});
		}
		return $return_array ;
	}
	
	/**
	* function to get the userids used for the report data 
	* @param integer $iduser
	* @param boolean $ignore_current_user
	* @return array
	*/
	public function get_userids($iduser = 0,$ignore_current_user = true) {
		$user_list = array() ;
		if ((int)$iduser == 0) $iduser = $_SESSION["do_user"]->iduser ;
		$do_user = new User() ;
		$user_list = $do_user->get_subordinate_users_by_iduser($iduser) ;
		if (false === $ignore_current_user) $user_list[] = $iduser ;
		return $user_list ;
	}
	
	/**
	* function to get the user details for the report data (combo for user filter)
	* @param integer $iduser
	* @return array
	*/
	public function get_report_user_filter($iduser = 0) {
		$return_array = array() ;
		$userids = $this->get_userids($iduser) ;
		if (count($userids) > 0) {
			$qry = "
			select iduser,user_name,firstname,lastname
			from user where iduser in(".implode(",",array_unique($userids)).")
			order by firstname
			";
			$stmt = $this->getDbConnection()->executeQuery($qry);
			if ($stmt->rowCount() > 0) {
				while ($row = $stmt->fetch()) {
					$return_array[$row["iduser"]] = array(
						"firstname"=>$row["firstname"],
						"lastname"=>$row["lastname"],
						"user_name"=>$row["user_name"]
					);
				}
			}
		}
		return $return_array ;
	}
	
	/**
	* function to generate the user specific where condition for the custom reports
	* @param integer $iduser
	* @param string $table_name
	* @param string $group_rel_table
	* @param boolean $ignore_current_user
	* @return string
	*/
	public function get_report_where($iduser,$table_name,$group_rel_table='',$ignore_current_user = false) {
		$where = '';
		$user_to_groups = array() ;
		$user_ids = $this->get_userids($iduser,$ignore_current_user);
		$user_ids = array_unique($user_ids);
		if ($group_rel_table != '') {
			$do_group_user_rel = new GroupUserRelation();
			$user_to_groups = $do_group_user_rel->get_groups_by_user($iduser,array(),true);
		}
		
		if (count($user_to_groups) > 0) {
			$where.= " AND 
			( 
			`$table_name`.`iduser` in (".implode(",",$user_ids).") 
			OR `$group_rel_table`.`idgroup` in (".implode(",",$user_to_groups).") 
			)
			" ;
		} else {
			$where.= " AND `$table_name`.`iduser` in (".implode(",",$user_ids).")" ;
		}
		return $where ;
	}
	
	/**
	* function to get the where condition based on the filter type and field name 
	* @param string $table_name
	* @param string $field_name
	* @param integer $filter_type
	* @param string $start_date
	* @param string $end_date
	* @return string
	*/
	public function get_date_filter_where($table_name,$field_name,$filter_type,$start_date='',$end_date='') {
		$date_where = '';
		switch ($filter_type) {
			case 1:
				if ($start_date != '' && $start_date !='0000-00-00' && $end_date != '' && $end_date != '0000-00-00') {
					$date_where = " AND `$table_name`.`$field_name` between '".$start_date."' AND '".$end_date."'";
				}
				break;
			
			case '2':
				$date_range = CommonUtils::get_year_date_range('previous');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '3':
				$date_range = CommonUtils::get_year_date_range('current');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '4':
				$date_range = CommonUtils::get_year_date_range('next');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
			
			case '5':
				$date_range = CommonUtils::get_quarter_date_range('previous');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
			
			case '6':
				$date_range = CommonUtils::get_quarter_date_range('current');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '7':
				$date_range = CommonUtils::get_quarter_date_range('next');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break; 
				
			case '8':
				$date_where = " AND `$table_name`.`$field_name` between concat(date_sub(curdate(),interval 1 day),' 00:00:00') and concat(date_sub(curdate(),interval 1 day),' 23:59:59')";
				break;
			
			case '9':
				$date_where = " AND `$table_name`.`$field_name` between concat(curdate(),' 00:00:00') and concat(curdate(),' 23:59:59')";
				break;
				
			case '10':
				$date_where = " AND `$table_name`.`$field_name` between concat(date_add(curdate(),interval 1 day),' 00:00:00') and concat(date_add(curdate(),interval 1 day),' 23:59:59')";
				break;	
				
			case '11':
				$date_range = CommonUtils::get_week_date_range('previous');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '12':
				$date_range = CommonUtils::get_week_date_range('current');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '13':
				$date_range = CommonUtils::get_week_date_range('next');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '14':
				$date_range = CommonUtils::get_month_date_range('previous');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '15':
				$date_range = CommonUtils::get_month_date_range('current');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '16':
				$date_range = CommonUtils::get_month_date_range('next');
				$date_where = " AND `$table_name`.`$field_name` between '".$date_range["start"]."' AND '".$date_range["end"]."'";
				break;
				
			case '17':
				$date_where = " AND `$table_name`.`$field_name` between date_sub(curdate(),interval 7 day) and date_sub(curdate(),interval 1 day)";
				break;
				
			case '18':
				$date_where = " AND `$table_name`.`$field_name` between date_sub(curdate(),interval 30 day) and date_sub(curdate(),interval 1 day)";
				break;
				
			case '19':
				$date_where = " AND `$table_name`.`$field_name` between date_sub(curdate(),interval 60 day) and date_sub(curdate(),interval 1 day)";
				break;
				
			case '20':
				$date_where = " AND `$table_name`.`$field_name` between date_sub(curdate(),interval 90 day) and date_sub(curdate(),interval 1 day)";
				break;
				
			case '21':
				$date_where = " AND `$table_name`.`$field_name` between date_add(curdate(),interval 1 day) and date_add(curdate(),interval 7 day)";
				break;
				
			case '22':
				$date_where = " AND `$table_name`.`$field_name` between date_add(curdate(),interval 1 day) and date_add(curdate(),interval 30 day)";
				break;
				
			case '23':
				$date_where = " AND `$table_name`.`$field_name` between date_add(curdate(),interval 1 day) and date_add(curdate(),interval 60 day)";
				break;
				
			case '24':
				$date_where = " AND `$table_name`.`$field_name` between date_add(curdate(),interval 1 day) and date_add(curdate(),interval 90 day)";
				break;
		}
		return $date_where;
	}
	
	/**
	* function to generate the breadcrumb for the custom report
	* @param string $path
	* @return string
	*/
	public function get_breadcrumbs($path) {
		if ($path == '') return '';
		$custom_report_path = BASE_PATH.'/modules/Report/CustomReports/'.$path ;
		if (file_exists($custom_report_path.'/config.json')) {
			$report_config = file_get_contents($custom_report_path.'/config.json') ;
			$report_config_decoded = json_decode($report_config) ;
			$html = '
			<div class="row-fluid">
				<div class="span12">
					<div class="datadisplay-outer">
						<a href="/modules/Report/custom_report">'._('Custom Reports').'</a> | '.$report_config_decoded->title.'
					</div>
				</div>
			</div>
			<div class="clear_float"></div>
			' ;
			return $html ;
		} else {
			return '';
		}
		
	}
}
