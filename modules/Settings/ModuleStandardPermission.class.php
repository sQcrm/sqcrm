<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ModuleStandardPermission
* Maintain the standard permission per module
* @author Abhik Chakraborty
*/


class ModuleStandardPermission extends DataObject {
	public $table = "module_standard_permission";
	public $primary_key = "idmodule_standard_permission";
    
	/**
	* Function to get the standard permission for the modules
	* @param integer $idmodule
	*/
	public function get_module_standard_permissions($idmodule) {
		$this->query("select * from ".$this->getTable()." where idmodule = ?",array($idmodule));
	}
    
}
