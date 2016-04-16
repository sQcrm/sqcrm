<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt

/**
* Class to check the different action of users within the cpanel and checks if they are permitted to do so 
* Different action could be view a module, view a data, edit/delete data etc.
* @author Abhik Chakraborty
*/
namespace cpanel_actionpermissions ;
class CRMActionPermission extends \DataObject {
	public $table = "";
	public $primary_key = "";
	public $cpanel_modules = array() ;
	public $full_module_details = array() ;
	public $cpanel_user_modules = array() ;
	
	/**
	* function to check if the customer support user is allowed to access the module
	* @param integer $idmodule
	* @return boolean
	* NOTE : The user infomation is accessed from the persistent Customer Support User Object and needs a valid logged in user
	*/
	public function module_access_allowed($idmodule) { 
		if (!is_object($_SESSION["do_cpaneluser"]) || $_SESSION["do_cpaneluser"]->idcpanel_user == '') return false ;
		$retval = false ;
		if (in_array($idmodule,$this->cpanel_user_modules)) {
			$retval = true ;
		}
		return $retval ;
	}

	/**
	* function to load the activated modules for the cpanel
	* @param integer $idorganization
	* @return void
	*/
	public function load_cpanel_modules() {
		$qry = "
		select m.* from module m 
		join cpanel_modules cm on cm.idmodule = m.idmodule
		" ;
		$stmt = $this->getDbConnection()->executeQuery($qry);
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$this->full_module_details[$data["idmodule"]] = array(
					"name" => $data["name"] ,
					"module_label" => $data["module_label"]
				) ;
				$this->cpanel_modules[$data["name"]] = $data["idmodule"] ;
			}
			$this->full_module_details[7] = array(
				"name" => "User" ,
				"module_label" => "User"
			) ;
			$this->cpanel_modules["User"] = 7 ;
			$this->cpanel_modules["Home"] = 1 ;
		}
	}
	
	/**
	* function to load the cpanel user accessible modules
	* @param integer $idorganization
	* @return void
	*/
	public function load_cpanel_user_modules($idorganization) {
		$qry = "
		select m.* from module m 
		join cpanel_modules_org cm on cm.idmodule = m.idmodule
		where cm.idorganization = ?
		" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idorganization));
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$this->cpanel_user_modules[] = $data["idmodule"] ;
			}
			$this->cpanel_user_modules[] = 1 ;
			$this->cpanel_user_modules[] = 7 ;
		}
	}
	
	/**
	* function to get the cpanel user modules
	* @return array
	*/
	public function get_cpanel_user_modules() {
		return $this->cpanel_user_modules ;
	}
	
	/**
	* function to get the cpanel modules
	* @return array
	*/
	public function get_cpanel_modules() {
		return $this->cpanel_modules ;
	}
	
	/**
	* function to check if the action is permitted for the cpanel user
	* @param string $action
	* @param integer $idmodule
	* @param integer $sqrecord
	*/
	public function action_permitted($action,$idmodule,$sqrecord = 0) {
		if (!is_object($_SESSION["do_cpaneluser"]) || $_SESSION["do_cpaneluser"]->idcpanel_user == '') return false ;
		$retval = false ;
		if (false === $this->module_access_allowed($idmodule)) return false ; 
		$idcontact = $_SESSION["do_cpaneluser"]->idcontacts ;
		$idorganization = $_SESSION["do_cpaneluser"]->idorganization ;
		if ((int)$sqrecord > 0) {
			$module_name = '\\'.$this->full_module_details[$idmodule]["name"] ;
			$entity_object = new $module_name() ;
			$entity_object->getId($sqrecord) ;
			if ($entity_object->getNumRows() > 0) {
				if (property_exists($entity_object,'values') && array_key_exists('idcontacts',$entity_object->values)) { 
					if ($entity_object->idcontacts == $idcontact) {
						$retval = true ;
					} else {
						$subordinate_contacts = $_SESSION["do_cpaneluser"]->get_subordinate_contacts() ;
						if (is_array($subordinate_contacts) && count($subordinate_contacts) > 0 && in_array($idcontact,$subordinate_contacts)) {
							$retval = true ;
						}
					}
				} elseif (property_exists($entity_object,'values') && array_key_exists('idorganization',$entity_object->values) && $entity_object->idorganization == $idorganization) {
					$retval = true ;
				}
			}
		} else { 
			$retval = true ;
		}
		return $retval ;
	}
	
	/**
	* function to generate the security where condition for the portal users
	* @param string $entity_table_name
	* @param string $lookup_field_name
	* @param boolean $subordinate_users_data 
	* @NOTE : while making a call to this function it should be noted that if the table has both idcontacts and idorganization then
	* the $lookup_field_name should be passed as idcontacts else it will not follow any hierarchy rule for portal users if there is any
	*/
	public function get_cpanal_user_where_condition($entity_table_name,$lookup_field_name,$subordinate_users_data=true) {
		$idcontact = $_SESSION["do_cpaneluser"]->idcontacts ;
		$idorganization = $_SESSION["do_cpaneluser"]->idorganization ;
		$subordinate_contacts = array() ;
		$where = '';
		if (true ===$subordinate_users_data) {
			$subordinate_contacts = $_SESSION["do_cpaneluser"]->get_subordinate_contacts() ;
		}
		if ($lookup_field_name == 'idcontacts') {
			if (count($subordinate_contacts) > 0) {
				$subordinate_contacts_comma_separated = implode(',',$subordinate_contacts) ;
				$where .= " AND 
				`$entity_table_name`.`$lookup_field_name` = $idcontact
				OR (
				`$entity_table_name`.`$lookup_field_name` in (".$subordinate_contacts_comma_separated.")
				)
				";
			} else {
				$where .= " AND `$entity_table_name`.`$lookup_field_name` = $idcontact" ;
			}
		} elseif ($lookup_field_name == 'idorganization') {
			$where .= " AND `$entity_table_name`.`$lookup_field_name` = $idorganization" ;
		}
		return $where ;
	}
}