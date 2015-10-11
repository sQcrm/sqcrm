<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Organization 
* @author Abhik Chakraborty
*/ 

class Organization extends DataObject {
	public $table = "organization";
	public $primary_key = "idorganization";

	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("organization_name","website","phone","member_of","assigned_to");

	/* Array holding the field values to be displayed by the popup section */
	public $popup_selection_fields = array("organization_name","website","phone","industry","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "organization_name";

	/* default order by in the list view */
	protected $default_order_by = "`organization`.`organization_name`";

	public $module_group_rel_table = "org_to_grp_rel";

	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	/**
	* sets the list view total without filter condition
	* @param integer $tot 
	*/
	public function set_list_tot_rows($tot) {
		$this->list_tot_rows = $tot ;
	}

	/**
	* gets the total num of rows for the list query without filer condition
	* @return list_tot_rows
	*/
	public function get_list_tot_rows() {
		return $this->list_tot_rows ;
	}

	/**
	* function to get the default order by in list value
	* @return default_order_by
	*/
	public function get_default_order_by() {
		return $this->default_order_by ; 
	}
    
	/**
	* gets the list query for display of data
	* @param integer $listid
	* sets the query using the dataobject setSqlQuery() and accessed using getSqlQuery() with persistent Object
	*/
	public function get_list_query($listid = '') {
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
		inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
		inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
		left join `user` on `user`.`iduser` = `organization`.`iduser`
		left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
		left join `group` on `group`.`idgroup` = `org_to_grp_rel`.`idgroup`
		left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` AND `organization`.`member_of` <> 0
		where `organization`.`deleted` = 0 
		";
		$this->setSqlQuery($qry);
	}
    
	/**
	* function getId(), gets the details of the entity by the primary key
	* Its Overwrite of the data object getId()
	* purpose to Overwrite this method is to get the details not just from leads table but also from 
	* the other related tables
	* @param integer $sqcrm_record_id
	*/
	public function getId($sqcrm_record_id) {
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
		inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
		inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
		left join `user` on `user`.`iduser` = `organization`.`iduser`
		left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
		left join `group` on `group`.`idgroup` = `org_to_grp_rel`.`idgroup`
		left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` AND `organization`.`member_of` <> 0
		where `organization`.`idorganization` = ?
		AND `organization`.`deleted` = 0 
		";
		$this->query($qry,array($sqcrm_record_id));
		return $this->next();
	}
    
	/**
	* Event function to add the organization data
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) { 
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('add',6) ; 
		if (true === $permission) {
			$do_crm_fields = new CRMFields();
			$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
			// Insert the data into the related tables - organization,organization_address,organization_custom_fld
			$table_entity = 'organization';
			$table_entity_address = 'organization_address';
			$table_entity_custom = 'organization_custom_fld';
			$table_entity_to_grp = 'org_to_grp_rel';
			
			$entity_data_array = array();
			$custom_data_array = array();
			$addr_data_array = array();
			$assigned_to_as_group = false ;
			
			foreach ($crm_fields as $crm_fields) {
				$field_name = $crm_fields["field_name"];
				if ($field_name == 'assigned_to' && $crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) 
					$field_name = 'iduser' ;
				$field_value = $do_crm_fields->convert_field_value_onsave($crm_fields,$evctl);
				if (is_array($field_value) && count($field_value) > 0) {
					if ($field_value["field_type"] == 15) {
						$value = $field_value["value"];
						$assigned_to_as_group = $field_value["assigned_to_as_group"];
					}
				} else { $value = $field_value ; }
				if ($crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) {
					$entity_data_array[$field_name] = $value ;
				} elseif ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
					$custom_data_array[$field_name] = $value ;
				} elseif ($crm_fields["table_name"] == $table_entity_address && $crm_fields["idblock"] > 0 ) {
					$addr_data_array[$field_name] = $value ;
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
				
				$custom_data_array[$this->primary_key] = $id_entity;
				$addr_data_array[$this->primary_key] = $id_entity;
				$this->insert($table_entity_custom,$custom_data_array);
				$this->insert($table_entity_address,$addr_data_array);
				//If the assigned_to to set as group then it goes to the table org_to_grp_rel
				if ($assigned_to_as_group === true) {
					$this->insert($table_entity_to_grp,array("idorganization"=>$id_entity,"idgroup"=>$group_id));
				}
				// record the data history
				$do_data_history = new DataHistory();
				$do_data_history->add_history($id_entity,(int)$evctl->idmodule,'add'); 
				//record the feed
				$feed_other_assigne = array() ;
				if ($assigned_to_as_group === true) {
					$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
				}
				$do_feed_queue = new LiveFeedQueue();
				$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->organization_name,'add',$feed_other_assigne);
				
				$_SESSION["do_crm_messages"]->set_message('success',_('New Organization has been added successfully ! '));
				$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
				$dis = new Display($next_page);
				$dis->addParam("sqrecord",$id_entity);
				$evctl->setDisplayNext($dis) ; 
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('Operation failed due to query error !'));
				$next_page = $evctl->error_page ;
				$dis = new Display($next_page); 
				$evctl->setDisplayNext($dis) ; 
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to add record ! '));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
    
	/**
	* Event function to update the organization data
	* @param object $evctl
	*/
	public function eventEditRecord(EventControler $evctl) {
		$id_entity = (int)$evctl->sqrecord;
		if ($id_entity > 0 && true === $_SESSION["do_crm_action_permission"]->action_permitted('edit',6,(int)$evctl->sqrecord)) {
			$obj = $this->getId($id_entity);
			$obj = (object)$obj; // convert the data array to Object
			$do_crm_fields = new CRMFields();
			$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
			$table_entity = 'organization';
			$table_entity_address = 'organization_address';
			$table_entity_custom = 'organization_custom_fld';
			$table_entity_to_grp = 'org_to_grp_rel';
			$entity_data_array = array();
			$custom_data_array = array();
			$addr_data_array = array();
			$assigned_to_as_group = false ;
			foreach ($crm_fields as $crm_fields) {
				$field_name = $crm_fields["field_name"];
				$field_value = $do_crm_fields->convert_field_value_onsave($crm_fields,$evctl);
				if (is_array($field_value) && count($field_value) > 0) {
					if ($field_value["field_type"] == 15) {
						$field_name = 'iduser';
						$value = $field_value["value"];
						$assigned_to_as_group = $field_value["assigned_to_as_group"];
						$group_id = $field_value["group_id"];
					}
				} else { $value = $field_value ; }
				if ($crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) {
					$entity_data_array[$field_name] = $value ;
				} elseif ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
					$custom_data_array[$field_name] = $value ;
				} elseif ($crm_fields["table_name"] == $table_entity_address && $crm_fields["idblock"] > 0) {
					$addr_data_array[$field_name] = $value ;
				}
			}
			$this->update(array($this->primary_key=>$id_entity),$table_entity,$entity_data_array);
			//updating the last_modified,last_modified_by
			$q_upd = "
			update `".$this->getTable()."` 
			set `last_modified` = ?,
			`last_modified_by` = ?
			where `".$this->primary_key."` = ?" ;
			$this->query($q_upd,array(date("Y-m-d H:i:s"),$_SESSION["do_user"]->iduser,$id_entity));
										
			if (count($custom_data_array) > 0) {
				$this->update(array($this->primary_key=>$id_entity),$table_entity_custom,$custom_data_array);
			}
			if (count($addr_data_array) > 0) {
				$this->update(array($this->primary_key=>$id_entity),$table_entity_address,$addr_data_array);
			}
			
			if ($assigned_to_as_group === false) {
				$qry_grp_rel = "DELETE from `$table_entity_to_grp` where idorganization = ? LIMIT 1";
				$this->query($qry_grp_rel,array($id_entity));
			} else {
				$qry_grp_rel = "select * from `$table_entity_to_grp` where idorganization = ?";
				$this->query($qry_grp_rel,array($id_entity));
				if ($this->getNumRows() > 0) {
					$this->next();
					$id_grp_rel = $this->idleads_to_grp_rel ;
					$q_upd = "
					update `$table_entity_to_grp` set `idgroup` = ? 
					where `idorg_to_grp_rel` = ? LIMIT 1";
					$this->query($q_upd,array($group_id,$id_grp_rel));
				} else {
					$this->insert($table_entity_to_grp,array("idorganization"=>$id_entity,"idgroup"=>$group_id));
				}
			}
			// Record the history
			$do_data_history = new DataHistory();
			$do_data_history->add_history($id_entity,(int)$evctl->idmodule,'edit');
			$do_data_history->add_history_value_changes($id_entity,(int)$evctl->idmodule,$obj,$evctl);
			
			//record the feed
			$feed_other_assigne = array() ;
			if ($assigned_to_as_group === true) {
				$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
			}
			$do_feed_queue = new LiveFeedQueue();
			$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->organization_name,'edit',$feed_other_assigne);
		
			$_SESSION["do_crm_messages"]->set_message('success',_('Data updated successfully !'));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
			$dis = new Display($next_page);
			$dis->addParam("sqrecord",$id_entity);
			$evctl->setDisplayNext($dis) ; 
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to edit the record ! '));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
	
	/**
	* Event function to get the organization address
	* @param object $evctl
	* @return mixed
	* @see self::get_organization_address_info()
	*/
	function eventGetOrganizationAddress(EventControler $evctl) { 
		if ((int)$evctl->id > 0) {
			echo $this->get_organization_address_info((int)$evctl->id);
		}
	}
	
	/**
	* function to get the organization address 
	* @param integer $idorganization
	* @param string $content_type
	* @return mixed
	*/
	public function get_organization_address_info($idorganization,$content_type='json') {
		$qry = "select * from `organization_address` where `idorganization` = ? ";
		$this->query($qry,array($idorganization));
		$return_array = array();
		if ($this->getNumRows() > 0) {
			$this->next();
			$return_array = array(
				"id"=>$idorganization,
				"org_bill_address"=>$this->org_bill_address,
				"org_ship_address"=>$this->org_ship_address,
				"org_bill_pobox"=>$this->org_bill_pobox,
				"org_ship_pobox"=>$this->org_ship_pobox,
				"org_bill_postalcode"=>$this->org_bill_postalcode,
				"org_ship_postalcode"=>$this->org_ship_postalcode,
				"org_bill_city"=>$this->org_bill_city,
				"org_ship_city"=>$this->org_ship_city,
				"org_bill_state"=>$this->org_bill_state,
				"org_ship_state"=>$this->org_ship_state,
				"org_bill_country"=>$this->org_bill_country,
				"org_ship_country"=>$this->org_ship_country
			);
		}
		if ($content_type == 'json') {
			return json_encode( (count($return_array) > 0 ? $return_array : array("id"=>0)) );
		} else {
			return (count($return_array) > 0 ? $return_array : false ) ;
		}
	}
  
	/**
	* function to get the organization contacts email address
	* @param integer $idorganization
	* @return array
	*/
	public function get_organization_contacts_email($idorganization){
		// get the email fields for the contacts
		$qry = "select field_name from fields where idmodule = 4 and field_type = 7";
		$stmt = $this->getDbConnection()->executeQuery($qry);
		$field_names = array();
		$email_array = array();
		if ($stmt->rowCount() > 0) {
			$select = "select";
			while ($data = $stmt->fetch()) {
				$select.= " c.".$data["field_name"].",";
				$field_names[] = $data["field_name"] ;
			}
			$select .= "c.firstname,c.lastname ";
			$select .="
			from contacts c
			where
			c.deleted = 0
			and c.email_opt_out <> 1
			and c.idorganization = ?
			";
			$stmt = $this->getDbConnection()->executeQuery($select,array($idorganization));
			if ($stmt->rowCount() > 0) {
				while ($data = $stmt->fetch()) {
					$email = array();
					foreach ($field_names as $fieldname) {
						$email[] = $data[$fieldname];
					}
					$email_array[] = array(
						"firstname"=>$data["firstname"],
						"lastname"=>$data["lastname"],
						"email"=>$email
					);
				}
			}
		}
		return $email_array;
	}
  
	/**
	* function to get the related contacts
	* @param integer $idorganization
	*/
	public function get_contacts($idorganization) {
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
		inner join `contacts_address` on `contacts_address`.`idcontacts` = `contacts`.`idcontacts`
		inner join `contacts_custom_fld` on `contacts_custom_fld`.`idcontacts` = `contacts`.`idcontacts`
		left join `user` on `user`.`iduser` = `contacts`.`iduser`
		left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
		left join `group` on `group`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
		inner join `organization` on `organization`.`idorganization` = `contacts`.`idorganization`
		left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` AND `contacts`.`reports_to` <> 0
		where 
		`contacts`.`deleted` = 0
		AND `contacts`.`idorganization` = ".(int)$idorganization;
		$this->setSqlQuery($qry);
	}
    
	/**
	* function to get the related potentials
	* @param integer $idorganization
	*/
	public function get_potentials($idorganization) {
		$qry = "
		Select `potentials`.*,
		`potentials_custom_fld`.*,
		`pot_to_grp_rel`.`idgroup`,
		`potentials_related_to`.idmodule as `potentials_related_to_idmodule`,
		`potentials_related_to`.related_to,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`,
		sqorg.organization_name as `potentials_related_to_value`
		from `potentials`
		inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
		left join `user` on `user`.`iduser` = `potentials`.`iduser`
		left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
		left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
		left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
		inner join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 6
		where 
		`potentials`.`deleted` = 0 
		AND `potentials_related_to`.`related_to` = ".(int)$idorganization;
		$this->setSqlQuery($qry);
	}
    
	/**
	* function to get the related events
	* @param integer $idorganization
	*/
	public function get_related_events($idorganization) {
		$this->getId($idorganization);
		$org_name = $this->organization_name ;
		$qry = "
		Select `events`.*,
		`events_custom_fld`.*,
		`events_to_grp_rel`.`idgroup`,
		`events_related_to`.idmodule as `events_related_to_idmodule`,
		`events_related_to`.related_to,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`,
		'".$org_name."' as `events_related_to_value`
		from `events`
		inner join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
		inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		where 
		`events`.`deleted` = 0 
		AND `events_related_to`.`idmodule` = 6
		AND `events_related_to`.`related_to` = ".(int)$idorganization;
		$this->setSqlQuery($qry);
	}
	
	/**
	* function to get the related quotes for the organization
	* @param integer $idorganization
	*/
	public function get_related_quotes($idorganization) {
		$qry = "
		select `quotes`.*,
		`quotes_custom_fld`.*,
		`quotes_address`.*,
		`organization`.`organization_name` as `org_name`,
		`potentials`.`potential_name`,
		`quotes_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `quotes`
		inner join `quotes_address` on `quotes_address`.`idquotes` = `quotes`.`idquotes`
		inner join `quotes_custom_fld` on `quotes_custom_fld`.`idquotes` = `quotes`.`idquotes`
		left join `user` on `user`.`iduser` = `quotes`.`iduser`
		left join `quotes_to_grp_rel` on `quotes_to_grp_rel`.`idquotes` = `quotes`.`idquotes`
		inner join `organization` on `organization`.`idorganization` = `quotes`.`idorganization`
		left join `group` on `group`.`idgroup` = `quotes_to_grp_rel`.`idgroup`
		left join `potentials` on `potentials`.`idpotentials` = `quotes`.`idpotentials`
		where 
		`quotes`.`deleted` = 0
		and `quotes`.`idorganization` = ".(int)$idorganization;
		$this->setSqlQuery($qry);
	}
	
	/**
	* function to get the related sales order for the organization
	* @param integer $idorganization
	*/
	public function get_related_sales_order($idorganization) {
		$qry = "
		select `sales_order`.*,
		`sales_order_custom_fld`.*,
		`sales_order_address`.*,
		`organization`.`organization_name` as `org_name`,
		concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `contact_name`,
		`potentials`.`potential_name`,
		`quotes`.`subject` as `quote_subject`,
		`sales_order_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `sales_order`
		inner join `sales_order_address` on `sales_order_address`.`idsales_order` = `sales_order`.`idsales_order`
		inner join `sales_order_custom_fld` on `sales_order_custom_fld`.`idsales_order` = `sales_order`.`idsales_order`
		left join `user` on `user`.`iduser` = `sales_order`.`iduser`
		left join `sales_order_to_grp_rel` on `sales_order_to_grp_rel`.`idsales_order` = `sales_order`.`idsales_order`
		inner join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
		left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
		left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
		left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
		left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
		where 
		`sales_order`.`deleted` = 0
		and `sales_order`.`idorganization` = ".(int)$idorganization;
		$this->setSqlQuery($qry);
	}
	
	/**
	* function to get the related invoice for the organization
	* @param integer $idorganization
	*/
	public function get_related_invoice($idorganization) {
		$qry = "
		select `invoice`.*,
		`invoice_custom_fld`.*,
		`invoice_address`.*,
		`organization`.`organization_name` as `org_name`,
		concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `contact_name`,
		`potentials`.`potential_name`,
		`sales_order`.`subject` as `so_subject`,
		`invoice_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `invoice`
		inner join `invoice_address` on `invoice_address`.`idinvoice` = `invoice`.`idinvoice`
		inner join `invoice_custom_fld` on `invoice_custom_fld`.`idinvoice` = `invoice`.`idinvoice`
		left join `user` on `user`.`iduser` = `invoice`.`iduser`
		left join `invoice_to_grp_rel` on `invoice_to_grp_rel`.`idinvoice` = `invoice`.`idinvoice`
		inner join `organization` on `organization`.`idorganization` = `invoice`.`idorganization`
		left join `group` on `group`.`idgroup` = `invoice_to_grp_rel`.`idgroup`
		left join `potentials` on `potentials`.`idpotentials` = `invoice`.`idpotentials`
		left join `sales_order` on `sales_order`.`idsales_order` = `invoice`.`idsales_order`
		left join `contacts` on `contacts`.`idcontacts` = `invoice`.`idcontacts`
		where 
		`invoice`.`deleted` = 0
		and `invoice`.`idorganization` =".(int)$idorganization;
		$this->setSqlQuery($qry);
	}

}
