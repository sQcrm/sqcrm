<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Leads
* @author Abhik Chakraborty
*/ 
	

class Leads extends DataObject {
	public $table = "leads";
	public $primary_key = "idleads";
	
	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("firstname","lastname","email","lead_status","assigned_to");

	/* Array holding the field values to be displayed by the popup section for the Leads */
	public $popup_selection_fields = array("firstname","lastname","email","lead_status","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "firstname,lastname";
	
	/* default order by in the list view */
	protected $default_order_by = "`leads`.`lastname`";
	
	public $module_group_rel_table = "leads_to_grp_rel";
	
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
		select `leads`.*,`leads_address`.*,`leads_custom_fld`.*,`leads_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `leads`
		inner join `leads_address` on `leads_address`.`idleads` = `leads`.`idleads`
		inner join `leads_custom_fld` on `leads_custom_fld`.`idleads` = `leads`.`idleads`
		left join `user` on `user`.`iduser` = `leads`.`iduser`
		left join `leads_to_grp_rel` on `leads_to_grp_rel`.`idleads` = `leads`.`idleads`
		left join `group` on `group`.`idgroup` = `leads_to_grp_rel`.`idgroup`
		where `leads`.`deleted` = 0 ";
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
		select `leads`.*,`leads_address`.*,`leads_custom_fld`.*,`leads_to_grp_rel`.`idgroup`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `leads`
		inner join `leads_address` on `leads_address`.`idleads` = `leads`.`idleads`
		inner join `leads_custom_fld` on `leads_custom_fld`.`idleads` = `leads`.`idleads`
		left join `user` on `user`.`iduser` = `leads`.`iduser`
		left join `leads_to_grp_rel` on `leads_to_grp_rel`.`idleads` = `leads`.`idleads`
		left join `group` on `group`.`idgroup` = `leads_to_grp_rel`.`idgroup`
		where `leads`.`idleads` = ? AND `leads`.`deleted` = 0 ";
		$this->query($qry,array($sqcrm_record_id));
		return $this->next();
	}
	
	/**
	* Event function to add the leads data
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) { 
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('add',3) ; 
		if (true === $permission) {
			$do_process_plugins = new CRMPluginProcessor() ;
			$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl);
			//echo $do_process_plugins->get_error();exit;
			if (strlen($do_process_plugins->get_error()) > 2) {
				$_SESSION["do_crm_messages"]->set_message('error',$do_process_plugins->get_error());
				$next_page = NavigationControl::getNavigationLink($evctl->module,"add");
				$dis = new Display($next_page);
				$evctl->setDisplayNext($dis) ;
			} else {
				$do_crm_fields = new CRMFields();
				$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
				// Insert the data into the 3 lead related tables - leads,leads_address,leads_custom_fld
				$table_entity = 'leads';
				$table_entity_address = 'leads_address';
				$table_entity_custom = 'leads_custom_fld';
				$table_entity_to_grp = 'leads_to_grp_rel';
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
						}
					} else { $value = $field_value ; }
					if ($crm_fields["table_name"] == $table_entity && $crm_fields["idblock"] > 0) {
						$entity_data_array[$field_name] = $value ;
					} elseif ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
						$custom_data_array[$field_name] = $value ;
					} elseif($crm_fields["table_name"] == $table_entity_address && $crm_fields["idblock"] > 0 ) {
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
					//If the assigned_to to set as group then it goes to the table `leads_to_grp_rel`
					if ($assigned_to_as_group === true) {
						$this->insert($table_entity_to_grp,array("idleads"=>$id_entity,"idgroup"=>$group_id));
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
					$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->firstname.' '.$evctl->lastname,'add',$feed_other_assigne);
					
					$_SESSION["do_crm_messages"]->set_message('success',_('New lead has been added successfully ! '));
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
	* Event function to update the leads data
	* @param object $evctl
	*/
	public function eventEditRecord(EventControler $evctl) {
		$id_entity = (int)$evctl->sqrecord;
		if ($id_entity > 0 && true === $_SESSION["do_crm_action_permission"]->action_permitted('edit',3,(int)$evctl->sqrecord)) {
			$obj = $this->getId($id_entity);
			$obj = (object)$obj; // convert the data array to Object
			$do_crm_fields = new CRMFields();
			$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
			$table_entity = 'leads';
			$table_entity_address = 'leads_address';
			$table_entity_custom = 'leads_custom_fld';
			$table_entity_to_grp = 'leads_to_grp_rel';
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
				} elseif ($crm_fields["table_name"] == $table_entity_address && $crm_fields["idblock"] > 0 ) {
					$addr_data_array[$field_name] = $value ;
				}
			}
			$this->update(array($this->primary_key=>$id_entity),$table_entity,$entity_data_array);
			//updating the last_modified,last_modified_by
			$q_upd = "
			update `".$this->getTable()."` 
			set `last_modified` = ?,
			`last_modified_by` = ?
			where `".$this->primary_key."` = ?";
			$this->query($q_upd,array(date("Y-m-d H:i:s"),$_SESSION["do_user"]->iduser,$id_entity));
			
			if (count($custom_data_array) > 0) {
				$this->update(array($this->primary_key=>$id_entity),$table_entity_custom,$custom_data_array);
			}
			if (count($addr_data_array) > 0) {
				$this->update(array($this->primary_key=>$id_entity),$table_entity_address,$addr_data_array);
			}
			if ($assigned_to_as_group === false) {
				$qry_grp_rel = "DELETE from `$table_entity_to_grp` where idleads = ? LIMIT 1";
				$this->query($qry_grp_rel,array($id_entity));
			} else {
				$qry_grp_rel = "select * from `$table_entity_to_grp` where idleads = ?";
				$this->query($qry_grp_rel,array($id_entity));
				if ($this->getNumRows() > 0) {
					$this->next();
					$id_grp_rel = $this->idleads_to_grp_rel ;
					$q_upd = "
					update `$table_entity_to_grp` set `idgroup` = ? 
					where `idleads_to_grp_rel` = ? LIMIT 1" ;
					$this->query($q_upd,array($group_id,$id_grp_rel));
				} else {
					$this->insert($table_entity_to_grp,array("idleads"=>$id_entity,"idgroup"=>$group_id));
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
			$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->firstname.' '.$evctl->lastname,'edit',$feed_other_assigne);
		
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
	* function to get the events related to leads by idleads
	* @param integer $idleads
	*/
	public function get_related_events($idleads) {
		$this->getId((int)$idleads);
		$lead_name = $this->firstname.' '.$this->lastname ;
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
		'".$lead_name."' as `events_related_to_value`
		from `events`
		inner join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
		inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		where 
		`events`.`deleted` = 0 
		AND `events_related_to`.`idmodule` = 3
		AND `events_related_to`.`related_to` = ".(int)$idleads;
		$this->setSqlQuery($qry);
	}
}