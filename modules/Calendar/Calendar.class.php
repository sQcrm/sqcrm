<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Calendar
* @author Abhik Chakraborty
*/ 
	
class Calendar extends DataObject {
	public $table = "events";
	public $primary_key = "idevents";

	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("subject","event_type","start_date","end_date","event_status","related_to","assigned_to");

	/* Array holding the field values to be displayed by the popup section for the Potential */
	public $popup_selection_fields = array("subject","event_type","start_date","end_date","event_status","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "subject";

	/* default order by in the list view */
	protected $default_order_by = "`events`.`start_date` desc ";

	public $module_group_rel_table = "events_to_grp_rel";
 
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
		Select `events`.*,
		`events_custom_fld`.*,
		`events_to_grp_rel`.`idgroup`,
		`events_related_to`.idmodule as `events_related_to_idmodule`,
		`events_related_to`.`related_to`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`,
		case 
		when `events_related_to`.`related_to` not like ''
		Then
		(
			case 
			when sqorg.organization_name not like '' then sqorg.organization_name
			when concat(sqcnt.firstname,' ',sqcnt.lastname) not like '' then concat(sqcnt.firstname,' ',sqcnt.lastname)
			when concat(sqleads.firstname,' ',sqleads.lastname) not like '' then concat(sqleads.firstname,' ',sqleads.lastname)
			when sqpot.potential_name not like '' then sqpot.potential_name
			end
		)
		else ''
		end
		as `events_related_to_value`
		from `events`
		inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		left join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
		left join `leads` as sqleads on sqleads.idleads = `events_related_to`.`related_to` and  `events_related_to`.`idmodule` =3
		left join `contacts` as sqcnt on sqcnt.idcontacts = `events_related_to`.`related_to` and `events_related_to`.`idmodule` = 4
		left join `organization` as sqorg on sqorg.idorganization = `events_related_to`.`related_to` and `events_related_to`.`idmodule` = 6
		left join `potentials` as sqpot on sqpot.idpotentials = `events_related_to`.`related_to` and `events_related_to`.`idmodule` = 5
		where `events`.`deleted` = 0 
		";
		$this->setSqlQuery($qry);
	}
    
	/**
	* function getId(), gets the details of the entity by the primary key
	* Its Overwrite of the data object getId()
	* purpose to Overwrite this method is to get the details not just from potentials table but also from 
	* the other related tables
	* @param integer $sqcrm_record_id
	*/
	public function getId($sqcrm_record_id) {
		$qry = "
		Select `events`.*,
		`events_custom_fld`.*,
		`events_to_grp_rel`.`idgroup`,
		`events_related_to`.idmodule as `events_related_to_idmodule`,
		`events_related_to`.`related_to`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`,
		case 
		when events_related_to.related_to not like ''
		Then
		(
			case 
			when sqorg.organization_name not like '' then sqorg.organization_name
			when concat(sqcnt.firstname,' ',sqcnt.lastname) not like '' then concat(sqcnt.firstname,' ',sqcnt.lastname)
			when concat(sqleads.firstname,' ',sqleads.lastname) not like '' then concat(sqleads.firstname,' ',sqleads.lastname)
			when sqpot.potential_name not like '' then sqpot.potential_name
			end
		)
		else ''
		end
		as `events_related_to_value`
		from `events`
		inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		left join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
		left join `leads` as sqleads on sqleads.idleads = `events_related_to`.`related_to` and  `events_related_to`.`idmodule` =3
		left join `contacts` as sqcnt on sqcnt.idcontacts = `events_related_to`.`related_to` and `events_related_to`.`idmodule` = 4
		left join `organization` as sqorg on sqorg.idorganization = `events_related_to`.`related_to` and `events_related_to`.`idmodule` = 6
		left join `potentials` as sqpot on sqpot.idpotentials = `events_related_to`.`related_to` and `events_related_to`.`idmodule` = 5
		where `events`.`deleted` = 0 AND `events`.`idevents` = ?";
		$this->query($qry,array($sqcrm_record_id));
		return $this->next();
	}
    
	/**
	* Event function to add the Potential data
	* @param object $evctl
	* Adds the event first and then check if recurrent event is set then add the recurrent events
	* Also check if event alert is set then add that also.
	* @see RecurrentEvents::get_recurrent_dates()
	*/
	public function eventAddRecord(EventControler $evctl) { 
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('add',2);
		if (true === $permission) {
			$do_process_plugins = new CRMPluginProcessor() ;
			// process before add plugin and if error is raised on plugin display error
			$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,1);
			if (strlen($do_process_plugins->get_error()) > 2) {
				$_SESSION["do_crm_messages"]->set_message('error',$do_process_plugins->get_error());
				$next_page = NavigationControl::getNavigationLink($evctl->module,"add");
				$dis = new Display($next_page);
				$evctl->setDisplayNext($dis) ;
			} else {
				$do_crm_fields = new CRMFields();
				$fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
				$idevents = $this->add_events($evctl,$fields);
				if (false === $idevents) {
					$next_page = $evctl->error_page ;
					$dis = new Display($next_page); 
					$evctl->setDisplayNext($dis) ; 
				} else {
					if ($evctl->event_repeat == 'on') {
						$do_recurrent_event = new RecurrentEvents();
						$recurrent_dates = $do_recurrent_event->get_recurrent_dates($evctl);
						$recurrent_pattern = $do_recurrent_event->get_recurrent_event_pattern() ;
						if (is_array($recurrent_dates) && count($recurrent_dates) > 0) {
							foreach ($recurrent_dates as $recurrent_dates) {
								$idevents_rec = $this->add_events($evctl,$fields);
								$qry = "
								update ".$this->getTable()." set 
								`start_date` = ? ,
								`end_date` = ?,
								`parent_recurrent_event_id` = ? 
								where `idevents` = ? ";
								$this->getDbConnection()->executeQuery($qry,array($recurrent_dates,$recurrent_dates,$idevents,$idevents_rec));
							}
							$this->insert('recurrent_events',array('idevents'=>$idevents,'recurrent_pattern'=>json_encode($recurrent_pattern)));
						}
					}
					if ($evctl->event_alert == 'on') {
						$do_events_reminder = new EventsReminder() ;
						$do_events_reminder->add_event_reminder($idevents,$evctl);
					}
					// process after add plugin
					$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,2,$idevents);
					
					$_SESSION["do_crm_messages"]->set_message('success',_('Event has been added successfully ! '));
					$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
					$dis = new Display($next_page);
					$dis->addParam("sqrecord",$idevents);
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
	* function to add the event
	* @param object $evctl
	* @param object $crm_fields
	* @return idevents
	*/
	public function add_events($evctl,$crm_fields) {
		$table_entity = 'events';
		$table_entity_custom = 'events_custom_fld';
		$table_entity_to_grp = 'events_to_grp_rel';
		$table_entity_related_to = 'events_related_to';
		$entity_data_array = array();
		$custom_data_array = array();
		$cnt_entity_custom = 0 ;
		$related_to_field = false ;
		$assigned_to_as_group = false ;
		$do_crm_fields = new CRMFields();
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
			}
			if ($crm_fields["table_name"] == $table_entity_related_to && $crm_fields["idblock"] > 0) {
				if ((int)$field_value > 0) {
					$related_to_field = true;
					$related_to = $value ;
					$idmodule_rel_to = $evctl->related_to_opt ;
				}
			}
		}
		// add data to the main entity table events
		$this->insert($table_entity,$entity_data_array);
		$id_entity = $this->getInsertId() ;
		if ($id_entity > 0) {
			//adding the added_on
			$q_upd = "
			update `".$this->getTable()."` set 
			`added_on` = ? 
			where `".$this->primary_key."` = ?" ;
			$this->query($q_upd,array(date("Y-m-d H:i:s"),$id_entity));
			$custom_data_array["idevents"] = $id_entity;
			$this->insert($table_entity_custom,$custom_data_array);
			//If the assigned_to to set as group then it goes to the table groups related to
			if ($assigned_to_as_group === true) {
				$this->insert($table_entity_to_grp,array('idevents'=>$id_entity,'idgroup'=>$group_id));
			}
			if ($related_to_field === true) {
				$this->insert($table_entity_related_to,array('idevents'=>$id_entity,'related_to'=>$related_to,'idmodule'=>$idmodule_rel_to));
			}
			// Finally record the data history
			$do_data_history = new DataHistory();
			$do_data_history->add_history($id_entity,(int)$evctl->idmodule,'add'); 
			
			//record the feed
			$feed_other_assigne = array() ;
			if ($assigned_to_as_group === true) {
				$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
			}
			$do_feed_queue = new LiveFeedQueue();
			$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->subject,'add',$feed_other_assigne);
			return $id_entity;        
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('Operation failed due to query error !'));
			return false ;
		}
	}
    
	/**
	* Event function to update the data
	* @param object $evctl
	*/  
	public function eventEditRecord(EventControler $evctl) {
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('edit',2,(int)$evctl->sqrecord) ; 
		if (true === $permission) {
			$do_process_plugins = new CRMPluginProcessor() ;
			// process before update plugin. If any error is raised display that.
			$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,3,(int)$evctl->sqrecord,(object)$this->getId((int)$evctl->sqrecord));
			if (strlen($do_process_plugins->get_error()) > 2) {
				$_SESSION["do_crm_messages"]->set_message('error',$do_process_plugins->get_error());
				$next_page = NavigationControl::getNavigationLink($evctl->module,"edit");
				$dis = new Display($next_page); 
				$dis->addParam("sqrecord",(int)$evctl->sqrecord); 
				if ($evctl->return_page != '') { 
					$dis->addParam("return_page",$evctl->return_page);
				}
				$evctl->setDisplayNext($dis) ;
			} else {
				$do_crm_fields = new CRMFields();
				$fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
				// edit the event
				$idevents = $this->edit_event($evctl,$fields);
				if (false === $idevents) { // if error re-direct
					$next_page = $evctl->error_page ;
					$dis = new Display($next_page);
					$evctl->setDisplayNext($dis) ; 
				} else {
					$do_recurrent_event = new RecurrentEvents();
					$has_recurrent_events  = $do_recurrent_event->has_recurrent_events($idevents);
					if ($evctl->event_repeat == 'on') { 
						$recurrent_dates = $do_recurrent_event->get_recurrent_dates($evctl);
						$recurrent_pattern = $do_recurrent_event->get_recurrent_event_pattern() ;
						if (is_array($recurrent_dates) && count($recurrent_dates) > 0) {
							$add_recurrent_events = false ;
							// if existing recurrent events found and its not eqaul to the submitted one 
							if (false !== $has_recurrent_events && trim(json_encode($recurrent_pattern)) != trim($has_recurrent_events)) {
								$this->delete_related_recurrent_events($idevents); 
								$do_recurrent_event->delete_recurrent_pattern($idevents) ;
								$add_recurrent_events = true ;
							} elseif ($has_recurrent_events === false) {
								$add_recurrent_events = true ;
							}
							if (true === $add_recurrent_events) {
								foreach ($recurrent_dates as $recurrent_dates) {
									$idevents_rec = $this->add_events($evctl,$fields);
									$qry = "
									update ".$this->getTable()." set 
									`start_date` = ?, 
									`end_date` = ?,
									`parent_recurrent_event_id` = ? 
									where `idevents` = ?";
									$stmt = $this->getDbConnection()->executeQuery($qry,array($recurrent_dates,$recurrent_dates,$idevents,$idevents_rec)) ;
								}
								$this->insert('recurrent_events',array('idevents'=>$idevents,'recurrent_pattern'=>json_encode($recurrent_pattern)));
							}
						}
					} else {
						if(false !== $recurrent_pattern){
							$this->delete_related_recurrent_events($idevents);
							$do_recurrent_event->delete_recurrent_pattern($idevents) ;
						}
					}
					// Event reminder
					$do_events_reminder = new EventsReminder();
					if ($evctl->event_alert == 'on') {
						$do_events_reminder->update_event_reminder($idevents,$evctl);
					} else {
						if (false !== $do_events_reminder->get_event_reminder($idevents)) {
							$do_events_reminder->delete_event_reminder($idevents);
						}
					}
					
					// process after update plugin
					$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,4,$idevents,(object)$this->getId($idevents));
					
					$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
					$dis = new Display($next_page);
					$dis->addParam("sqrecord",$idevents);
					$evctl->setDisplayNext($dis) ; 
				}
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to edit the record ! '));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
    
	/**
	* function to edit the event
	* @param object $evctl
	* @param object $do_crm_fields
	*/
	public function edit_event($evctl,$crm_fields) {
		$id_entity = (int)$evctl->sqrecord;
		if ($id_entity > 0) {
			$obj = $this->getId($id_entity);
			$obj = (object)$obj; // convert the data array to Object
			$table_entity = 'events';
			$table_entity_custom = 'events_custom_fld';
			$table_entity_to_grp = 'events_to_grp_rel';
			$table_entity_related_to = 'events_related_to';
			$entity_data_array = array();
			$custom_data_array = array();
			$assigned_to_as_group = false ;
			$related_to_field = false ;
			$do_crm_fields = new CRMFields();
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
				}
				if ($crm_fields["table_name"] == $table_entity_custom && $crm_fields["idblock"] > 0) {
					$custom_data_array[$field_name] = $value ; 
				}
				if ($crm_fields["table_name"] == $table_entity_related_to && $crm_fields["idblock"] > 0) {
					if ((int)$field_value > 0) {
						$related_to_field = true;
						$related_to = $value ;
						$idmodule_rel_to = $evctl->related_to_opt ;
					}
				}
			}
			$this->update(array("idevents"=>$id_entity),$table_entity,$entity_data_array);
			//updating the last_modified,last_modified_by
			$q_upd = "
			update `".$this->getTable()."` set 
			`last_modified` = ? ,
			`last_modified_by` = ?
			where `".$this->primary_key."` = ?" ;
			$this->query($q_upd, array(date("Y-m-d H:i:s"),$_SESSION["do_user"]->iduser,$id_entity));
			if (count($custom_data_array) > 0) {
				$this->update(array("idevents"=>$id_entity),$table_entity_custom,$custom_data_array);
			}
			if ($assigned_to_as_group === false) {
				$qry_grp_rel = "DELETE from `$table_entity_to_grp` where `idevents` = ? LIMIT 1";
				$this->query($qry_grp_rel,array($id_entity));
			} else {
				$qry_grp_rel = "select * from `$table_entity_to_grp` where `idevents` = ?";
				$this->query($qry_grp_rel,array($id_entity));
				if ($this->getNumRows() > 0) {
					$this->next();
					$id_grp_rel = $this->idevents_to_grp_rel ;
					$q_upd = "
					update `$table_entity_to_grp` set 
					`idgroup` = ?
					where `idevents_to_grp_rel` = ? LIMIT 1" ;
					$this->query($q_upd,array($group_id,$id_grp_rel));
				} else {
					$this->insert($table_entity_to_grp,array("idevents"=>$id_entity,"idgroup"=>$group_id));
				}
			}
			if ($related_to_field === true) {
				$this->query("select * from `$table_entity_related_to` where idevents = ?",array($id_entity));
				if ($this->getNumRows() > 0) {
					$q_upd = "
					update `$table_entity_related_to` set 
					`related_to` = ?,
					`idmodule` = ?
					where `idevents` = ? limit 1 " ;
					$this->query($q_upd,array($related_to,$idmodule_rel_to,$id_entity));
				} else {
					$this->insert($table_entity_related_to,array("idevents"=>$id_entity,"related_to"=>$related_to,"idmodule"=>$idmodule_rel_to));
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
			$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->subject,'edit',$feed_other_assigne);
			
			$_SESSION["do_crm_messages"]->set_message('success',_('Data updated successfully !'));
			return $id_entity ;
		}
	}
    
	/**
	* function to delete the recurrent events when the parent event is set to be not recurrent or set to some other pattern
	* @param integer $idevents
	*/
	public function delete_related_recurrent_events($idevents) {
		$qry = "select * from ".$this->getTable()." where `parent_recurrent_event_id` = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idevents)) ;
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$this->query("delete from ".$this->getTable()." where `idevents` = ? limit 1",array($data["idevents"])) ;
				$this->query("delete from `events_custom_fld` where `idevents` = ? limit 1",array($data["idevents"])) ;
				$this->query("delete from `events_to_grp_rel` where `idevents` = ? limit 1",array($data["idevents"])) ;
				$this->query("delete from `events_related_to` where `idevents` = ? limit 1",array($data["idevents"])) ;
			}
		}
	}
  
	/**
	* function to get the events count by day
	* @param integer $year
	* @param integer $month
	* @param integer $iduser
	* @return array
	*/
	public function get_events_count_by_day($year,$month,$iduser="") {
		$return_array = array();
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition("events",2,false);
		$qry = "
		select count(*) as tot_events,
		DATE_FORMAT(`start_date`,'%d') as `day`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`
		from `events` 
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		where `events`.`deleted` = 0 
		AND start_date like ?
		".$security_where."
		group by DATE_FORMAT(`start_date`,'%d')";
		$this->query($qry,array('%'.$year.'-'.$month.'%'));
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$return_array[(int)$this->day] = $this->tot_events ;
			}
		}
		return $return_array ;
	}
    
	/**
	* function to get all events by day
	* @param integer $year
	* @param integer $month
	* @param integer $iduser
	*/
	public function get_all_events_by_day($year,$month,$day,$iduser = "") {
		$security_where = $_SESSION["do_crm_action_permission"]->get_user_where_condition("events",2,false);
		if (strlen($month) == 1) $month = '0'.$month;
		if (strlen($day) == 1) $day = '0'.$day;
		$event_date = $year."-".$month."-".$day;
		$qry = "
		select `events`.* ,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` 
		end
		as `assigned_to`
		from
		`events`
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		where `events`.`deleted` = 0 
		AND start_date = ? 
		".$security_where."
		order by `start_time`
		";
		$this->query($qry,array($event_date));
	}
    
	/**
	* function to get the contacts related to potentials
	* @param integer $idpotentials
	*/
	public function get_related_contacts($idpotentials) {
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
		inner join `potentials_related_to` on `potentials_related_to`.`related_to` = `contacts`.`idcontacts`
		inner join `contacts_address` on `contacts_address`.`idcontacts` = `contacts`.`idcontacts`
		inner join `contacts_custom_fld` on `contacts_custom_fld`.`idcontacts` = `contacts`.`idcontacts`
		left join `user` on `user`.`iduser` = `contacts`.`iduser`
		left join `cnt_to_grp_rel` on `cnt_to_grp_rel`.`idcontacts` = `contacts`.`idcontacts`
		left join `group` on `group`.`idgroup` = `cnt_to_grp_rel`.`idgroup`
		left join `organization` on `organization`.`idorganization` = `contacts`.`idorganization`
		left join contacts as `cnt2` on `contacts`.`reports_to` = `cnt2`.`idcontacts` AND `contacts`.`reports_to` <> 0
		where 
		`contacts`.`deleted` = 0 
		AND `potentials_related_to`.idmodule = 4
		AND `potentials_related_to`.idpotentials = ".$this->quote($idpotentials);
		$this->setSqlQuery($qry);
	}
    
	/**
	* function to get the organizations related to potentials
	* @param integer $idpotentials
	*/
	public function get_related_organization($idpotentials) {
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
		inner join `potentials_related_to` on `potentials_related_to`.`related_to` = `contacts`.`idcontacts`
		inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
		inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
		left join `user` on `user`.`iduser` = `organization`.`iduser`
		left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
		left join `group` on `group`.`idgroup` = `org_to_grp_rel`.`idgroup`
		left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` AND `organization`.`member_of` <> 0
		where 
		`organization`.`deleted` = 0 
		AND `potentials_related_to`.idmodule = 6
		AND AND `potentials_related_to`.idpotentials = ".$this->quote($idpotentials);			
		$this->setSqlQuery($qry);
	}
	
}