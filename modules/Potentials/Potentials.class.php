<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Potentials
* @author Abhik Chakraborty
*/ 
	

class Potentials extends DataObject {
	public $table = "potentials";
	public $primary_key = "idpotentials";

	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("potential_name","expected_closing_date","leadsource","sales_stage","related_to","assigned_to");

	/* Array holding the field values to be displayed by the popup section for the Potential */
	public $popup_selection_fields = array("potential_name","expected_closing_date","leadsource","sales_stage","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "potential_name";

	/* default order by in the list view */
	protected $default_order_by = "`potentials`.`potential_name`";

	public $module_group_rel_table = "pot_to_grp_rel";
	
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
		as `potentials_related_to_value`
		from `potentials`
		inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
		left join `user` on `user`.`iduser` = `potentials`.`iduser`
		left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
		left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
		left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
		left join `contacts` as sqcnt on sqcnt.idcontacts = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 4
		left join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 6
		where `potentials`.`deleted` = 0 
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
		as `potentials_related_to_value`
		from `potentials`
		inner join `potentials_custom_fld` on `potentials_custom_fld`.`idpotentials` = `potentials`.`idpotentials`
		left join `user` on `user`.`iduser` = `potentials`.`iduser`
		left join `pot_to_grp_rel` on `pot_to_grp_rel`.`idpotentials` = `potentials`.`idpotentials`
		left join `group` on `group`.`idgroup` = `pot_to_grp_rel`.`idgroup`
		left join `potentials_related_to` on `potentials_related_to`.`idpotentials` = `potentials`.`idpotentials`
		left join `contacts` as sqcnt on sqcnt.idcontacts = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 4
		left join organization as sqorg on sqorg.idorganization = `potentials_related_to`.`related_to` and `potentials_related_to`.`idmodule` = 6
		where `potentials`.`deleted` = 0 AND `potentials`.`idpotentials` = ?";			
		$this->query($qry,array($sqcrm_record_id));
		return $this->next();
	}
    
	/**
	* Event function to add the Potential data
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) { 
		$do_crm_fields = new CRMFields();
		$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
		// Insert the data into the potentials related tables - potentials,potentials_custom_fld,pot_to_grp_rel
		$table_entity = 'potentials';
		$table_entity_custom = 'potentials_custom_fld';
		$table_entity_to_grp = 'pot_to_grp_rel';
		$table_entity_related_to = 'potentials_related_to';
		$entity_data_array = array();
		$custom_data_array = array();
		$assigned_to_as_group = false ;
		$related_to_field = false ;
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
			} else{ $value = $field_value ; }
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
			$q_upd= "
			update `".$this->getTable()."` 
			set `added_on` = ? 
			where `".$this->primary_key."` = ?";
			$this->query($q_upd,array(date("Y-m-d H:i:s"),$id_entity));
			$custom_data_array[$this->primary_key] = $id_entity;
			$this->insert($table_entity_custom,$custom_data_array);
			//If the assigned_to to set as group then it goes to the table groups related to
			if ($assigned_to_as_group === true) {
				$this->insert($table_entity_to_grp,array("idpotentials"=>$id_entity,"idgroup"=>$group_id));
			}
			
			if ($related_to_field === true) {
				$this->insert($table_entity_related_to,array("idpotentials"=>$id_entity,"related_to"=>$related_to,"idmodule"=>$idmodule_rel_to));
			}
			//record the data history
			$do_data_history = new DataHistory();
			$do_data_history->add_history($id_entity,(int)$evctl->idmodule,'add'); 
			
			//record the feed
			$feed_other_assigne = array() ;
			if ($assigned_to_as_group === true) {
				$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>$group_id));
			}
			$do_feed_queue = new LiveFeedQueue();
			$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->potential_name,'add',$feed_other_assigne);
			
			$_SESSION["do_crm_messages"]->set_message('success',_('New Potential has been added successfully ! '));
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
    
	/**
	* Event function to update the Potentials data
	* @param object $evctl
	*/
	public function eventEditRecord(EventControler $evctl) {
		$id_entity = (int)$evctl->sqrecord;
		if ($id_entity > 0) {
			$obj = $this->getId($id_entity);
			$obj = (object)$obj; // convert the data array to Object
			$do_crm_fields = new CRMFields();
			$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
			$table_entity = 'potentials';
			$table_entity_custom = 'potentials_custom_fld';
			$table_entity_to_grp = 'pot_to_grp_rel';
			$table_entity_related_to = 'potentials_related_to';
			$entity_data_array = array();
			$custom_data_array = array();
			$assigned_to_as_group = false ;
			$related_to_field = false ;
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
			//print_r($entity_data_array);exit;
			$this->update(array($this->primary_key=>$id_entity),$table_entity,$entity_data_array);
			//updating the last_modified,last_modified_by
			$q_upd = "
			update `".$this->getTable()."` set 
			`last_modified` = ? ,
			`last_modified_by` = ?
			where `".$this->primary_key."` = ?" ;
			$this->query($q_upd, array(date("Y-m-d H:i:s"),$_SESSION["do_user"]->iduser,$id_entity));
			if (count($custom_data_array) > 0) {
				$this->update(array($this->primary_key=>$id_entity),$table_entity_custom,$custom_data_array);
			}
			if ($assigned_to_as_group === false) {
				$qry_grp_rel = "DELETE from `$table_entity_to_grp` where `idpotentials` = ? LIMIT 1";
				$this->query($qry_grp_rel,array($id_entity));
			} else {
				$qry_grp_rel = "select * from `$table_entity_to_grp` where `idpotentials` = ?";
				$this->query($qry_grp_rel,array($id_entity));
				if ($this->getNumRows() > 0) {
					$this->next();
					$id_grp_rel = $this->idpot_to_grp_rel ;
					$q_upd = "
					update `$table_entity_to_grp` set `idgroup` = ? 
					where `idpot_to_grp_rel` = ? LIMIT 1" ;
					$this->query($q_upd,array($group_id,$id_grp_rel));
				} else {
					$this->insert($table_entity_to_grp,array("idpotentials"=>$id_entity,"idgroup"=>$group_id));
				}
			}
			if ($related_to_field === true) {
				$q_upd = "
				update `$table_entity_related_to` set 
				`related_to` = ? ,
				`idmodule` = ? 
				where `idpotentials` = ? limit 1" ;
				$this->query($q_upd,array($related_to,$idmodule_rel_to,$id_entity));
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
			$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->potential_name,'edit');
		
			$_SESSION["do_crm_messages"]->set_message('success',_('Data updated successfully !'));
			$next_page = NavigationControl::getNavigationLink($evctl->module,"detail");
			$dis = new Display($next_page);
			$dis->addParam("sqrecord",$id_entity);
			$evctl->setDisplayNext($dis) ; 
		}
	}
    
	/**
	* function to get the contacts related to potentials
	* @param integer $idpotentials
	*/
	public function get_related_contacts($idpotentials){
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
		AND `potentials_related_to`.idpotentials = ".(int)$idpotentials;
		$this->setSqlQuery($qry);
	}
    
	/**
	* function to get the organizations related to potentials
	* @param integer $idpotentials
	*/
	public function get_related_organization($idpotentials){
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
		inner join `potentials_related_to` on `potentials_related_to`.`related_to` = `organization`.`idorganization`
		inner join `organization_address` on `organization_address`.`idorganization` = `organization`.`idorganization`
		inner join `organization_custom_fld` on `organization_custom_fld`.`idorganization` = `organization`.`idorganization`
		left join `user` on `user`.`iduser` = `organization`.`iduser`
		left join `org_to_grp_rel` on `org_to_grp_rel`.`idorganization` = `organization`.`idorganization`
		left join `group` on `group`.`idgroup` = `org_to_grp_rel`.`idgroup`
		left join `organization` as `org2` on `organization`.`member_of` = `org2`.`idorganization` AND `organization`.`member_of` <> 0
		where 
		`organization`.`deleted` = 0 
		AND `potentials_related_to`.idmodule = 6
		AND `potentials_related_to`.idpotentials = ".(int)$idpotentials;       
		$this->setSqlQuery($qry);
	}
    
	/** 
	* function to get the related events
	* @param $integer $idpotentials
	*/
	public function get_related_events($idpotentials){
		$this->getId((int)$idpotentials);
		$pot_name = $this->potential_name ;
		
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
		'".$pot_name."' as `events_related_to_value`
		from `events`
		inner join `events_related_to` on `events_related_to`.`idevents` = `events`.`idevents`
		inner join `events_custom_fld` on `events_custom_fld`.`idevents` = `events`.`idevents`
		left join `user` on `user`.`iduser` = `events`.`iduser`
		left join `events_to_grp_rel` on `events_to_grp_rel`.`idevents` = `events`.`idevents`
		left join `group` on `group`.`idgroup` = `events_to_grp_rel`.`idgroup`
		where 
		`events`.`deleted` = 0 
		AND `events_related_to`.idmodule = 5
		AND `events_related_to`.`related_to` = ".(int)$idpotentials;
		$this->setSqlQuery($qry);
	}

}