<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class LeadConversion
* @author Abhik Chakraborty
*/ 
	

class LeadConversion extends Leads {
	public $table = "leads";
	public $primary_key = "idleads";
	
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

    
	/**
	* Event function to convert lead
	* The conversion process goes as -
	* if a new organization is set to be created then create it and transfer other information and mapped custom fields.
	* if a new contact is set to be created then create it and transfer other information and mapped custom fields.
	* if a new potential is set to be created then create it and transfer other information and mapped custom fields,also
	* see the related to and if organization is created or selected then the related to is set to idorganization, else related
	* to should be idcontacts depending on if its created or selected from the conversion form.
	* if no potential is set to be created then check what is set to be created, and if just organization or contact is set to 
	* be created then create and transfer the data and custom fields mapped information.
	* if both organization and contact is set to be created then the created contact is mapped to the organization which is created
	* or selected.
	* if both organization and contact or one of them is set to be conveted and instead of creating if the values are selected
	* then nothing to be done.
	* @param object $evctl
	* @see popups/convert_lead_modal.php
	*/
	public function eventConvertLeads(EventControler $evctl) { 
		$idleads = (int)$evctl->idleads ;
		$idcontacts = 0;
		$idorganization = 0 ;
		$idpotentials = 0 ;
		$create_potential = false ;
		$create_organization = false ;
		$create_contact = false ;
		$assigned_to_as_group = false ;
		$group_id = 0 ;
		$assigned_to = $evctl->assigned_to_selector;
		if ($assigned_to == 'user') {
			$fld_value = $evctl->user_selector ;
		} else {
			$fld_value = 0 ;
			$group_id = $evctl->group_selector ;
			$assigned_to_as_group = true ;
		}
		$assigned_to_data = array(
			"value"=>$fld_value,
			"assigned_to_as_group"=>$assigned_to_as_group,
			"group_id"=>$group_id
		);
		if ($evctl->pot_convertion == 'on' && true === $_SESSION["do_crm_action_permission"]->action_permitted('add',5)) {
			$create_potential = true;
		}
		if ($evctl->org_convertion == 'on' && true === $_SESSION["do_crm_action_permission"]->action_permitted('add',6)) {
			$create_organization = true;
		}
		if ($evctl->cnt_convertion == 'on' && true === $_SESSION["do_crm_action_permission"]->action_permitted('add',4)) {
			$create_contact = true;
		}
		$do_convert = true ;
		if ($create_organization === true && $evctl->select_org == 'on' && $create_potential === false && $create_contact === false) {
			$do_convert = false ;
		}
		if ($create_contact === true && $evctl->select_cnt == 'on' && $create_potential === false && $create_organization === false) {
			$do_convert = false ;
		}
		if ($do_convert === true) {
			$this->getId($idleads);
			$do_feed_queue = new LiveFeedQueue();
			$do_data_history = new DataHistory();
			$related_identifier_data = array(
				"related_identifier"=>$this->firstname.' '.$this->lastname,
				"related_identifier_idrecord"=>$idleads,
				"related_identifier_idmodule"=>3
			);
			//add to feed queue
			$feed_other_assigne  = array();
			if ($this->idgroup > 0) { 
				$feed_other_assigne = array(
					"related"=>"group",
					"data" => array(
						"key"=>"oldgroup",
						"val"=>$this->idgroup
					)
				); 
			}
			$do_feed_queue->add_feed_queue($idleads,3,$this->firstname.' '.$this->lastname,'lead_covert',$feed_other_assigne);
			// add to data history
			$do_data_history->add_history($idleads,3,'add');
			$do_fields_mapping = new CRMFieldsMapping();
			$qry_mapped_fields = "
			select custom_field_mapping.*,
			fields.field_name as lead_field_name
			from custom_field_mapping
			inner join fields on fields.idfields = custom_field_mapping.mapping_field_id";
			$stmt = $do_fields_mapping->getDbConnection()->executeQuery($qry_mapped_fields);
			$mapped_fields = $stmt->fetchAll();
			$do_custom_field_map = false ;
			if (count($mapped_fields) > 0) $do_custom_field_map = true ;
			if ($create_organization === true) {
				if ($evctl->create_org == 'on') {
					$idorganization = $this->create_new_organization_converted($evctl,$assigned_to_data);
					// add to feed queue
					$feed_other_assigne  = array();
					if ($assigned_to_as_group === true) {
						$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
					}
					$do_feed_queue->add_feed_queue($idorganization,6,$evctl->organization_name,'add_organization_lead_convert',$feed_other_assigne,$related_identifier_data);
					// add to data history
					$do_data_history->add_history($idorganization,6,'add');
					$map_entity = "
					UPDATE 
					`organization`
					set 
					`rating` = ?,
					`phone` = ?,
					`fax` = ? 
					where `idorganization` = ? limit 1 " ;
					$this->getDbConnection()->executeQuery($map_entity,array($this->rating,$this->phone,$this->fax,$idorganization));
					$map_addr = "
					UPDATE 
					`organization_address` 
					set
					`org_bill_address` = ?,
					`org_bill_pobox` = ?,
					`org_bill_city` = ?,
					`org_bill_state` = ?,
					`org_bill_postalcode` = ?,
					`org_bill_country` = ?
					where `idorganization` = ? limit 1 ";
					$this->getDbConnection()->executeQuery(
						$map_addr,
						array(
							$this->street,
							$this->po_box,
							$this->city,
							$this->state,
							$this->postal_code,
							$this->country,
							$idorganization
						)
					);
					if ($do_custom_field_map === true) $this->update_mapped_fields_data(6,$idorganization,$mapped_fields);
				} elseif ($evctl->select_org == 'on') {
					$idorganization = $evctl->idorganization;
				}
			}
			if ($create_contact === true) {
				if ($evctl->create_cnt == 'on') {
					$idcontacts = $this->create_new_contact_converted($evctl,$assigned_to_data,$idorganization);
					// add to feed queue
					$feed_other_assigne  = array();
					if ($assigned_to_as_group === true) {
						$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
					}
					$do_feed_queue->add_feed_queue($idcontacts,4,$evctl->firstname.' '.$evctl->lastname,'add_contact_lead_convert',$feed_other_assigne,$related_identifier_data);
					// add to data history
					$do_data_history->add_history($idcontacts,4,'add');
					$map_entity = "
					UPDATE
					`contacts`
					set 
					`office_phone` = ?,
					`mobile_num` = ?,
					`leadsource` = ?,
					`title` = ?,
					`fax` = ?
					where `idcontacts` = ? limit 1 ";
					$this->getDbConnection()->executeQuery(
						$map_entity,
						array(
							$this->phone,
							$this->mobile,
							$this->leadsource,
							$this->title,
							$this->fax,
							$idcontacts
						)
					);
					$map_addr = "
					UPDATE
					`contacts_address`
					set
					`cnt_mail_street` = ?,
					`cnt_mail_pobox` = ?,
					`cnt_mailing_city` = ?,
					`cnt_mailing_state` = ?,
					`cnt_mailing_postalcode` = ?,
					`cnt_mailing_country` = ?
					where `idcontacts` = ? limit 1 ";
					$this->getDbConnection()->executeQuery(
						$map_addr,
						array(
							$this->street,
							$this->po_box,
							$this->city,
							$this->state,
							$this->postal_code,
							$this->country,
							$idcontacts
						)
					);
					if ($do_custom_field_map === true) $this->update_mapped_fields_data(4,$idcontacts,$mapped_fields);
				} elseif ($evctl->select_cnt == 'on') {
					$idcontacts = $evctl->idcontacts ;
				}
			}
			if ($create_potential === true) {
				if ($idorganization > 0  && $create_organization === true) {
					$related_to = $idorganization ;
					$related_to_module = 6;
				} elseif ($idcontacts > 0 && $create_contact === true) {
					$related_to = $idcontacts ;
					$related_to_module = 4;
				} else {
					$related_to = $idorganization ;
					$related_to_module = 6;
				}
				$idpotentials = $this->create_new_potential_converted($evctl,$assigned_to_data,$related_to,$related_to_module);
				if ($do_custom_field_map === true) $this->update_mapped_fields_data(5,$idpotentials,$mapped_fields);
					// add to feed queue
				$feed_other_assigne  = array();
				if ($assigned_to_as_group === true) {
					$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
				}
				$do_feed_queue->add_feed_queue($idpotentials,5,$evctl->potential_name,'add_potential_lead_convert',$feed_other_assigne,$related_identifier_data);
				// add to data history
				$do_data_history->add_history($idpotentials,5,'add');
			}
			if ($idpotentials > 0) {
				$next_page = NavigationControl::getNavigationLink("Potentials","detail");
				$sqrecord = $idpotentials ;
			} elseif ($idorganization > 0 && $evctl->create_org == 'on') {
				$next_page = NavigationControl::getNavigationLink("Organization","detail");
				$sqrecord = $idorganization ;
			} else {
				$next_page = NavigationControl::getNavigationLink("Contacts","detail");
				$sqrecord = $idcontacts ;
			}
			$this->query("update ".$this->getTable()." set `converted` = 1 where idleads = ? limit 1",array($idleads));
			if ($evctl->create_org == 'on') {
				$idorganization_converted = $idorganization;
			} else { $idorganization_converted = 0 ; }
			if ($evctl->create_cnt == 'on') {
				$idcontacts_converted = $idcontacts ;
			} else { $idcontacts_converted = 0 ; }
			$this->record_lead_conversion_matrix($idleads,$idpotentials,$idorganization_converted,$idcontacts_converted);
			// transfer the related data to the selected one
			if ((int)$evctl->transfer_related_data == 1) {
				$transfer_module_id = 6;
				$idtransfer_to = $idorganization ;
			} elseif ((int)$evctl->transfer_related_data == 2) {
				$transfer_module_id = 4;
				$idtransfer_to = $idcontacts ;
			} elseif ((int)$evctl->transfer_related_data == 3 ) {
				$transfer_module_id = 5;
				$idtransfer_to = $idpotentials ;
			}
			$this->transfer_relateddata_on_conversion($idleads,$idtransfer_to,$transfer_module_id);	
			$dis = new Display($next_page);
			$dis->addParam("sqrecord",$sqrecord);
			$evctl->setDisplayNext($dis) ;
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('No conversion has been done, please try again !'));
		}
	}
    
	/**
	* function to create a new organization during lead conversion
	* @param object $evctl
	* @param array $assigned_to_data
	* @return integer $idorganization
	*/
	public function create_new_organization_converted($evctl,$assigned_to_data) {
		$this->insert(
			"organization",
			array(
				"organization_name"=>$evctl->organization_name,
				"industry"=>$evctl->industry,
				"iduser"=>$assigned_to_data["value"],
				"added_on"=>date("Y-m-d H:i:s")
			)
		);
		$idorganization = $this->getInsertId() ;
		$this->insert("organization_address",array("idorganization"=>$idorganization));
		$this->insert("organization_custom_fld",array("idorganization"=>$idorganization));
		if ($assigned_to_data["assigned_to_as_group"] === true) {
			$this->insert("org_to_grp_rel",array("idorganization"=>$idorganization,"idgroup"=>$assigned_to_data["group_id"]));
		}
		return $idorganization ;              
	}
    
	/**
	* function to create a new contact at the time of conversion
	* @param object $evctl
	* @param array $assigned_to_data
	* @param integer $idorganization
	* @return integer $idcontacts
	*/
	public function create_new_contact_converted($evctl,$assigned_to_data,$idorganization=0) {
		$this->insert(
			"contacts",
			array(
				"firstname"=>$evctl->firstname,
				"lastname"=>$evctl->lastname,
				"email"=>$evctl->email,
				"idorganization"=>$idorganization,
				"iduser"=>$assigned_to_data["value"],
				"added_on"=>date("Y-m-d H:i:s")
			)
		);
		$idcontacts = $this->getInsertId();
		$this->insert("contacts_address",array("idcontacts"=>$idcontacts));
		$this->insert("contacts_custom_fld",array("idcontacts"=>$idcontacts));    
		if ($assigned_to_data["assigned_to_as_group"] === true) {
			$this->insert("cnt_to_grp_rel",array("idcontacts"=>$idcontacts,"idgroup"=>$assigned_to_data["group_id"]));
		}
		return $idcontacts;
	}
    
	/**
	* function to create a new potential at the time of conversion
	* @param object $evctl
	* @param array $assigned_to_data
	* @param integer $related_to
	* @param integer $related_to_module
	* @return integer $idpotentials
	*/
	public function create_new_potential_converted($evctl,$assigned_to_data,$related_to,$related_to_module) {
		$this->insert(
			"potentials",
			array(
				"potential_name"=>$evctl->potential_name,
				"expected_closing_date"=>FieldType9::convert_before_save($evctl->expected_closing_date),
				"sales_stage"=>$evctl->sales_stage,"probability"=>$evctl->probability,
				"amount"=>FieldType30::convert_before_save($evctl->amount),"iduser"=>$assigned_to_data["value"],
				"added_on"=>date("Y-m-d H:i:s"),
				"lost_reason"=>$evctl->lost_reason,
				"competitor_name"=>$evctl->competitor_name
			)
		);
		$idpotentials = $this->getInsertId();
		$this->insert("potentials_custom_fld",array("idpotentials"=>$idpotentials));
		$this->insert("potentials_related_to",array("idpotentials"=>$idpotentials,"related_to"=>$related_to,"idmodule"=>$related_to_module));
		return $idpotentials ;
	}
    
	/**
	* function to transfer the mapped custom fields
	* @param integer $idmodule
	* @param integer $pk_value
	* @param object $do_fields_mapping
	*/
	function update_mapped_fields_data($idmodule,$pk_value,$mapped_fields) {
		$cust_fields = "select * from `fields` where idmodule = ? AND field_name like '%ctf_%'";
		$stmt = $this->getDbConnection()->executeQuery($cust_fields,array($idmodule));
		if ($stmt->rowCount() > 0) {
			$org_cust_fields = array();
			$cnt_cust_fields = array();
			$pot_cust_fields = array();
			$fields = array();
			while ($data = $stmt->fetch()) {
				$fields[$data["idfields"]] = $data["field_name"];
			}
			foreach ($mapped_fields as $mapped_fields) {
				$field_name = $mapped_fields["lead_field_name"];//$do_fields_mapping->lead_field_name ;
				if ($idmodule == 6 && array_key_exists($mapped_fields["organization_mapped_to"],$fields))
					$org_cust_fields[$fields[$mapped_fields["organization_mapped_to"]]] = $this->$field_name;
				elseif ($idmodule == 4 && array_key_exists($mapped_fields["contacts_mapped_to"],$fields))
					$cnt_cust_fields[$fields[$mapped_fields["contacts_mapped_to"]]] = $this->$field_name;
				elseif ($idmodule == 5 && array_key_exists($mapped_fields["potentials_mapped_to"],$fields))
					$pot_cust_fields[$fields[$mapped_fields["potentials_mapped_to"]]] = $this->$field_name;
			}
			if ($idmodule == 6) {
				$this->update(array("idorganization"=>$pk_value),"organization_custom_fld",$org_cust_fields);
			} elseif($idmodule == 4) {
				$this->update(array("idcontacts"=>$pk_value),"contacts_custom_fld",$cnt_cust_fields);
			} elseif ($idmodule == 5) {
				$this->update(array("idpotentials"=>$pk_value),"potentials_custom_fld",$pot_cust_fields);
			} 
		}
	}
    
	/**
	* function to record the conversion matrix for complete conversion information history
	* @param integer $idleads
	* @param integer $idpotentials
	* @param integer $idorganization
	* @param integer $idcontacts
	*/
	public function record_lead_conversion_matrix($idleads,$idpotentials=0,$idorganization=0,$idcontacts=0) {
		$this->insert(
			"leads_conversion_matrix",
			array(
				"idpotentials"=>$idpotentials,
				"idorganization"=>$idorganization,
				"idcontacts"=>$idcontacts,
				"iduser"=>$_SESSION["do_user"]->iduser,
				"conversion_date"=>date("Y-m-d H:i:s"),
				"idleads"=>$idleads
			)
		);
	}
    
	/**
	* function to get the complete conversion information
	* @param integer $idleads
	* @return array if data found else false
	*/
	public function get_conversion_matrix($idleads) {
		$qry = "select * from `leads_conversion_matrix` where `idleads` = ?";
		$this->query($qry,array($idleads));
		if ($this->getNumRows() > 0) {
			$this->next();
			if ((int)$this->idpotentials > 0) {
				$do_potentials = new Potentials();
				$q_p = "
				select `potential_name` 
				from `".$do_potentials->getTable()."` 
				where `idpotentials` = ?" ;
				$do_potentials->query($q_p,array($this->idpotentials));
				if ($do_potentials->getNumRows() > 0) {
					$do_potentials->next();
					$return_array["potential"] = array("idpotentials"=>(int)$this->idpotentials,"potential_name"=>$do_potentials->potential_name); 
				}
			}
			if ((int)$this->idorganization > 0) {
				$do_organization = new Organization();
				$q_o = "
				select `organization_name` 
				from `".$do_organization->getTable()."` 
				where `idorganization` = ?";
				$do_organization->query($q_o,array($this->idorganization));
				if ($do_organization->getNumRows() > 0) {
					$do_organization->next();
					$return_array["organization"] = array("idorganization"=>(int)$this->idorganization,"organization_name"=>$do_organization->organization_name);
				}
			}
			if ((int)$this->idcontacts > 0) {
				$do_contacts = new Contacts();
				$q_c = "
				select `firstname`,`lastname` 
				from `".$do_contacts->getTable()."` where `idcontacts` = ?" ;
				$do_contacts->query($q_c,array($this->idcontacts));
				if ($do_contacts->getNumRows() > 0) {
					$do_contacts->next();
					$return_array["contact"] = array(
						"idcontacts"=>(int)$this->idcontacts,
						"contact_name"=>$do_contacts->firstname.' '.$do_contacts->lastname
					);
				}
			}
			$do_user = new User();
			$do_user->getId((int)$this->iduser);
			$return_array["user"] = array("user_name"=>$do_user->user_name,"fullname"=>$do_user->firstname.' '.$do_user->lastname);
			$return_array["conversion_date"] = array(
				"conversion_date"=>i18nDate::i18n_long_date(TimeZoneUtil::convert_to_user_timezone($this->conversion_date,true),true)
			);
			return $return_array ;
		} else {
			return false ;
		}
	}
    
	/**
	* function to transfer the related information like notes,calendar events etc to a different module entity 
	* while converting the lead.
	* @param integer $idleads
	* @param integer $idtransfer_to
	* @param integer $transfer_module_id
	*/
	public function transfer_relateddata_on_conversion($idleads,$idtransfer_to,$transfer_module_id) {
		// transfer the notes
		$qry = "
		update `notes` 
		set 
		`sqcrm_record_id` = ? ,
		`related_module_id` = ?
		where 
		`sqcrm_record_id` = ?
		AND `related_module_id` = 3";
		$this->query($qry,array($idtransfer_to,$transfer_module_id,$idleads)) ;
		
		// transfer calendar events
		$qry = "
		update `events_related_to`
		set 
		`related_to` = ?,
		`idmodule` = ?
		where 
		`related_to` = ?
		AND `idmodule` = 3";
		$this->query($qry,array($idtransfer_to,$transfer_module_id,$idleads));
	}
}
