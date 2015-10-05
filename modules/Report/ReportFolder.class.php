<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ReportFolder 
* @author Abhik Chakraborty
*/ 
	

class ReportFolder extends DataObject {
	public $table = "report_folder";
	public $primary_key = "idreport_folder";
	
	/**
	* get report folders
	* @return array
	*/
	public function get_report_folders() {
		$return_array = array();
		$qry = "select * from `report_folder` order by name";
		$this->query($qry);
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[] = array("idreport_folder"=>$this->idreport_folder,"name"=>$this->name,"description"=>$this->description);
			}
		}
		return $return_array;
	}
}