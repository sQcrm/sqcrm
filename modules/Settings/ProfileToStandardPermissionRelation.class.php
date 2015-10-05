<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class ProfileToStandardPermissionRelation
* Maintain the profile to standard permission relation
* @author Abhik Chakraborty
*/


class ProfileToStandardPermissionRelation extends DataObject {
	public $table = "profile_standard_permission_rel";
	public $primary_key = "idprofile_standard_permission_rel";
	
	/**
	* function to get the profile standard permission by idprofile
	* @param integer $idprofile
	*/
	public function get_profile_standard_permissions($idprofile) {
		$this->query("select * from ".$this->getTable()." where idprofile = ?",array($idprofile));
	}
}
