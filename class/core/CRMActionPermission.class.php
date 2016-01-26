<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt

/**
* Class to check the different action of users within the CRM and checks if they are permitted to do so 
* Different action could be view a module, view a data, edit/delete data etc.
* @author Abhik Chakraborty
*/

class CRMActionPermission extends DataObject {
	public $table = "";
	public $primary_key = "";
	
	private $standard_action_permissions = array("add"=>1,"edit"=>1,"view"=>2,"delete"=>3);

	/**
	* function to check if the user is allowed to access the module
	* @param integer $idmodule
	* @return boolean
	* @see modules/User/User.class.php
	* NOTE : The user infomation is accessed from the persistent User Object and needs a valid logged in user
	*/
	public function module_access_allowed($idmodule) { 
		if (!is_object($_SESSION["do_user"]) || $_SESSION["do_user"]->iduser == '') return false ;
		if ($_SESSION["do_user"]->is_admin == 1) return true ;
		$retval = false ;
		$user_module_permissions = $_SESSION["do_user"]->get_user_module_privileges();
		if (is_array($user_module_permissions) && count($user_module_permissions) > 0) {
			if (array_key_exists($idmodule,$user_module_permissions)) {
				if ($user_module_permissions[$idmodule]["module_permission"] == 1) {
					$retval = true ;
				} else { $retval = false ; }
			}
		}
		return $retval ;
	}

	/**
	* function to check if action is permitted
	* @param string $action
	* @param integer $idmodule
	* @param integer $sqrecord
	* @see modules/User/User.class.php
	* NOTE : The user infomation is accessed from the persistent User Object and needs a valid logged in user
	*/
	public function action_permitted($action,$idmodule,$sqrecord = '') {
		$retval = false ;
		if (!is_object($_SESSION["do_user"]) || $_SESSION["do_user"]->iduser == '') return false ;
		if ($_SESSION["do_user"]->is_admin == 1) $retval =  true ;
		if ($this->module_access_allowed($idmodule) === false) return false ; 
		if ($_SESSION["do_user"]->is_admin != 1) {
			$module_data_share_permissions = $_SESSION["do_user"]->get_module_data_share_permissions();
			switch ($action) {
				case "add":
					if ($module_data_share_permissions[$idmodule] == 2 || $module_data_share_permissions[$idmodule] == 3) {
						$retval =  true ;
					}
					break;
				case "edit":
					if ($module_data_share_permissions[$idmodule] == 2 || $module_data_share_permissions[$idmodule] == 3) {
						$retval =  true ;
					}
					break;
				case "delete" :
					if ($module_data_share_permissions[$idmodule] == 3) {
						$retval =  true ;
					}
					break;
				case "view":
					if ($module_data_share_permissions[$idmodule] == 1 || $module_data_share_permissions[$idmodule]==2 
					|| $module_data_share_permissions[$idmodule] == 3) {
						$retval = true ;
					}
					break;
				default :
					break;
			}
			$user_module_permissions = $_SESSION["do_user"]->get_user_module_privileges();
			$user_standard_permissions = $user_module_permissions[$idmodule]["standard_permissions"];
			if (array_key_exists($this->standard_action_permissions[$action],$user_standard_permissions)) {
				if ($user_standard_permissions[$this->standard_action_permissions[$action]] == 1) {
					$retval =  true ;
				} else {
					$retval =  false ;
				}
			} else { $retval =  false ;}
		}
		if ($sqrecord != '' && $retval === true) { 
			$sqrecord = (int)$sqrecord ;
			$do_module = new Module();
			$do_module->getId($idmodule);
			$module_name = $do_module->name ;
			$entity_object = new $module_name();
			$entity_object->getId($sqrecord); 
			if ($entity_object->getNumRows() == 0) { 
				$retval = false ;
			} elseif ($entity_object->deleted == 1) { 
				$retval = false ;
			} elseif ($_SESSION["do_user"]->is_admin == 1 && $module_data_share_permissions[$idmodule] != 5) { 
				$retval = true ;
			} elseif ($entity_object->iduser == $_SESSION["do_user"]->iduser) {
				$retval = true ;
			} else { 
				if ($module_data_share_permissions[$idmodule] == 1 && $action == 'view') return true;
				if ($module_data_share_permissions[$idmodule] == 2 && ( $action == 'view' || $action == 'add' || $action == 'edit')) return true;
				if ($module_data_share_permissions[$idmodule] == 3 && ( $action == 'view' || $action == 'add' || $action == 'edit' || $action== 'delete')) return true;
				if ($module_data_share_permissions[$idmodule] == 5) {
					if ($entity_object->iduser == $_SESSION["do_user"]->iduser) {
						return true ;
					} else {
						return false ;
					}
				}
				$subordinate_users = $_SESSION["do_user"]->get_subordinate_users();
				if (is_array($subordinate_users) && count($subordinate_users) >0 && in_array($entity_object->iduser,$subordinate_users)) { 
					$retval = true ;
				} else {
					$user_to_groups = $_SESSION["do_user"]->get_user_associated_to_groups();
					if (is_array($user_to_groups) && count($user_to_groups)> 0) {
						if ($entity_object->module_group_rel_table != '') {
							if (in_array($entity_object->idgroup,$user_to_groups)) { 
								$retval = true ;
							} else { $retval = false ; }
						} else { $retval = false ; }
					} else { $retval = false ; }
				}
			}
		}
		return $retval ;
	}

	/**
	* function to generate the where condition for the user.
	* While displaying data in the list view data may appear from lower level users in the hierarchy.
	* For each user when the condition is to be generated, first get the subordinate user if any
	* And then generate the condition. Each table (entity - contacts,leads,potentials etc) will have 
	* iduser representing who is owner of the record.
	* @param string $entity_table_name
	* @param integer $idmodule
	* @param boolean $subordinate_users_data
	* @param integer $iduser
	* @see modules/User/User.class.php
	*/
	public function get_user_where_condition($entity_table_name,$idmodule,$subordinate_users_data=true,$iduser = '') {
		if ($iduser == '') $iduser = $_SESSION["do_user"]->iduser ;
		$module_data_share_permissions = $_SESSION["do_user"]->get_module_data_share_permissions();
		$where = '';
		//if($idmodule == 7 ) return " where 1=1 ";
		if ($subordinate_users_data === true) {
			if($module_data_share_permissions[$idmodule] == 5) return " AND `".$entity_table_name."`.`iduser` = ".$iduser ;
			if($_SESSION["do_user"]->is_admin == 1) return "";
		} 
		if ($module_data_share_permissions[$idmodule] == 1 || $module_data_share_permissions[$idmodule]==2 || $module_data_share_permissions[$idmodule] == 3) {
			// if the datashare permission is public then display all
			$where = '';
		} elseif($module_data_share_permissions[$idmodule] == 5) {
			$where = " AND `".$entity_table_name."`.`iduser` = ".$iduser ;
		} else {
			if (isset($_SESSION["do_user"]->iduser) && $_SESSION["do_user"]->iduser > 0) {
				$subordinate_users = $_SESSION["do_user"]->get_subordinate_users();
				$user_to_groups = $_SESSION["do_user"]->get_user_associated_to_groups();
			} else {
				$do_user = new User();
				$do_group_user_rel = new GroupUserRelation();
				$subordinate_users = $do_user->get_subordinate_users_by_iduser($iduser);
				$user_to_groups = $do_group_user_rel->get_groups_by_user($iduser);
			}
			$group_qry = false ;
			if (is_array($user_to_groups) && count($user_to_groups)> 0) {
				$do_module = new Module();
				$do_module->getId($idmodule);
				$module_name = $do_module->name ;
				$entity_object = new $module_name();
				if ($entity_object->module_group_rel_table != '') {
					$group_qry = true ;
				}
			}

			if (is_array($subordinate_users) && count($subordinate_users) > 0 && $subordinate_users_data === true) {	
				$unique_subordinate_users = array_unique($subordinate_users);
				$comma_seperated_subordinate_users = implode(",",$unique_subordinate_users);
				if ($group_qry === true) {
					$where = " 
					AND (
								( ".$entity_table_name.".iduser = ".$iduser." 
									OR ".$entity_table_name.".iduser IN (".$comma_seperated_subordinate_users.") 
								)
								OR (".$entity_object->module_group_rel_table.".idgroup in (".implode(",",$user_to_groups).") )
							)" ;
				} else {
					$where = " AND ( ".$entity_table_name.".iduser = ".$iduser." OR ".$entity_table_name.".iduser IN (".$comma_seperated_subordinate_users.") )" ;
				}
			} else {	
				if ($group_qry === true) {
					$where = " AND ( ".$entity_table_name.".iduser = ".$iduser." OR ".$entity_object->module_group_rel_table.".idgroup in (".implode(",",$user_to_groups).") )"; ;
				} else {
					$where = " AND ".$entity_table_name.".iduser = ".$iduser ;
				}
			}
		}
		return $where ;
	}

	/**
	* Function to check the permission related to the setting module data
	* @param string $current_file
	* @param integer $sqrecord
	* @return boolean
	*/
	public function action_permitted_settings($current_file,$sqrecord = '') { 
		$retval = true ;
		if (preg_match("#^group(.*)$#i",$current_file) == 1 && (int)$sqrecord > 0) {
			$do_check = new Group();
			$do_check->getId((int)$sqrecord);
			if ($do_check->getNumRows() == 0) {
				$retval =  false ;
			}
			$do_check->free();
		}

		if (preg_match("#^profile(.*)$#i",$current_file) == 1 && (int)$sqrecord > 0) { 
			$do_check = new Profile();
			$do_check->getId((int)$sqrecord);
			if ($do_check->getNumRows() == 0) {
				$retval =  false ;
			} else {
				if ($do_check->editable == 0 && ( $current_file == 'profile_permissions' || $current_file == 'profile_details')) 
					$retval =  false ;
			}
			$do_check->free();
		}

		if (preg_match("#^roles(.*)$#i",$current_file) == 1) { 
			$idrole = '';
			if ($_GET["parentrole"] != '') {  
				$idrole = $_GET["parentrole"] ;
			} elseif ($_GET["idrole"] != '') {
				$idrole = $_GET["idrole"] ;
			}
			if ($idrole != '') {
				$do_check = new Roles();
				$qry = "select * from `role` where idrole = :idrole";
				$stmt = $do_check->getDbConnection()->prepare($qry);
				$stmt->bindValue(":idrole", $idrole);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					$retval = false ;
				} else {
					$row = $stmt->fetch() ;
					if ($row["editable"] == 0 && ( $current_file == 'roles_edit' || $current_file == 'roles_detail'))  $retval =  false ;
				}
				$do_check->free();
			} 
		}
		return $retval;
	}

	/**
	* function to check the action permission on setting related data 
	* @param string $action
	* @param integer $sqrecord
	* @return boolean
	*/
	public function action_permitted_user($action,$sqrecord = '') {
		$retval = true ;
		if ((int)$sqrecord > 0) {
			$do_check = new User();
			$do_check->getId($sqrecord);
			if ($do_check->getNumRows() == 0) {
				$retval = false ;
			}
		}
		return $retval ;
	}
}