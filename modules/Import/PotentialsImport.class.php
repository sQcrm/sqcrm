<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Potentials Import
* @author Abhik Chakraborty
*/ 
	

class PotentialsImport extends Potentials {

	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
  
	/**
	* function to save the imported potentials
	* @param object $import_object
	* @param object $do_crm_fields
	* @param array $data
	* @return integer inseted recordid
	*/
  
	public function import_save($import_object,$crm_fields,$data) {
		$mapped_fields = $import_object->get_mapped_fields();
		//print_r($crm_fields);
		$table_entity = 'potentials';
		$table_entity_custom = 'potentials_custom_fld';
		$table_entity_to_grp = 'pot_to_grp_rel';
		$table_entity_related_to = 'potentials_related_to';
    
		$entity_data_array = array();
		$custom_data_array = array();
		$addr_data_array = array();
		$related_to_field = false ;
		
		foreach ($crm_fields as $crm_fields) {
			$field_name = $crm_fields["field_name"];
			if ($field_name == 'related_to') {
				$mapped_field_key = array_search("pot_related_to_organization",$mapped_fields);
				if ($mapped_field_key === false) {
					$mapped_field_key = array_search("pot_related_to_contact",$mapped_fields);
					if ($mapped_field_key !== false) {
						$field_value = $data[$mapped_field_key];
						if ($field_value != '') $related_to_cnt_map = true ;   
					}
				} else {
					$field_value = $data[$mapped_field_key];
					if ($field_value !== '') {
						$related_to_org_map = true ;
					} else {
						$mapped_field_key = array_search("pot_related_to_contact",$mapped_fields);
						if ($mapped_field_key !== false) {
							$field_value = $data[$mapped_field_key];
							if($field_value != '') $related_to_cnt_map = true ;   
						}
					}
				}
				if ($related_to_cnt_map === false && $related_to_org_map === false) return false ;
			} else {
				$mapped_field_key = array_search($field_name,$mapped_fields);
				if ($mapped_field_key !== false) {
					$field_value = $data[$mapped_field_key];
					$field_value = $import_object->format_data_before_save($crm_fields["field_type"],$field_value);
				} else { $field_value = ''; }
			}
      
			if ($field_name == 'assigned_to') { 
				$field_name = 'iduser';
				$field_value = $_SESSION["do_user"]->iduser ;
			}
      
			if ($crm_fields["table_name"] == $table_entity_related_to && $crm_fields["idblock"] > 0) {
				if ($field_name == 'related_to') {
					if ($related_to_org_map === true) {
						$related_to = $this->map_related_to_organization($field_value) ;
						$idmodule_rel_to = 6 ;
						$related_to_field = true ;
					} elseif ($related_to_cnt_map === true) {
						$related_to = $this->map_related_to_contacts($field_value) ;
						$idmodule_rel_to = 4;
						$related_to_field = true ;
					}
				}
			}
			if ($crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) {
				$entity_data_array[$field_name] = $field_value ;
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
			$custom_data_array["idpotentials"] = $id_entity;
			$this->insert($table_entity_custom,$custom_data_array);
			if ($related_to_field === true && (int)$related_to_field > 0) {
				$this->insert(
					$table_entity_related_to,
					array(
						"idpotentials"=>$id_entity,
						"related_to"=>$related_to,
						"idmodule"=>$idmodule_rel_to
					)
				);
			}
			$do_data_history = new DataHistory();
			$do_data_history->add_history($id_entity,5,'add'); 
			$do_data_history->free();
			return $id_entity;
		} else { return false ;}
	}
  
	/**
	* function to map the ralated to (organization) for potentials while importing
	* checks if the organization exists else will add a new one
	* @param string $organization_name
	* @return integer idorganization
	*/
	public function map_related_to_organization($organization_name) {
		if (strlen($organization_name) > 2) {
			$organization_name = trim($organization_name);
			$do_organization = new Organization();
			$qry = "
			select `idorganization` 
			from `organization` 
			where 
			`organization_name` = ?
			AND `deleted` = 0 
			AND `iduser` = ".$_SESSION["do_user"]->iduser;
			$do_organization->query($qry,array($organization_name));
			if ($do_organization->getNumRows() > 0) {
				$do_organization->next();
				return $do_organization->idorganization ;
			} else {
				$do_organization->insert(
					"organization",
					array(
						"organization_name"=>CommonUtils::purify_input($organization_name),
						"iduser"=>$_SESSION["do_user"]->iduser
					)
				);
				$idorganization = $do_organization->getInsertId() ;
				//adding the added_on
				$q_upd = "
				update `organization`
				set `added_on` = '".date("Y-m-d H:i:s")."'
				where `idorganization` = ".$idorganization ;
				$do_organization->query($q_upd);
                  
				$do_organization->insert("organization_custom_fld",array("idorganization"=>$idorganization));
				$do_organization->insert("organization_address",array("idorganization"=>$idorganization));
				$do_data_history = new DataHistory();
				$do_data_history->add_history($idorganization,6,'add'); 
				$do_data_history->free();
				return $idorganization;
			}
		}
	}
  
	/**
	* function to map related to (contacts) for potentials while importing
	* checks if the contact exists else adds a new contact
	* @param string $contact_name
	* @return integer idcontacts
	*/
	public function map_related_to_contacts($contact_name) {
		if (strlen($contact_name) > 2) {
			$contact_name = trim($contact_name);
			$do_contact = new Contacts();
			$qry = "
			select `idcontacts`
			from  `contacts`
			where `deleted` = 0 
			AND iduser = ".$_SESSION["do_user"]->iduser."
			AND 
			(
				concat(firstname,' ',lastname) = ?
				or
				concat(lastname,' ',firstname) = ?
			)
			";
			$do_contact->query($qry,array($contact_name,$contact_name)) ;
			if ($do_contact->getNumRows() > 0) {
				$do_contact->next();
				return $do_contact->idcontacts ;
			} else {
				$contact_name_explode = explode(" ",$contact_name);
				$do_contact->insert(
					"contacts",
					array(
						"firstname"=>CommonUtils::purify_input($contact_name_explode[0]),
						"lastname"=>CommonUtils::purify_input($contact_name_explode[1]),
						"iduser"=>$_SESSION["do_user"]->iduser
					)
				) ;
				$idcontacts = $do_contact->getInsertId();  
				//adding the added_on
				$q_upd = "
				update `contacts` 
				set `added_on` = '".date("Y-m-d H:i:s")."'
				where `idcontacts` = ".$idcontacts ;
				$do_contact->query($q_upd);
					
				$do_contact->insert("contacts_custom_fld",array("idcontacts"=>$idcontacts));
				$do_contact->insert("contacts_address",array("idcontacts"=>$idcontacts));
					
				$do_data_history = new DataHistory();
				$do_data_history->add_history($idcontacts,4,'add'); 
				$do_data_history->free();
				return $idcontacts;
			}
		}
	}
  
	/**
	* function to get the last imported potentials for listing them
	*/
	public function list_imported_data() {
		$qry = "
		Select `potentials`.*,
		`potentials_custom_fld`.*,
		`pot_to_grp_rel`.`idgroup`,
		`potentials_related_to`.idmodule as related_to_idmodule,
		`potentials_related_to`.related_to,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`,
		case 
		when potentials_related_to.related_to not like ''
		Then
		(
			case 
			when sqorg.organization_name not like '' then sqorg.organization_name
			when concat(sqcnt.firstname,' ',sqcnt.lastname) not like '' then concat(sqcnt.firstname,' ',sqcnt.lastname)
			end
		)
		else ''
		end
		as related_to_value
		from `potentials`
		inner join `import` on `import`.`idrecord` = `potentials`.`idpotentials`
		inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
		left join `user` on `user`.`iduser` = `potentials`.`iduser`
		left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
		left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
		left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
		left join `contacts` as sqcnt on sqcnt.idcontacts = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 4
		left join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 6
		where `potentials`.`deleted` = 0 
		AND `import`.`idmodule` = 5
		AND `potentials`.`iduser` = ".$_SESSION["do_user"]->iduser."
		AND `import`.`iduser` = ".$_SESSION["do_user"]->iduser ;         
		$this->setSqlQuery($qry);
	}
}
