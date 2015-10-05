<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMRelatedInformation 
* Mantain the related information of the modules
* @author Abhik Chakraborty
*/
	

class CRMRelatedInformation extends DataObject {
	public $table = "related_information";
	public $primary_key = "idrelated_information";

	/**
	* function to get the related information of a module
	* @param integer $idmodule
	*/
	public function get_related_information($idmodule) {
		$this->query("select * from ".$this->getTable()." where idmodule = ? order by sequence",array($idmodule));
		if ($this->getNumRows() > 0) {
			$return_data = array();
			while ($this->next()) {
				$data["id"] = $this->idrelated_information;
				$data["method"] = $this->method_name;
				$data["heading"] = $this->heading;
				$return_data[$this->related_module] = $data ;
			}
			return $return_data ;
		} else {
			return false ;
		}
	}
}
