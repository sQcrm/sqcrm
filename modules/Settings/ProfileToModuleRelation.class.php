<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ProfileToModuleRelation
* Maintain the profile to module relation
* @author Abhik Chakraborty
*/


class ProfileToModuleRelation extends DataObject {
	public $table = "profile_module_rel";
	public $primary_key = "idprofile_module_rel";
	
	/**
	* function to get the profile module relation by idprofile
	* @param integer $idprofile
	*/
	public function get_profile_module_rel($idprofile) {
		$qry = "select * from ".$this->getTable()." where idprofile = ?";
		$this->query($qry,array($idprofile));
	}
      
}
