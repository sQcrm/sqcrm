<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class SalesOrder 
* @author Abhik Chakraborty
*/ 

class SalesOrder extends DataObject {
	public $table = "sales_order";
	public $primary_key = "idsales_order";

	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("subject","idorganization","sales_order_status","assigned_to");

	/* Array holding the field values to be displayed by the popup section */
	public $popup_selection_fields = array("subject","idorganization","sales_order_status","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "subject";

	/* default order by in the list view */
	protected $default_order_by = "`sales_order`.`idsales_order`";

	public $module_group_rel_table = "sales_order_to_grp_rel";
	
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
		left join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
		left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
		left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
		left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
		left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
		where `sales_order`.`deleted` = 0
		";
		$this->setSqlQuery($qry);
	}
    
	/**
	* function getId(), gets the details of the entity by the primary key
	* Its Overwrite of the data object getId()
	* purpose to Overwrite this method is to get the details not just from entity table but also from 
	* the other related tables
	* @param integer $sqcrm_record_id
	*/
	public function getId($sqcrm_record_id) {
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
		left join `organization` on `organization`.`idorganization` = `sales_order`.`idorganization`
		left join `group` on `group`.`idgroup` = `sales_order_to_grp_rel`.`idgroup`
		left join `potentials` on `potentials`.`idpotentials` = `sales_order`.`idpotentials`
		left join `quotes` on `quotes`.`idquotes` = `sales_order`.`idquotes`
		left join `contacts` on `contacts`.`idcontacts` = `sales_order`.`idcontacts`
		where `sales_order`.`deleted` = 0 and `sales_order`.`idsales_order` = ?
		";
		$this->query($qry,array($sqcrm_record_id));
		return $this->next();
	}
    
	/**
	* Event function to add the SalesOrder Data
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) { 
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('add',14) ; 
		if (true === $permission) {
			$do_process_plugins = new CRMPluginProcessor() ;
			$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,1);
			if (strlen($do_process_plugins->get_error()) > 2) {
				$_SESSION["do_crm_messages"]->set_message('error',$do_process_plugins->get_error());
				$next_page = NavigationControl::getNavigationLink($evctl->module,"add");
				$dis = new Display($next_page);
				$evctl->setDisplayNext($dis) ;
			} else {
				$do_crm_fields = new CRMFields();
				$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
				// Insert the data into the related tables 
				$table_entity = 'sales_order';
				$table_entity_custom = 'sales_order_custom_fld';
				$table_entity_to_grp = 'sales_order_to_grp_rel';
				$table_entity_address = 'sales_order_address';
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
							$group_id = $field_value["group_id"] ;
						} elseif ($field_value["field_type"] == 12) {
							$value = $field_value["name"];
							$avatar_array[] = $field_value ;
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
				//add to entity table
				$this->insert($table_entity,$entity_data_array);
				$id_entity = $this->getInsertId() ;
				if ($id_entity > 0) {
					//update quotes other information
					$upd_array= array(
						"sales_order_number"=>$id_entity,
						"sales_order_key"=>md5(microtime().$id_entity),
						"terms_condition"=>$evctl->terms_cond,
						"net_total"=>$evctl->net_total_lines,
						"discount_type"=>$evctl->final_discount_type,
						"discount_value"=>$evctl->final_discount_val,
						"discounted_amount"=>$evctl->final_discounted_total,
						"tax_values"=>$evctl->final_tax_val,
						"taxed_amount"=>$evctl->final_tax_amount,
						"shipping_handling_charge"=>$evctl->final_ship_hand_charge,
						"shipping_handling_tax_values"=>$evctl->final_ship_hand_tax_val,
						"shipping_handling_taxed_amount"=>$evctl->final_ship_hand_tax_amount,
						"final_adjustment_type"=>$evctl->final_adjustment,
						"final_adjustment_amount"=>$evctl->final_adjustment_val,
						"grand_total"=>$evctl->grand_total
					);
					$this->update(array($this->primary_key=>$id_entity),$table_entity,$upd_array);
							
					//adding the added_on
					$u_qry = "
					update `".$this->getTable()."` 
					set 
					`added_on` = ? 
					where `".$this->primary_key."` = ?
					";
					$this->query($u_qry,array(date("Y-m-d H:i:s"),$id_entity));
					$custom_data_array["idsales_order"] = $id_entity;
					$addr_data_array["idsales_order"] = $id_entity;
					$this->insert($table_entity_custom,$custom_data_array);
					$this->insert($table_entity_address,$addr_data_array);
					//If the assigned_to to set as group then it goes to the table entity group relation table
					if ($assigned_to_as_group === true) {
						$this->insert($table_entity_to_grp,array("idsales_order"=>$id_entity,"idgroup"=>$group_id));
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
					$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->subject,'add',$feed_other_assigne);
					
					$do_line_items = new Lineitems();
					$param_array = (array)$evctl ;
					$do_line_items->add_line_items((int)$evctl->idmodule,$id_entity,$param_array["params"]);
					
					// process after add plugin
					$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,2,$id_entity);
					
					$_SESSION["do_crm_messages"]->set_message('success',_('New Sales Order has been added successfully ! '));
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
		$param_array = (array)$evctl ;
		$id_entity = (int)$evctl->sqrecord;
		if ($id_entity > 0 && true === $_SESSION["do_crm_action_permission"]->action_permitted('edit',14,(int)$evctl->sqrecord)) {
			$obj = $this->getId($id_entity);
			$obj = (object)$obj; // convert the data array to Object
			$do_process_plugins = new CRMPluginProcessor() ;
			// process before update plugin. If any error is raised display that.
			$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,3,$id_entity,$obj);
			if (strlen($do_process_plugins->get_error()) > 2) {
				$_SESSION["do_crm_messages"]->set_message('error',$do_process_plugins->get_error());
				$next_page = NavigationControl::getNavigationLink($evctl->module,"edit");
				$dis = new Display($next_page);
				$dis->addParam("sqrecord",$id_entity);
				if ($evctl->return_page != '') { 
					$dis->addParam("return_page",$evctl->return_page);
				}
				$evctl->setDisplayNext($dis) ;
			} else {
				$do_crm_fields = new CRMFields();
				$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
				$table_entity = 'sales_order';
				$table_entity_custom = 'sales_order_custom_fld';
				$table_entity_to_grp = 'sales_order_to_grp_rel';
				$table_entity_address = 'sales_order_address';
				$entity_data_array = array();
				$custom_data_array = array();
				$addr_data_array = array();
				$assigned_to_as_group = false ;
				foreach ($crm_fields as $crm_fields) {
					$field_name = $crm_fields["field_name"];
					$field_value = $do_crm_fields->convert_field_value_onsave($crm_fields,$evctl,'edit');
					if (is_array($field_value) && count($field_value) > 0) {
						if ($field_value["field_type"] == 15) {
							$field_name = 'iduser';
							$value = $field_value["value"];
							$assigned_to_as_group = $field_value["assigned_to_as_group"];
							$group_id = $field_value["group_id"];
						} elseif ($field_value["field_type"] == 12) {
							$value = $field_value["name"];
							$avatar_array[] = $field_value ;
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
				
				//update quotes other information
				$upd_array= array(
					"terms_condition"=>$evctl->terms_cond,
					"net_total"=>$evctl->net_total_lines,
					"discount_type"=>$evctl->final_discount_type,
					"discount_value"=>$evctl->final_discount_val,
					"discounted_amount"=>$evctl->final_discounted_total,
					"tax_values"=>$evctl->final_tax_val,
					"taxed_amount"=>$evctl->final_tax_amount,
					"shipping_handling_charge"=>$evctl->final_ship_hand_charge,
					"shipping_handling_tax_values"=>$evctl->final_ship_hand_tax_val,
					"shipping_handling_taxed_amount"=>$evctl->final_ship_hand_tax_amount,
					"final_adjustment_type"=>$evctl->final_adjustment,
					"final_adjustment_amount"=>$evctl->final_adjustment_val,
					"grand_total"=>$evctl->grand_total
				);
				$this->update(array($this->primary_key=>$id_entity),$table_entity,$upd_array);
				
				//updating the last_modified,last_modified_by
				$u_qry = "
				update `".$this->getTable()."` 
				set 
				`last_modified` = ? ,
				`last_modified_by` = ? 
				where `".$this->primary_key."` = ?
				";
				$this->query($u_qry,array(date("Y-m-d H:i:s"),$_SESSION["do_user"]->iduser,$id_entity));
											
				if (count($custom_data_array) > 0) {
					$this->update(array($this->primary_key=>$id_entity),$table_entity_custom,$custom_data_array);
				}
				if (count($addr_data_array) > 0) {
					$this->update(array($this->primary_key=>$id_entity),$table_entity_address,$addr_data_array);
				}
							
				if ($assigned_to_as_group === false) {
					$qry_grp_rel = "DELETE from `$table_entity_to_grp` where `idsales_order` = ? LIMIT 1";
					$this->query($qry_grp_rel,array($id_entity));
				} else {
					$qry_grp_rel = "select * from `$table_entity_to_grp` where `idsales_order` = ?";
					$this->query($qry_grp_rel,array($id_entity));
					if ($this->getNumRows() > 0) {
						$this->next();
						$id_grp_rel = $this->idsales_order_to_grp_rel ;
						$u_qry = "
						update `$table_entity_to_grp` 
						set 
						`idgroup` = ?
						where `idsales_order_to_grp_rel` = ? LIMIT 1
						";
						$this->query($u_qry,array($group_id,$id_grp_rel));
					} else {
						$this->insert($table_entity_to_grp,array("idsales_order"=>$id_entity,"idgroup"=>$group_id));
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
				$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->product_name,'edit',$feed_other_assigne);
				
				$do_line_items = new Lineitems();
				$param_array = (array)$evctl ;
				$do_line_items->edit_line_items((int)$evctl->idmodule,$id_entity,$param_array["params"]);
				
				// process after update plugin
				$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,4,$id_entity,$obj);
				
				$_SESSION["do_crm_messages"]->set_message('success',_('Data updated successfully !'));
				$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
				$dis = new Display($next_page);
				$dis->addParam("sqrecord",$id_entity);
				$evctl->setDisplayNext($dis) ; 
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to edit the record ! '));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}	
	}
	
	
	/**
	* event function to send sales order by email
	* @param object $evctl
	*/
	public function sendSalesOrderWithEmail(EventControler $evctl) {
		$record_id = $evctl->idsales_order ;
		$sales_order_email = $evctl->sales_order_email ;
		$crm_global_settings = new CRMGlobalSettings();
		$inventory_prefixes = $crm_global_settings->get_inventory_prefixes();
		$company_address = $crm_global_settings->get_setting_data_by_name('company_address');
		$export_inventory = new ExportInventoryData();
		if ((int)$record_id > 0) {
			if (is_array($sales_order_email) && count($sales_order_email) > 0) {
				$email_template = new EmailTemplate("send_sales_order_email");
				$emailer = new SQEmailer();
				$pdf_filename = $export_inventory->generate_inventory_pdf((int)$record_id,14,true);
				foreach ($sales_order_email as $key=>$val) {
					$val_exploded = explode(':::',$val);
					$to_email = $val_exploded[0];
					$name = $val_exploded[1];
					$name_explode = explode('::',$name);
					$email_data = array(
						"sales_order_number"=>$inventory_prefixes["salesorder_num_prefix"].$record_id,
						"company_name"=>CRM_NAME,
						"firstname"=>( (array_key_exists(0,$name_explode))? $name_explode[0]:''),
						"lastname"=>( (array_key_exists(1,$name_explode))? $name_explode[1]:''),
						"company_address"=>nl2br($company_address)
					);
					$to_name = ( (array_key_exists(0,$name_explode))? $name_explode[0]:'').' '.( (array_key_exists(1,$name_explode))? $name_explode[1]:'');
					$emailer->IsSendmail();
					$emailer->setEmailTemplate($email_template);
					$emailer->mergeArray($email_data);
					$emailer->AddAddress($to_email, $to_name);
					$emailer->AddAttachment(OUTBOUND_PATH.'/'.$pdf_filename);
					$emailer->send();
					$_SESSION["do_crm_messages"]->set_message('success',_('Email has been sent !'));
					$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
					$dis = new Display($next_page);
					$dis->addParam("sqrecord",$record_id);
					$evctl->setDisplayNext($dis) ; 
				}
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('Operation failed! No email id specified.'));
				$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
				$dis = new Display($next_page);
				$dis->addParam("sqrecord",$record_id);
				$evctl->setDisplayNext($dis) ; 
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('Operation failed! No record id specified.'));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
			$dis = new Display($next_page);
			$dis->addParam("sqrecord",$record_id);
			$evctl->setDisplayNext($dis) ; 
		}
	}
}