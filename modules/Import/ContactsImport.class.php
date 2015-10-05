<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Contacts Import
* @author Abhik Chakraborty
*/ 
	

class ContactsImport extends Contacts {
    
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
  
  
	/**
	* function to save the imported contacts
	* @param object $import_object
	* @param object $do_crm_fields
	* @param array $data
	* @return integer inseted recordid
	*/
	public function import_save($import_object,$crm_fields,$data) {
		$mapped_fields = $import_object->get_mapped_fields();
		$table_entity = 'contacts';
		$table_entity_address = 'contacts_address';
		$table_entity_custom = 'contacts_custom_fld';
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
				if ($field_name == 'reports_to') {
					$field_value = $this->map_contact_report_to($field_value) ;
				} elseif ($field_name == 'idorganization') {
					// get the idorganization by val else gets a new id by adding the record
					$field_value = $this->map_contact_organization($field_value,$import_object,$data) ;
				}
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
			where `".$this->primary_key."` = ?";
			$this->query($q_upd,array(date("Y-m-d H:i:s"),$id_entity));
			$custom_data_array["idcontacts"] = $id_entity;
			$addr_data_array["idcontacts"] = $id_entity;
			$this->insert($table_entity_custom,$custom_data_array);
			$this->insert($table_entity_address,$addr_data_array);
			
			$do_data_history = new DataHistory();
			$do_data_history->add_history($id_entity,4,'add'); 
			$do_data_history->free();
			return $id_entity;
		} else { return false ; }
	}
  
	/**
	* function to get the last imported contacts for listing them
	*/
	public function list_imported_data() {
		$qry = "
		select `contacts`.*,
		`contacts_address`.*,
		`contacts_custom_fld`.*,
		`cnt_to_grp_rel`.`idgroup`,
		`organization`.`organization_name` as `org_name`,
		concat(`cnt2`.firstname,' ',`cnt2`.lastname) as `contact_report_to`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `contacts`
		inner join `import` on `import`.`idrecord` = `contacts`.`idcontacts`
		inner join `contacts_address` on `contacts_address`.`idcontacts` = `contacts`.`idcontacts`
		inner join `contacts_custom_fld` on `contacts_custom_fld`.`idcontacts` = `contacts`.`idcontacts`
		left join `user` on `user`.`iduser` = `contacts`.`iduser`
		left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
		left join `group` on `group`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
		left join `organization` on `organization`.`idorganization` = `contacts`.`idorganization`
		left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` AND `contacts`.`reports_to` <> 0
		where 
		`contacts`.`deleted` = 0 
		AND `import`.`idmodule` = 4 
		AND `contacts`.`iduser` = ".$_SESSION["do_user"]->iduser."
		AND `import`.`iduser` = ".$_SESSION["do_user"]->iduser;
		$this->setSqlQuery($qry);
	}
  
	/**
	* function to map organization to contact while importing
	* checks if the organization already exists else add a new one
	* @param string $organization_name
	* @param object $import_object
	* @param array $data
	* @return integer idorganization
	*/
	public function map_contact_organization($organization_name,$import_object,$data) {
		$qry = "
		select idorganization 
		from organization 
		where organization_name = ?
		AND deleted = 0
		AND iduser =".$_SESSION["do_user"]->iduser;
		$stmt = $this->getDbConnection()->executeQuery($qry,array(trim($organization_name)));
		if ($stmt->rowCount() > 0) {
			$rs = $stmt->fetch();
			return $rs["idorganization"];
		} else {
			if (strlen($organization_name) > 2) {
				$mapped_fields = $import_object->get_mapped_fields();
				$do_organization = new Organization();
				$do_organization->insert(
					"organization",
					array(
						"organization_name"=>CommonUtils::purify_input($organization_name),
						"iduser"=>$_SESSION["do_user"]->iduser
					)
				);
				$idorganization = $do_organization->getInsertId() ;
				$q_upd = "
				update `organization`
				set `added_on` = ?
				where `idorganization` = ?";
				$do_organization->query($q_upd,array(date("Y-m-d H:i:s"),$idorganization));
				$do_organization->insert("organization_custom_fld",array("idorganization"=>$idorganization));
				$mapped_fields = $import_object->get_mapped_fields();
				if (array_search("cnt_mail_street",$mapped_fields) !== false) {
					$org_bill_address = $data[array_search("cnt_mail_street",$mapped_fields)] ;
				} else { $org_bill_address = ''; }
				
				if (array_search("cnt_mail_pobox",$mapped_fields) !== false) {
					$org_bill_pobox = $data[array_search("cnt_mail_pobox",$mapped_fields)] ;
				} else { $org_bill_pobox = ''; }
				
				if (array_search("cnt_mailing_city",$mapped_fields) !== false) {
					$org_bill_city = $data[array_search("cnt_mailing_city",$mapped_fields)] ;
				} else { $org_bill_city = ''; }
				
				if (array_search("cnt_mailing_state",$mapped_fields) !== false) {
					$org_bill_state = $data[array_search("cnt_mailing_state",$mapped_fields)] ;
				} else { $org_bill_state = ''; }
				
				if( array_search("cnt_mailing_postalcode",$mapped_fields) !== false) {
					$org_bill_postalcode = $data[array_search("cnt_mailing_postalcode",$mapped_fields)] ;
				} else { $org_bill_postalcode = ''; }
				
				if (array_search("cnt_mailing_country",$mapped_fields) !== false) {
					$org_bill_country = $data[array_search("cnt_mailing_country",$mapped_fields)] ;
				} else { $org_bill_country = ''; }
				$do_organization->insert(
					"organization_address",
					array(
						"idorganization"=>$idorganization,
						"org_bill_address"=>CommonUtils::purify_input($org_bill_address),
						"org_bill_pobox"=>CommonUtils::purify_input($org_bill_pobox),
						"org_bill_city"=>CommonUtils::purify_input($org_bill_city),
						"org_bill_state"=>CommonUtils::purify_input($org_bill_state),
						"org_bill_postalcode"=>CommonUtils::purify_input($org_bill_postalcode),
						"org_bill_country"=>CommonUtils::purify_input($org_bill_country)
					)		
				);
				$do_data_history = new DataHistory();
				$do_data_history->add_history($idorganization,6,'add'); 
				$do_data_history->free();
				return  $idorganization;
			} else { return 0 ; }
		}
	}
  
	/**
	* function to map reports to for contact while importing the data
	* @param string $report_to
	* @return integer idcontacts
	*/
	public function map_contact_report_to($report_to) {
		$qry = "
		select 
		`idcontacts` 
		from `contacts` 
		where
		deleted = 0 
		AND
		iduser = ".$_SESSION["do_user"]->iduser."
		AND 
		(
			concat(firstname,' ',lastname) = ?
			or
			concat(lastname,' ',firstname) = ?
		)
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($report_to,$report_to));
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			return $data["idcontacts"];
		} else {
			return 0 ;
		} 
	}
}