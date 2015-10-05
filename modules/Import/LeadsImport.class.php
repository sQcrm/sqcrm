<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Leads
* @author Abhik Chakraborty
*/ 
	

class LeadsImport extends Leads {
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
  
	/**
	* function to save the imported leads
	* @param object $import_object
	* @param object $do_crm_fields
	* @param array $data
	* @return integer inseted recordid
	*/
	public function import_save($import_object,$crm_fields,$data) {
		$mapped_fields = $import_object->get_mapped_fields();
		$table_entity = 'leads';
		$table_entity_address = 'leads_address';
		$table_entity_custom = 'leads_custom_fld';
    
		$entity_data_array = array();
		$custom_data_array = array();
		$addr_data_array = array();
		foreach ($crm_fields as $crm_fields) {
			$field_name = $crm_fields["field_name"];
			$mapped_field_key = array_search($field_name,$mapped_fields);
			if ($mapped_field_key !== false) {
				$field_value = $data[$mapped_field_key];
				$field_value = $import_object->format_data_before_save($crm_fields["field_type"],$field_value);
			} else { $field_value = ''; }
			if ($field_name == 'assigned_to') { 
				$field_name = 'iduser';
				$field_value = $_SESSION["do_user"]->iduser ;
			}
			if ($crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) {
				$entity_data_array[$field_name] = $field_value ;
			}
			if ($crm_fields["table_name"] == $table_entity_address && $crm_fields["idblock"] > 0) {
				$addr_data_array[$field_name] = $field_value ;
			}
			if ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
				$custom_data_array[$field_name] = $field_value ;
			}
		}
		$this->insert($table_entity,$entity_data_array);
		$id_entity = $this->getInsertId() ;
		if ($id_entity > 0) {
			//adding the added_on
			$q_upd = "
			update `".$this->getTable()."` 
			set `added_on` = ? 
			where `".$this->primary_key."` = ?" ;
			$this->query($q_upd,array(date("Y-m-d H:i:s"),$id_entity));
			$custom_data_array["idleads"] = $id_entity;
			$addr_data_array["idleads"] = $id_entity;
			$this->insert($table_entity_custom,$custom_data_array);
			$this->insert($table_entity_address,$addr_data_array);
			$do_data_history = new DataHistory();
			$do_data_history->add_history($id_entity,3,'add'); 
			$do_data_history->free();
			return $id_entity;
		} else { return false ; }
	}
  
	/**
	* function to get the last imported leads for listing them
	*/
	public function list_imported_data() {
		$qry = "
		select `leads`.*,`leads_address`.*,`leads_custom_fld`.*,`leads_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `leads`
		inner join `import` on `import`.`idrecord` = `leads`.`idleads`
		inner join `leads_address` on `leads_address`.`idleads` = `leads`.`idleads`
		inner join `leads_custom_fld` on `leads_custom_fld`.`idleads` = `leads`.`idleads`
		left join `user` on `user`.`iduser` = `leads`.`iduser`
		left join `leads_to_grp_rel` on `leads_to_grp_rel`.`idleads` = `leads`.`idleads`
		left join `group` on `group`.`idgroup` = `leads_to_grp_rel`.`idgroup`
		where `leads`.`deleted` = 0 
		AND `leads`.`converted` = 0  
		AND `import`.`idmodule` = 3 
		AND `leads`.`iduser` = ".$_SESSION["do_user"]->iduser."
		AND `import`.`iduser` = ".$_SESSION["do_user"]->iduser ;        
		$this->setSqlQuery($qry);    
	}
   
}