<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class RoleProfileRelation
* Maintain the role and profile relation
* @author Abhik Chakraborty
*/


class RoleProfileRelation extends DataObject {
	public $table = "role_profile_rel";
	public $primary_key = "idrole_profile_rel";

	/**
	* function to get profiles related to a role
	* @param string $idrole
	*/
	public function get_pofiles_related_to_role($idrole) {
		$qry = "
		select role_profile_rel.*,profile.profilename from role_profile_rel
		inner join profile on profile.idprofile = role_profile_rel.idprofile
		where role_profile_rel.idrole = ? " ;
		$this->query($qry,array($idrole));
	}

	/**
	* function to get the roles related to profile by idprofile
	* @param integer $idprofile
	* @return mix
	*/
	public function get_roles_related_to_profile($idprofile) {
		$qry = "
		select `role`.`rolename` from `".$this->getTable()."`
		inner join `role` on `role`.`idrole` = `".$this->getTable()."`.`idrole`
		where 
		`".$this->getTable()."`.`idprofile` = ?";
		$this->query($qry,array($idprofile));
		if ($this->getNumRows() > 0) {
			$return_array = array();
			while ($this->next()) {
				$return_array[] = $this->rolename ;
			}
			return $return_array ;
		} else { return false ; }
	}
    
	/**
	* Update the role profile relation data
	* @param array $profile_array
	* @param integer $idrole
	*/
	public function update_profile_related_to_role($profile_array,$idrole) {
		$qry = "select idprofile from ".$this->getTable()." where idrole = ?" ; 
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idrole));
		if ($stmt->rowCount() > 0) {
			$profile_available = array();
			while ($data = $stmt->fetch()) {
				$profile_available[] = $data["idprofile"];
			}
		}
		
		foreach ($profile_available as $idprofile) {
			if (!in_array($idprofile,$profile_array)) {
				$this->delete_profile_related_to_role($idrole,$idprofile);
			}
		}

		foreach ($profile_array as $idprofile) {
			if (!in_array($idprofile,$profile_available)) {
				$this->add_new_profile_related_to_role($idrole,$idprofile);
			}
		}
	}
    
	/**
	* Adding a new role profile relation
	* @param string $idrole
	* @param integer $idprofile
	*/
	public function add_new_profile_related_to_role($idrole,$idprofile) {
		$this->addNew();
		$this->idrole = $idrole ;
		$this->idprofile = $idprofile;
		$this->add();
	}

	/**
	* Deleting a role to profile relation data
	* @param string $idrole
	* @param integer $idprofile
	*/
	public function delete_profile_related_to_role($idrole,$idprofile) {
		$qry = "
		delete from ".$this->getTable()." 
		where idrole = ?
		AND idprofile = ? LIMIT 1";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idrole,$idprofile));
	}
}