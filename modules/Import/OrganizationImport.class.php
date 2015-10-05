<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Organization 
* @author Abhik Chakraborty
*/ 
	

class OrganizationImport extends Organization {
    
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
  
	/**
	* function to save the imported organization
	* @param object $import_object
	* @param object $do_crm_fields
	* @param array $data
	* @return integer inseted recordid
	*/
	public function import_save($import_object,$crm_fields,$data) {
		$mapped_fields = $import_object->get_mapped_fields();
		$table_entity = 'organization';
		$table_entity_address = 'organization_address';
		$table_entity_custom = 'organization_custom_fld';
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
			if ($field_name == 'member_of') {
				$field_value = $this->map_member_of($field_value) ;
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
			$custom_data_array["idorganization"] = $id_entity;
			$addr_data_array["idorganization"] = $id_entity;
			$this->insert($table_entity_custom,$custom_data_array);
			$this->insert($table_entity_address,$addr_data_array);
			$do_data_history = new DataHistory();
			$do_data_history->add_history($id_entity,6,'add'); 
			$do_data_history->free();
			return $id_entity;
		} else { return false ; }
	}
  
	/**
	* function to get the last imported organizations for listing them
	*/
	public function list_imported_data() {
		$qry = "
		select `organization`.*,
		`organization_address`.*,
		`organization_custom_fld`.*,
		`org_to_grp_rel`.`idgroup`,
		`org2`.`organization_name` as `organization_member_of`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `organization`
		inner join `import` on `import`.`idrecord` = `organization`.`idorganization`
		inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
		inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
		left join `user` on `user`.`iduser` = `organization`.`iduser`
		left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
		left join `group` on `group`.`idgroup` = `org_to_grp_rel`.`idgroup`
		left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` AND `organization`.`member_of` <> 0
		where `organization`.`deleted` = 0 
		AND `import`.`idmodule` = 6
		AND `organization`.`iduser` = ".$_SESSION["do_user"]->iduser."
		AND `import`.`iduser` = ".$_SESSION["do_user"]->iduser ;
		$this->setSqlQuery($qry); 
	}
  
	/**
	* function to map member of for an organization while importing
	* @param string $member_of
	* @return integer idorganization
	*/
	public function map_member_of($member_of) {
		if (strlen($member_of) > 2) {
			$qry = "
			select idorganization 
			from organization 
			where organization_name = ?
			AND deleted = 0
			AND iduser =".$_SESSION["do_user"]->iduser;
			$stmt = $this->getDbConnection()->executeQuery($qry,array(trim($member_of)));
			if ($stmt->rowCount() > 0) {
				$data = $stmt->fetch();
				return $data["idorganization"];
			} else { return 0; }
		} else { return 0 ; }
	}
  
}