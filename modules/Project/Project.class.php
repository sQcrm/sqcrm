<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Project 
* @author Abhik Chakraborty
*/ 
	

class Project extends DataObject {
	public $table = "project";
	public $primary_key = "idproject";

	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("project_name","idorganization","project_status","due_date","assigned_to");

	/* Array holding the field values to be displayed by the popup section */
	public $popup_selection_fields = array("project_name","idorganization","project_status","due_date","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "project_name";

	/* default order by in the list view */
	protected $default_order_by = "`project`.`idproject`";

	public $module_group_rel_table = "project_to_grp_rel";
	
	public $overwrite_permissions = true;

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
		select `project`.*,
		`project_custom_fld`.*,
		`project_to_grp_rel`.`idgroup`,
		`organization`.`organization_name` as `org_name`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `project`
		inner join `project_custom_fld` on `project_custom_fld`.`idproject` = `project`.`idproject`
		left join `user` on `user`.`iduser` = `project`.`iduser`
		left join `project_to_grp_rel` on `project_to_grp_rel`.`idproject` = `project`.`idproject`
		left join `group` on `group`.`idgroup` = `project_to_grp_rel`.`idgroup`
		left join `organization` on `organization`.`idorganization` = `project`.`idorganization`
		where `project`.`deleted` = 0 ";
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
		select `project`.*,
		`project_custom_fld`.*,
		`project_to_grp_rel`.`idgroup`,
		`organization`.`organization_name` as `org_name`,
		case when (`user`.`user_name` not like '')
		then
		`user`.`user_name` 
		else
		`group`.`group_name` end
		as `assigned_to`
		from `project`
		inner join `project_custom_fld` on `project_custom_fld`.`idproject` = `project`.`idproject`
		left join `user` on `user`.`iduser` = `project`.`iduser`
		left join `project_to_grp_rel` on `project_to_grp_rel`.`idproject` = `project`.`idproject`
		left join `group` on `group`.`idgroup` = `project_to_grp_rel`.`idgroup`
		left join `organization` on `organization`.`idorganization` = `project`.`idorganization`
		where `project`.`idproject` = ?
		AND `project`.`deleted` = 0 ";
		$this->query($qry,array($sqcrm_record_id));
		return $this->next();
	}
    
	/**
	* Event function to add the project data
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) {
		$permission = $_SESSION["do_crm_action_permission"]->action_permitted('add',19) ; 
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
				$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
				// Insert the data into the related tables - project,project_custom_fld
				$table_entity = 'project';
				$table_entity_custom = 'project_custom_fld';
				$table_entity_to_grp = 'project_to_grp_rel';    
				$entity_data_array = array();
				$custom_data_array = array();
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
					$custom_data_array["idproject"] = $id_entity;
					$addr_data_array["idproject"] = $id_entity;
					$this->insert($table_entity_custom,$custom_data_array);
					//If the assigned_to to set as group then it goes to the table entity group relation table
					if ($assigned_to_as_group === true) {
						$this->insert($table_entity_to_grp,array("idproject"=>$id_entity,"idgroup"=>$group_id));
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
					$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->project_name,'add',$feed_other_assigne);
					
					// process after add plugin
					$do_process_plugins->process_action_plugins((int)$evctl->idmodule,$evctl,2,$id_entity);
					
					$_SESSION["do_crm_messages"]->set_message('success',_('New Project has been added successfully ! '));
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
	* Event function to update the project data
	* @param object $evctl
	*/
	public function eventEditRecord(EventControler $evctl) {
		$id_entity = (int)$evctl->sqrecord;
		if ($id_entity > 0 && true === $_SESSION["do_crm_action_permission"]->action_permitted('edit',19,(int)$evctl->sqrecord)) {
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
				$table_entity = 'project';
				$table_entity_custom = 'project_custom_fld';
				$table_entity_to_grp = 'project_to_grp_rel';
				$entity_data_array = array();
				$custom_data_array = array();
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
				where `".$this->primary_key."` = ?";
				$this->query($q_upd,array(date("Y-m-d H:i:s"),$_SESSION["do_user"]->iduser,$id_entity));
				
				if (count($custom_data_array) > 0) {
					$this->update(array($this->primary_key=>$id_entity),$table_entity_custom,$custom_data_array);
				}
				
				if ($assigned_to_as_group === false) {
					$qry_grp_rel = "DELETE from `$table_entity_to_grp` where idproject = ? LIMIT 1";
					$this->query($qry_grp_rel,array($id_entity));
				} else {
					$qry_grp_rel = "select * from `$table_entity_to_grp` where idproject = ?";
					$this->query($qry_grp_rel,array($id_entity));
					if ($this->getNumRows() > 0) {
						$this->next();
						$id_grp_rel = $this->idproject_to_grp_rel ;
						$q_upd = "
						update `$table_entity_to_grp` set 
						`idgroup` = ?
						where `idproject_to_grp_rel` = ? LIMIT 1" ;
						$this->query($q_upd,array($group_id,$id_grp_rel));
					} else {
						$this->insert($table_entity_to_grp,array("idproject"=>$id_entity,"idgroup"=>$group_id));
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
				$do_feed_queue->add_feed_queue($id_entity,(int)$evctl->idmodule,$evctl->project_name,'edit',$feed_other_assigne);
				
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
	* function to get the additional permission for a project
	* @param integer $idproject
	*
	* Task create permission
	*
	* 1 - Only project owner can create tasks
	* 2 - All members for the project can create tasks
	*
	* Task edit permission
	*
	* 1 - Only project owner can edit tasks
	* 2 - Only the task owner can edit the tasks
	* 3 - All members for the project can edit tasks
	*
	* Task close permission
	*
	* 1 - Only project owner can close tasks.
	* 2 - Only the task owner can close tasks.
	* 3 - All members for the project can close the task.
	*
	* Task assignees
	*
	* 1 - Only project owner can add task assignees.
	* 2 - Only the task owner can add task assignees.
	* 3 - All members for the project can add task assignees.
	*
	* Project members
	*
	* 1 - Only project owner can add members into the project.
	* 2 - Other members of the project can add members into the project.
	* 
	* Who has permission for setting project permissions (project owner default, and choosen users from the project members)
	*/
	public function get_additional_permissions($idproject) {
		$qry = "select * from `project_permission` where `idproject` = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject));
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			return array(
				'task_create' => $data['task_create'],
				'task_edit' => $data['task_edit'],
				'task_close' => $data['task_close'],
				'task_assignees' => $data['task_assignees'],
				'project_members' => $data['project_members'],
				'permission_changer' => $data['permission_changer']
			);
		} else {
			return array(
				'task_create' => 2,
				'task_edit' => 3,
				'task_close' => 3,
				'task_assignees' => 3,
				'project_members' => 2,
				'permission_changer' =>''
			);
		}
	}
	
	/**
	* function to load the custom permission for the project access by id
	* View/Add/Edit/Delete permission will be checked via the crm action permission 
	* Function will check if the project is accessible by the user considering
	* - If the user is owner
	* - If the assigned_to is group and the user is a member of the group
	* - If the user is a part of the project via the project members
	* @param string $action , reserved for future use if needed
	* @param integer $idproject
	* @param integer $iduser
	* @return boolean
	*/
	public function custom_permission($action,$idproject,$iduser=0) {
		if ((int)$idproject == 0) return false;
		$this->getId($idproject);
		// if record not found return unauthorized
		if ($this->getNumRows() == 0) {
			return false;
		}
		// if record is deleted return unauthorized
		if ($this->deleted == 1) {
			return false;
		}
		if ((int)$iduser == 0) $iduser = $_SESSION["do_user"]->iduser;
		// if assigned_to is same as user accessing the record return access permitted
		$retval = false;
		if ($this->iduser == $iduser) {
			$retval = true;
		} elseif ($this->iduser == 0) {
			// check if the project assigned_to to a group and the user accessing belongs to the group
			$qry = "select * from `project_to_grp_rel` where `idproject` = ?";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject));
			if ($stmt->rowCount() > 0) {
				$data = $stmt->fetch();
				$idgroup = $data['idgroup'];
				if ($idgroup > 0) {
					$qry_group = "select * from `group_user_rel` where `idgroup`=? and `iduser` = ?";
					$stmt_grp = $this->getDbConnection()->executeQuery($qry_group,array($idgroup,$iduser));
					if ($stmt_grp->rowCount() > 0) {
						$retval = true;
					} else {
						$retval = false;
					}
				} else {
					$retval = false;
				}
			} else {
				$retval = false;
			}
		} else {
			// check if the user accessing the project is a member of the project when all above condition fails
			$qry = "select * from `project_members` where `idproject` = ? and `iduser` = ? and `accepted` = 1";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject,$iduser));
			if ($stmt->rowCount() > 0) {
				$retval = true;
			} else {
				$retval = false;
			}
		}
		
		return $retval;
	}
	
	/**
	* function to get the custom where condition
	* The where condition is returned considering
	* - if the user is the project owner
	* - if the user is a part of the group, if project is assigned to group
	* - if the user is a part of the project via the project member
	* 
	* @param integer $iduser
	* @return string, where condition for the list view
	*/
	public function get_custom_where_cond($iduser = 0) {
		if ((int)$iduser == 0) $iduser = $_SESSION["do_user"]->iduser;
		
		// get the assiciated group of the user
		$group_user_rel = new GroupUserRelation();
		$user_associated_groups = $group_user_rel->get_groups_by_user($iduser);
		
		// get the project ids where the user is a member of projects 
		$qry = "select * from `project_members` where `iduser` = ? and `accepted` = 1";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($iduser));
		
		$member_of_projects = array();
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$member_of_projects[] = $data['idproject'];
			}
		}
		
		$where = " AND `project`.`iduser` = ".$iduser;
		
		if (is_array($member_of_projects) && count($member_of_projects) >0) {
			if (is_array($user_associated_groups) && count($user_associated_groups) > 0) {
				$where = 
				" AND 
				(
					(
						`project`.`iduser` = ".$iduser." OR `project_to_grp_rel`.`idgroup` in (".implode(',',$user_associated_groups).")
					)
					OR
					`project`.`idproject` in (".implode(',',$member_of_projects).")
				)
				";
			} else {
				$where = 
				" AND 
				(
					`project`.`iduser` = ".$iduser." OR `project`.`idproject` in (".implode(',',$member_of_projects).")
				)
				";
			}
		} else {
			if (is_array($user_associated_groups) && count($user_associated_groups) > 0) {
				$where = 
				" AND
				(
					`project`.`iduser` = ".$iduser." OR `project_to_grp_rel`.`idgroup` in (".implode(',',$user_associated_groups).")
				)
				";
			}
		}
		
		return $where;
	}
}