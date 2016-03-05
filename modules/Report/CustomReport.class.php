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
}