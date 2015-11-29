<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Vendor 
* @author Abhik Chakraborty
*/ 
	

class Vendor extends DataObject {
	public $table = "vendor";
	public $primary_key = "idvendor";
	
	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("vendor_name","email","phone","website","assigned_to");

	/* Array holding the field values to be displayed by the popup section */
	public $popup_selection_fields = array("vendor_name","email","phone","website","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "vendor_name";
	
	/* default order by in the list view */
	protected $default_order_by = "`vendor`.`vendor_name`";
	
	public $module_group_rel_table = "vendor_to_grp_rel";

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
		select `vendor`.*,
		`vendor_address`.*,
		`vendor_custom_fld`.*,
		`vendor_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `vendor`
		inner join `vendor_address` on `vendor_address`.`idvendor` = `vendor`.`idvendor`
		inner join `vendor_custom_fld` on `vendor_custom_fld`.`idvendor` = `vendor`.`idvendor`
		left join `user` on `user`.`iduser` = `vendor`.`iduser`
		left join `vendor_to_grp_rel` on `vendor_to_grp_rel`.`idvendor` = `vendor`.`idvendor`
		left join `group` on `group`.`idgroup` = `vendor_to_grp_rel`.`idgroup`
		where `vendor`.`deleted` = 0 ";
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
		select `vendor`.*,
		`vendor_address`.*,
		`vendor_custom_fld`.*,
		`vendor_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `vendor`
		inner join `vendor_address` on `vendor_address`.`idvendor` = `vendor`.`idvendor`
		inner join `vendor_custom_fld` on `vendor_custom_fld`.`idvendor` = `vendor`.`idvendor`
		left join `user` on `user`.`iduser` = `vendor`.`iduser`
		left join `vendor_to_grp_rel` on `vendor_to_grp_rel`.`idvendor` = `vendor`.`idvendor`
		left join `group` on `group`.`idgroup` = `vendor_to_grp_rel`.`idgroup`
		where `vendor`.`idvendor` = ?
		AND `vendor`.`deleted` = 0 ";
		$this->query($qry,array($sqcrm_record_id));
		return $this->next();
	}
    
	/**
	* Event function to add the vendor data
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) {
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('add',11) ; 
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
				$table_entity = 'vendor';
				$table_entity_address = 'vendor_address';
				$table_entity_custom = 'vendor_custom_fld';
				$table_entity_to_grp = 'vendor_to_grp_rel';    
				$entity_data_array = array();
				$custom_data_array = array();
				$addr_data_array = array();
				$assigned_to_as_group = false ;
				foreach ($crm_fields as $crm_fields) {
					$field_name = $crm_fields["field_name"];
					if ($field_name == 'assigned_to' && $crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0)
						$field_name = 'iduser' ;
					$field_value = $do_crm_fields->convert_field_value_onsave($crm_fields,$evctl);
					if (is_array($field_value) && count($field_value) > 0 ) {
						if ($field_value["field_type"] == 15) {
							$value = $field_value["value"];
							$assigned_to_as_group = $field_value["assigned_to_as_group"];
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
				//add to organization table
				$this->insert($table_entity,$entity_data_array);
				$id_entity = $this->getInsertId() ;
				if ($id_entity > 0) {
					//adding the added_on
					$q_upd = "
					update `".$this->getTable()."` set 
					`added_on` = ? 
					where `".$this->primary_key."` = ?";
					$this->query($q_upd,array(date("Y-m-d H:i:s"),$id_entity));
					$custom_data_array["idvendor"] = $id_entity;
					$addr_data_array["idvendor"] = $id_entity;
					$this->insert($table_entity_custom,$custom_data_array);
					$this->insert($table_entity_address,$addr_data_array);
					//If the assigned_to to set as group then it goes to the table entity group relation table
					if ($assigned_to_as_group === true) {
						$this->insert($table_entity_to_grp,array("idvendor"=>$id_entity,"idgroup"=>$group_id));
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
					$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->vendor_name,'add',$feed_other_assigne);
					
					// process after add plugin
					$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,2,$id_entity);
					
					$_SESSION["do_crm_messages"]->set_message('success',_('New Vendor has been added successfully ! '));
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
		$id_entity = (int)$evctl->sqrecord;
		if ($id_entity > 0 && true === $_SESSION["do_crm_action_permission"]->action_permitted('edit',11,(int)$evctl->sqrecord)) {
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
				$table_entity = 'vendor';
				$table_entity_address = 'vendor_address';
				$table_entity_custom = 'vendor_custom_fld';
				$table_entity_to_grp = 'vendor_to_grp_rel'; 
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
					}
					if ($crm_fields["table_name"] == $table_entity_address && $crm_fields["idblock"] > 0) {
						$addr_data_array[$field_name] = $value ;
					}
					if ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
						$custom_data_array[$field_name] = $value ;
					}
				}
				$this->update(array($this->primary_key=>$id_entity),$table_entity,$entity_data_array);
				//updating the last_modified,last_modified_by
				$q_upd = "
				update `".$this->getTable()."` set 
				`last_modified` = ? ,
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
					$qry_grp_rel = "DELETE from `$table_entity_to_grp` where idvendor = ? LIMIT 1";
					$this->query($qry_grp_rel,array($id_entity));
				} else {
					$qry_grp_rel = "select * from `$table_entity_to_grp` where idvendor = ?";
					$this->query($qry_grp_rel,array($id_entity));
					if ($this->getNumRows() > 0) {
						$this->next();
						$id_grp_rel = $this->idvendor_to_grp_rel ;
						$q_upd = "
						update `$table_entity_to_grp` set 
						`idgroup` = ?
						where `idvendor_to_grp_rel` = ? LIMIT 1" ;
						$this->query($q_upd,array($group_id,$id_grp_rel));
					} else {
						$this->insert($table_entity_to_grp,array("idvendor"=>$id_entity,"idgroup"=>$group_id));
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
				$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->vendor_name,'edit',$feed_other_assigne);
				
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
	* function to get the related purchase order for vendor
	* @param integer $idvendor
	*/
	public function get_related_purchase_order($idvendor) {
		$qry = "
		select `purchase_order`.*,
		`purchase_order_custom_fld`.*,
		`purchase_order_address`.*,
		`vendor`.`vendor_name`,
		concat(`contacts`.`firstname`,' ',`contacts`.`lastname`) as `contact_name`,
		`purchase_order_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `purchase_order`
		inner join `purchase_order_address` on `purchase_order_address`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
		inner join `purchase_order_custom_fld` on `purchase_order_custom_fld`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
		left join `user` on `user`.`iduser` = `purchase_order`.`iduser`
		left join `purchase_order_to_grp_rel` on `purchase_order_to_grp_rel`.`idpurchase_order` = `purchase_order`.`idpurchase_order`
		inner join `vendor` on `vendor`.`idvendor` = `purchase_order`.`idvendor`
		left join `group` on `group`.`idgroup` = `purchase_order_to_grp_rel`.`idgroup`
		left join `contacts` on `contacts`.`idcontacts` = `purchase_order`.`idcontacts`
		where 
		`purchase_order`.`deleted` = 0 
		and `purchase_order`.`idvendor` = ".(int)$idvendor;
		$this->setSqlQuery($qry);
	}
}