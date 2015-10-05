<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class GroupUserRelation
* Maintain the group and user relation
* @author Abhik Chakraborty
*/


class GroupUserRelation extends DataObject {
	public $table = "group_user_rel";
	public $primary_key = "idgroup_user_rel";

	/**
	* function to get users related to a group
	* @param string $idgroup
	*/
	public function get_users_related_to_group($idgroup) {
		$qry = "
		select group_user_rel.*,user.* from group_user_rel
		inner join user on user.iduser = group_user_rel.iduser
		where group_user_rel.idgroup = ?" ;
		$this->query($qry,array($idgroup));
	}

	/**
	* Update the group user relation data
	* @param array $user_array
	* @param integer $idgroup
	*/
	public function update_group_related_to_user($user_array,$idgroup) {
		$qry = "select iduser from `".$this->getTable()."` where idgroup = ?" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idgroup));
		if ($stmt->rowCount() > 0) {
			$user_available = array();
			while ($data = $stmt->fetch()) {
				$user_available[] = $data["iduser"];
			}
		}
		foreach ($user_available as $iduser) {
			if (!in_array($iduser,$user_array)) {
				$this->delete_group_related_to_user($idgroup,$iduser);
			}
		}
		foreach ($user_array as $iduser) {
			if (!in_array($iduser,$user_available)) {
				$this->add_new_group_related_to_user($idgroup,$iduser);
			}
		}
	}
    
	/**
	* Adding a new group user relation
	* @param string $idgroup
	* @param integer $iduser
	*/
	public function add_new_group_related_to_user($idgroup,$iduser) {
		$this->addNew();
		$this->idgroup = $idgroup ;
		$this->iduser = $iduser;
		$this->add();
	}

	/**
	* Deleting a group to user relation data
	* @param string $idrole
	* @param integer $idprofile
	*/
	public function delete_group_related_to_user($idgroup,$iduser) {
		$this->query("delete from ".$this->getTable()." where idgroup = ? AND iduser = ? LIMIT 1 ",array($idgroup,$iduser));
	}
    
	/**
	* function to get the goups to which user is associated
	* @param integer $iduser
	* @return $ret_array
	*/
	public function get_groups_by_user($iduser) {
		$ret_array = array();
		$this->query("select idgroup from ".$this->table." where iduser = ?",array($iduser));
		if ($this->getNumRows() > 0) {
			$this->next();
			$ret_array[] = $this->idgroup ;
		}
		return $ret_array ;
	}
}