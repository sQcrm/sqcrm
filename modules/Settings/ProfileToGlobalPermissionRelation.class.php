<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ProfileToGlobalPermissionRelation
* Maintain the profile to global permission relation
* @author Abhik Chakraborty
*/


class ProfileToGlobalPermissionRelation extends DataObject {
	public $table = "profile_global_permission_rel";
	public $primary_key = "idprofile_global_permission_rel";
	
	/**
	* function to get the global permission by profile id
	* @param integer $idprofile
	*/
	public function get_global_permissions_by_profile($idprofile) {
		$this->query("select * from ".$this->getTable()." where idprofile = ?",array($idprofile));
	}
}
