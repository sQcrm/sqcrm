<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Module
* Maintain the Module information of crm
* @author Abhik Chakraborty
*/


class Module extends DataObject {
	public $table = "module";
	public $primary_key = "idmodule";
	/* Array holding all the active modules*/
	public $active_modules = array();

	public $modules_full_details = array();

	private $lead_mapping_modules = array(4,5,6);

	/**
	* function to get all the active modules except the User
	*/
	public function get_all_active_module() {
		$this->query("select * from ".$this->getTable()." where active = 1 AND idmodule NOT IN (7)");
	}

	/**
	* function to load all the active modules
	* @see module.php
	* sets the array containing the module and and id in the array active_modules
	*/
	public function load_active_modules() {
		$active_modules = array();
		$all_info = array();
		$qry = "select * from ".$this->getTable()." where active = :active" ;
		$stmt = $this->getDbConnection()->prepare($qry);
		$stmt->bindValue(":active", 1);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$active_modules[$row["name"]] = $row["idmodule"] ;
			$module[$row["idmodule"]]["name"] = $row["name"];
			$module[$row["idmodule"]]["label"] = $row["module_label"]; 
			$module[$row["idmodule"]]["menu_item"] = $row["menu_item"]; 
		}
		$this->active_modules = $active_modules ;
		$this->modules_full_details = $module ;
	}
    
	/**
	* gets the active modules loaded
	* @return active_modules
	* @see Module::load_active_modules
	*/
	public function get_active_modules_for_crm() {
		return $this->active_modules ;
	}
    
	/**
	* function to get the modules with full information
	* the value is set in the function Module :: load_active_modules()
	* @return $modules_full_details
	* @see Module :: load_active_modules()
	*/
	public function get_modules_with_full_info() {
		return $this->modules_full_details ;
	}

	/**
	* function to get the module id by name
	* @param string $name
	* @param object $object
	*/
	public function get_idmodule_by_name($name,$object='') {
		if (is_object($object)) {
			$active_modules = $object->get_active_modules_for_crm();
		} else {
			$this->load_active_modules();
			$active_modules = $this->get_active_modules_for_crm();
		}
		if (array_key_exists($name,$active_modules)) {
			return $active_modules[$name];
		} else {
			return false ;
		}
	}
    
	/**
	* function to get the lead mapping modules
	* @return lead_mapping_modules
	* @see modules/Settings/custom_field_list.php  
	*/
	public function get_lead_mapping_modules() {
		return $this->lead_mapping_modules ;
	}
}
