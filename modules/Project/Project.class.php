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
				'id' => $data['idproject_permission'],
				'task_create' => $data['task_create'],
				'task_edit' => $data['task_edit'],
				'task_close' => $data['task_close'],
				'task_assignees' => $data['task_assignees'],
				'project_members' => $data['project_members'],
				'permission_changer' => $data['permission_changer']
			);
		} else {
			return array(
				'id' =>0,
				'task_create' => 2,
				'task_edit' => 3,
				'task_close' => 3,
				'task_assignees' => 3,
				'project_members' => 1,
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
		
		if ($_REQUEST['sfaction'] == 'invitation') return true;
		
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
	
	/**
	* function to get the assigned to userids for a project
	* A project could be assigned to an individual or a group of user ids
	* This function is to return the user(s) assigned to the project
	* @param object $project_obj 
	* @return array
	*/
	public function get_assigned_to_userids($project_obj) {
		$iduser = $project_obj->iduser;
		$idgroup = $project_obj->idgroup;
		$return_array = array();
		
		if ((int)$iduser == 0) {
			$grp_user_rel = new GroupUserRelation();
			$grp_user_rel->get_users_related_to_group($idgroup);
			if ($grp_user_rel->getNumRows() > 0) {
				while ($grp_user_rel->next()) {
					$return_array[] = $grp_user_rel->iduser;
				}
			}
		} else {
			$return_array[] = $iduser;
		}
		return $return_array;
	}
	
	/**
	* get all the project members who are not a part of assigned to
	* @param integer $idproject
	* @param string $status
	* @return array
	*/
	public function get_other_project_members($idproject,$status='all') {
		$qry = '';
		
		switch ($status) {
			case 'all':
				$qry = "select * from `project_members` where `idproject` = ?";
				$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject));
				break;
				
			case 'accepted':
				$qry = "select * from `project_members` where `idproject` = ? and `accepted` = 1";
				$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject));
				break;
				
			case 'pending':
				$qry = "select * from `project_members` where `idproject` = ? and `accepted` = 0";
				$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject));
				break;
				
			case 'rejected':
				$qry = "select * from `project_members` where `idproject` = ? and `accepted` = 2";
				$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject));
				break;
		}
		
		return $stmt->fetchAll();
	}
	
	/**
	* get all the project members including assigned to and the other users
	* The returned function returns the users with the type related to the project
	* @param object $project_obj
	* @return array
	*/
	public function get_project_members($project_obj) {
		$idproject = $project_obj->idproject;
		$iduser = $project_obj->iduser;
		$idgroup = $project_obj->idgroup;
		$return_array = array();
		
		if ((int)$iduser == 0) {
			$grp_user_rel = new GroupUserRelation();
			$grp_user_rel->get_users_related_to_group($idgroup);
			$users = array();
			if ($grp_user_rel->getNumRows() > 0) {
				while ($grp_user_rel->next()) {
					$users[$grp_user_rel->iduser] = array(
						'iduser'=>$grp_user_rel->iduser,
						'user_name'=>$grp_user_rel->user_name,
						'firstname'=>$grp_user_rel->firstname,
						'lastname'=>$grp_user_rel->lastname,
						'email'=>$grp_user_rel->email,
						'user_avatar'=>$grp_user_rel->user_avatar
					);
				}
			}
			$return_array['assigned_to'] = $users;
		} else {
			$do_user = new User();
			$do_user->getId($iduser);
			$return_array['assigned_to'][$iduser] = array(
				'iduser'=>$iduser,
				'user_name'=>$do_user->user_name,
				'firstname'=>$do_user->firstname,
				'lastname'=>$do_user->lastname,
				'email'=>$do_user->email,
				'user_avatar'=>$do_user->user_avatar
			);
		}
		
		$other_assignee = array();
		$qry = "
		select `p`.*,`u`.* from `project_members` `p`
		join `user` `u` on `u`.`iduser` = `p`.`iduser`
		where `p`.`idproject` = ? and `p`.`accepted` = 1
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject));
		if ($stmt->rowCount() > 0) {
			while ($data = $stmt->fetch()) {
				$other_assignee[$data['iduser']] = array(
					'iduser'=>$data['iduser'],
					'user_name'=>$data['user_name'],
					'firstname'=>$data['firstname'],
					'lastname'=>$data['lastname'],
					'email'=>$data['email'],
					'user_avatar'=>$data['user_avatar']
				);
			}
		}
		$return_array['other_assignee'] = $other_assignee;
		return $return_array;
	}
	
	/**
	* function to get the different users who are assigned to a project 
	* by default the users assigned_to is/are member of the project
	* other users where the request is sent but not accepted or rejected or accepted are 
	* divided in groups as in the returned array
	* @param integer $idproject
	* @return array
	*/
	public function get_users_to_be_assigned($idproject) {
		$this->getId($idproject);
		// if invalid project id return 
		if ($this->getNumRows() == 0) return array();
		
		$iduser = $this->iduser;
		$idgroup = $this->idgroup;
		
		$users = array();
		$assigned_to_array = array();
		
		// if the assigned to is a group then get all the users from the group and set them as member of the project
		if ((int)$iduser == 0) {
			$group_user_rel = new GroupUserRelation();
			$group_user_rel->get_users_related_to_group($idgroup);
			
			if ($group_user_rel->getNumRows() > 0) {
				while ($group_user_rel->next()) {
					$users['member'][] = array(
						'iduser' => $group_user_rel->iduser,
						'user_name' => $group_user_rel->user_name,
						'firstname' => $group_user_rel->firstname,
						'lastname' => $group_user_rel->lastname,
						'user_avatar' => $group_user_rel->user_avatar,
						'assigned_to'=>1
					);
					$assigned_to_array[] = $group_user_rel->iduser;
				}
			}
		} else {
			// if not group then just the assigned to user
			$do_user = new User();
			$do_user->getId($iduser);
			$users['member'][] = array(
				'iduser' => $do_user->iduser,
				'user_name' => $do_user->user_name,
				'firstname' => $do_user->firstname,
				'lastname' => $do_user->lastname,
				'user_avatar' => $do_user->user_avatar,
				'email' => $do_user->email,
				'assigned_to'=>1
			);
			$assigned_to_array[] = $iduser;
		}
		
		// get all the roles which are permitted to access the project module
		$permitted_roles = array();
		$qry_permitted_roles = "
		select 
		rpr.idrole,
		max(pmr.permission_flag) as permission_flag
		from role_profile_rel rpr 
		join profile_module_rel pmr on pmr.idprofile = rpr.idprofile 
		where pmr.idmodule = 19 group by rpr.idrole
		";
		$stmt_permitted_roles = $this->getDbConnection()->executeQuery($qry_permitted_roles);
		
		if ($stmt_permitted_roles->rowCount() > 0) {
			while ($data = $stmt_permitted_roles->fetch()) {
				if ($data['permission_flag'] == 1) {
					$permitted_roles[] = $data['idrole'];
				}
			}
		}
		
		/**
		* get all the users from project members and the users table by allowed roles
		* set the different users as part of the project as per accepted condition
		*/
		$roles = '';
		if (count($permitted_roles) > 0) {
			$roles = "'" . implode ( "','", $permitted_roles ) . "'";
			$qry_user = "
			select 
			u.iduser,
			u.user_name,
			u.firstname,
			u.lastname,
			u.user_avatar,
			u.email,
			psp.permission,
			case
				when pm.accepted is null then 'not_assigned'
				when pm.accepted = 0 then 'req_sent'
				when pm.accepted = 1 then 'member'
				when pm.accepted = 2 then 'req_rejected'
			end as `project_users`
			from user u 
			join role_profile_rel rpr on rpr.idrole = u.idrole 
			join 
			( 
				select 
				max(permission_flag) as permission,
				idprofile from profile_standard_permission_rel 
				where idmodule = 19 
				group by idprofile
			) psp on psp.idprofile = rpr.idprofile 
			left join project_members pm on pm.iduser = u.iduser and pm.idproject = ?
			where u.idrole in(".$roles.")
			";
			$stmt_users = $this->getDbConnection()->executeQuery($qry_user,array($idproject));
			if ($stmt_users->rowCount() > 0) {
				while ($data = $stmt_users->fetch()) {
					$user_data = array();
					$user_data = array(
						'iduser' => $data['iduser'],
						'user_name' => $data['user_name'],
						'firstname' => $data['firstname'],
						'lastname' => $data['lastname'],
						'user_avatar' => $data['user_avatar'],
						'email' => $data['email'],
						'assigned_to'=>0
					);
					
					if ($data['project_users'] == 'not_assigned') {
						if (in_array($data['iduser'],$assigned_to_array)) continue;
						$users['not_assigned'][] = $user_data;
					} elseif ($data['project_users'] == 'req_sent') {
						$users['req_sent'][] = $user_data;
					} elseif ($data['project_users'] == 'member') {
						$users['member'][] = $user_data;
					} elseif ($data['project_users'] == 'req_rejected') {
						$users['req_rejected'][] = $user_data;
					}
				}
			}
		}
		return $users;
	}
	
	/**
	* event function to add an user into the project
	* @param object $evctl
	* @return string
	*/
	public function eventAddProjectMember(EventControler $evctl) {
		$err = '';
		$added_by = $_SESSION["do_user"]->iduser;
		$existing_members = array();
		
		if ((int)$evctl->idproject == 0) {
			$err = _('Missing the project id.');
		} elseif ((int)$evctl->iduser == 0) {
			$err = _('Missing user id to be added into the project.');
		} elseif (false === $this->custom_permission('view',$evctl->idproject,$added_by)) {
			$err = _('You do not have permission to do this operation.');
		} else {
			$this->getId($evctl->idproject);
			if ($this->getNumRows() == 0) {
				$err = _('Project does not exist.');
			} else {
				$additional_permissions = $this->get_additional_permissions($evctl->idproject);
				$accepted_members = array();
				$assigned_to_array = $this->get_assigned_to_userids($this);
				$existing_members = $assigned_to_array;
				$project_members_data = $this->get_other_project_members($evctl->idproject);
				
				if (count($project_members_data) > 0) {
					foreach ($project_members_data as $key=>$val) {
						$existing_members[] = $val['iduser'];
						if ($val['accepted'] == 1) {
							$accepted_members[] = $val['iduser'];
						}
					}
				}
				
				if (in_array($evctl->iduser,$existing_members)) {
					$err = _('User you are trying to add to the project is already a member or request is still pending.');
				}
				
				if ($err == '') {
					$add_action = false ;
					if ($additional_permissions['project_members'] == 2 && (in_array($added_by,$assigned_to_array) || in_array($added_by,$accepted_members))) {
						$add_action = true;
					} elseif ($additional_permissions['project_members'] == 1 && in_array($added_by,$assigned_to_array)) {
						$add_action = true;
					}
					
					if (false === $add_action) {
						echo _('You are not authorized to perform this operation');
					}
				}
			}
		}
		
		if ($err !='') {
			echo $err;
		} else {
			$qry = "insert into `project_members` (`idproject`,`iduser`,`sender`) values (?, ?, ?)";
			$this->getDbConnection()->executeQuery($qry,array($evctl->idproject, $evctl->iduser, $added_by));
			$idinvitation = $this->getDbConnection()->lastInsertId();
			
			// send invitation email
			$do_projectmailer = new ProjectEmailer();
			$do_projectmailer->send_project_member_invitation_email($this, $idinvitation, $evctl->iduser);
			
			// add custom history
			$do_data_history = new DataHistory();
			$do_invitee = new User();
			$do_invitee->getId($evctl->iduser);
			$history_text = _('Has added');
			$history_text .= ' '.$do_invitee->firstname.' '.$do_invitee->lastname. ' '._('to this project');
			$do_invitee->free();
			$do_data_history->add_custom_history($evctl->idproject, 19, $history_text);
			$do_data_history->free();
			
			echo '1';
		}
	}
	
	/**
	* event function to remove a project member
	* @param object $evctl
	* @return string
	*/
	public function eventRemoveProjectMember(EventControler $evctl) {
		$err = '';
		$removed_by = $_SESSION["do_user"]->iduser;
		
		if ((int)$evctl->idproject == 0) {
			$err = _('Missing project id');
		} elseif ((int)$evctl->iduser == 0) {
			$err = _('Missing the user id to be removed');
		} elseif (!in_array($evctl->type,array('accepted','pending','rejected'))) {
			$err = _('Invalid project member');
		} else {
			$this->getId($evctl->idproject);
			if ($this->getNumRows() == 0) {
				$err = _('Project does not exist.');
			} else {
				$additional_permissions = $this->get_additional_permissions($evctl->idproject);
				$assigned_to_users = $this->get_assigned_to_userids($this);
				$action_allowed = false;
				
				if ($err == '') {
					$accepted_members = array();
					$rejected_members = array();
					$pending_members = array();
					$project_members_data = $this->get_other_project_members($evctl->idproject);
					
					if (count($project_members_data) > 0) {
						foreach ($project_members_data as $key=>$val) {
							if ($val['accepted'] == 0) {
								$pending_members[] = $val['iduser'];
							}
							
							if ($val['accepted'] == 1) {
								$accepted_members[] = $val['iduser'];
							}
							
							if ($val['accepted'] == 2) {
								$rejected_members[] = $val['iduser'];
							}
							
						}
					}
					
					if ($additional_permissions['project_members'] == 2 && (in_array($removed_by,$assigned_to_users) || in_array($removed_by,$accepted_members))) {
						$action_allowed = true;
					} elseif ($additional_permissions['project_members'] == 1 && in_array($removed_by,$assigned_to_users)) {
						$action_allowed = true;
					}
					
					if (in_array($evctl->iduser,$assigned_to_users)) {
						$err = _('This user can not be removed from the project.');
					}
					
					if ($evctl->type == 'accepted' && !in_array($evctl->iduser,$accepted_members)) {
						$err = _('You are trying to remove a member who is not a part of this project.');
					}
					
					if ($evctl->type == 'pending' && !in_array($evctl->iduser,$pending_members)) {
						$err = _('You are trying to remove a member who is not a part of pending members in this project.');
					}
					
					if ($evctl->type == 'rejected' && !in_array($evctl->iduser,$rejected_members)) {
						$err = _('You are trying to remove a member who is not a part of rejected members in this project');
					}
				
					if (false === $action_allowed) {
						echo _('You are not authorized to perform this operation');
					}
				}
			}
		}
		
		if ($err != '') {	
			echo $err;
		} else {
			$qry = "delete from `project_members` where `idproject` =? AND `iduser` = ?";
			$this->getDbConnection()->executeQuery($qry,array($evctl->idproject,$evctl->iduser));
			if ($evctl->type == 'accepted') {
				// send email to the user who is removed from project
				$do_projectmailer = new ProjectEmailer();
				$do_projectmailer->send_removed_from_project_email($evctl->idproject, $evctl->iduser);
				
				// add custom history
				$do_data_history = new DataHistory();
				$do_invitee = new User();
				$do_invitee->getId($evctl->iduser);
				$history_text = _('Has removed');
				$history_text .= ' '.$do_invitee->firstname.' '.$do_invitee->lastname. ' '._('from this project');
				$do_invitee->free();
				$do_data_history->add_custom_history($evctl->idproject, 19, $history_text);
				$do_data_history->free();
				
				//remove the user from permission changer if the user is there
				$permissions = $this->get_additional_permissions($evctl->idproject);
				$permission_changer = array();
				
				if (trim($permissions['permission_changer']) !==null && trim($permissions['permission_changer']) != '') {
					$permission_changer = explode(',',$permissions['permission_changer']);
					
					if (count($permission_changer) > 0 && ($key = array_search($evctl->iduser, $permission_changer)) !== false) {
						unset($permission_changer[$key]);
						$permission_changer_data = '';
						
						if (count($permission_changer) > 0) {
							$permission_changer_data = implode(',', $permission_changer);
							
							if ((int)$permissions['id'] > 0) {
								$q_upd = "
								update `project_permission` set `permission_changer` = ? 
								where `idproject_permission` = ?
								";
								$this->getDbConnection()->executeQuery($q_upd,array($permission_changer_data,$permissions['id']));
							}
						}
					}
				}
			}
			echo '1';
		}
	}
	
	/**
	* function to check if the invitation url is valid or not 
	* @param integer $id
	* @param integer $idproject
	* @param integer $iduser
	* @return boolean
	*/
	public function check_valid_invitation($id, $idproject=0, $iduser=0) {
		if ((int)$iduser == 0) $iduser = $_SESSION["do_user"]->iduser;
		
		if ((int)$id == 0) {
			return false;
		} else {
			$qry = "select * from `project_members` where `idproject_members` = ?";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($id));
			if ($stmt->rowCount() > 0) {
				$data = $stmt->fetch();
				if ($data['accepted'] != 0) return false;
				if ($iduser != $data['iduser']) return false;
				if ((int)$idproject != 0 && $data['idproject'] != $idproject) return false;
				return $data['idproject'];
			} else {
				return false;
			}
		}
	}
	
	/**
	* event function to accept/reject the project invitation
	* @param object $evctl
	* @return void
	*/
	public function eventAcceptRejectProjectInvitation(EventControler $evctl) {
		$err = '';
		
		if ((int)$evctl->id == 0) {
			$err = _('The project invitation you are trying to access is invalid, please check again.');
		} elseif (false === $this->check_valid_invitation($evctl->id,$evctl->idproject)) {
			$err = _('The project invitation you are trying to access is invalid, please check again.');
		} elseif ($evctl->action != 'accept' && $evctl->action != 'reject') {
			$err = _('Invalid action, the action should be either "accept" or "reject"');
		}
		
		if ($err == '') {
		
			if ($evctl->action == 'accept') {
				$qry = "update `project_members` set `accepted` = 1 where `idproject_members` = ?";
			} else {
				$qry = "update `project_members` set `accepted` = 2 where `idproject_members` = ?";
			}
			
			$stmt = $this->getDbConnection()->executeQuery($qry,array($evctl->id));
			$do_projectmailer = new ProjectEmailer();
			$do_projectmailer->send_project_accept_reject_email($evctl->id, $evctl->action);
			$do_data_history = new DataHistory();
			$iduser = $_SESSION['do_user']->iduser;
			
			if ($evctl->action == 'accept') {
				$do_data_history->add_custom_history($evctl->idproject, 19, _('Has joined this project'));
				$do_data_history->free();
				$_SESSION["do_crm_messages"]->set_message('success',_('You are now member of this project'));
				$next_page = NavigationControl::getNavigationLink('Project',"detail");
				$dis = new Display($next_page);
				$dis->addParam("sqrecord",$evctl->idproject);
				$evctl->setDisplayNext($dis) ;
			} else {
				$do_data_history->add_custom_history($evctl->idproject, 19, _('Has rejected invitation to join this project'));
				$do_data_history->free();
				$_SESSION["do_crm_messages"]->set_message('error',_('You have rejected the invitation'));
				$next_page = NavigationControl::getNavigationLink('Project',"list");
				$dis = new Display($next_page);
				$evctl->setDisplayNext($dis) ;
			}
			
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',$err);
		}
	}
	
	/**
	* event function add project's additional permissions
	* @param object $evctl
	* @return string
	*/
	public function eventAddProjectPermission(EventControler $evctl) {
		$response = array();
		
		if ((int)$evctl->idproject > 0) {
			$this->getId($evctl->idproject);
			$project_members = $this->get_project_members($this);
			$permissions = $this->get_additional_permissions($evctl->idproject);
			$current_user = $_SESSION["do_user"]->iduser;
			$allow_action = false;
			$permission_changer = array();
			
			if (trim($permissions['permission_changer']) !==null && trim($permissions['permission_changer']) != '') {
				$permission_changer = explode(',',$permissions['permission_changer']);
			}
			
			if (array_key_exists($current_user,$project_members['assigned_to'])) {
				$allow_action = true;
			} else {
				if (is_array($permission_changer) && count($permission_changer) > 0) {
					foreach ($permission_changer as $iduser) {
						if (array_key_exists($iduser,$project_members['other_assignee']) && $current_user == $iduser) {
							$allow_action = true;
							break;
						}
					}
				}
			}
			
			if (true === $allow_action) {
				if ((int)$evctl->id > 0) {
					// update the existing record
					$qry = "
					update `project_permission` set 
					`task_create` = ?,
					`task_edit` = ?,
					`task_close` = ?,
					`task_assignees` = ?,
					`project_members` = ?,
					`permission_changer` = ?
					where `idproject_permission` = ?
					";
					
					$stmt = $this->getDbConnection()->executeQuery($qry,array(
						$evctl->task_create,
						$evctl->task_edit,
						$evctl->task_close,
						$evctl->task_assignees,
						$evctl->project_members,
						$evctl->permission_changer,
						$evctl->id
					));
					
					$response = array(
						'status'=>'ok',
						'id'=>$evctl->id,
						'message'=>_('Permission has been updated successfully.')
					);
					
				} else {
					// add a new entity
					$qry = "
					insert into `project_permission`
					(`task_create`,`task_edit`,`task_close`,`task_assignees`,`project_members`,`permission_changer`,`idproject`)
					values
					(?, ?, ?, ?, ?, ?, ?)
					";
					$stmt = $this->getDbConnection()->executeQuery($qry,array(
						$evctl->task_create,
						$evctl->task_edit,
						$evctl->task_close,
						$evctl->task_assignees,
						$evctl->project_members,
						$evctl->permission_changer,
						$evctl->idproject
					));
					
					$response = array(
						'status'=>'ok',
						'id'=>$this->getDbConnection()->lastInsertId(),
						'message'=>_('Permission has been saved successfully.')
					);
				}
			} else {
				$response = array(
					'status'=>'fail',
					'id'=> ((int)$evctl->id > 0 ? $evctl->id : 0),
					'message'=>_('You are not authorized to perform this operation.')
				);
			}
		} else {
			$response = array(
				'status'=>'fail',
				'id'=> ((int)$evctl->id > 0 ? $evctl->id : 0),
				'message'=>_('Missing project id')
			);
		}
		
		echo json_encode($response);
	}
	
	/**
	* function to check additional permissions on a project and its task
	* @param mixed $project
	* @param string $permission
	* @param object $task
	* @param integer $iduser
	* @return boolean
	*/
	public function check_additional_permissions($project, $permission, $task = null, $iduser = 0) {
		if ((int)$iduser == 0) $iduser = $_SESSION["do_user"]->iduser;
		
		if ($project instanceof Project) {
			$project_members = $project->get_project_members($project);
			$additional_permissions = $project->get_additional_permissions($project->idproject);
		} else {
			$project_members = $project['members'];
			$additional_permissions = $project['permissions'];
		}
		
		$permission_flag = false;
		
		switch ($permission) {
		
			case 'task_create' :
				if ($additional_permissions['task_create'] == 2) {
					$permission_flag = true;
				} else {
					if (array_key_exists($iduser,$project_members['assigned_to'])) {
						$permission_flag = true;
					}
				}
			
			break;
			
			case 'task_assignees' :
				if ($additional_permissions['task_assignees'] == 3) {
					$permission_flag = true;
				} elseif ($additional_permissions['task_assignees'] == 2) {
					if ($task instanceof Tasks) {
						if ($iduser == $task->created_by) {
							$permission_flag = true;
						}	
					} else {
						$permission_flag = true;
					}
				} else {
					if (array_key_exists($iduser,$project_members['assigned_to'])) {
						$permission_flag = true;
					}
				} 
				
			break;
			
			case 'task_edit' :
				if ($additional_permissions['task_edit'] == 3) {
					$permission_flag = true;
				} elseif ($additional_permissions['task_edit'] == 2) {
					if ($task instanceof Tasks && $iduser == $task->created_by) {
						$permission_flag = true;
					}
				} else {
					if (array_key_exists($iduser,$project_members['assigned_to'])) {
						$permission_flag = true;
					}
				} 
			
			break;
			
			case 'task_close' :
				if ($additional_permissions['task_close'] == 3 ) {
					$permission_flag = true;
				} elseif ($additional_permissions['task_close'] == 2) {
					if ($iduser == $task->created_by) {
						$permission_flag = true;
					}
				} else {
					if (array_key_exists($iduser,$project_members['assigned_to'])) {
						$permission_flag = true;
					}
				} 
			
			break;
			
			case 'project_members' :
				if ($additional_permissions['project_members'] == 2) {
					$permission_flag = true;
				} else {
					if (array_key_exists($iduser,$project_members['assigned_to'])) {
						$permission_flag = true;
					}
				}
			
			break;
		}
		
		return $permission_flag;
	}
	
	/**
	* function to get the email subscription flag in a project for an user
	* @param integer $idproject
	* @param integer $iduser
	* @return integer
	*
	* Subscription flags are -
	* 1 : Receive all discussion emails
	* 2 : Receive email only when @username is mentioned in the discussion
	* 3 : Do't receive any email on the discussion
	*/
	public function get_email_subscription_for_project_by_user($idproject , $iduser = 0) {
		$subscription_flag = 1;
		
		if ((int)$idproject > 0) {
			if ((int)$iduser == 0) $iduser = $_SESSION['do_user']->iduser;
			
			$qry = "select * from project_email_subscription where idproject = ? and iduser = ?";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject, $iduser));
			if ($stmt->rowCount() > 0) {
				$data = $stmt->fetch();
				$subscription_flag = $data['subscription_flag'];
			}
		}
		
		return $subscription_flag;
	}
	
	/**
	* function to get subscription information for all the users for a project
	* @param integer $iduser
	* @return array
	*/
	public function get_project_email_subscriptions($idproject) {
		$return_array = array();
		
		if ((int)$idproject > 0) {
			$qry = "select * from project_email_subscription where idproject = ?";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($idproject));
			if ($stmt->rowCount() > 0) {
				while ($data = $stmt->fetch()) {
					$return_array[$data['iduser']] = $data['subscription_flag'];
				}
			}
		}
		
		return $return_array;
	}
	
	public function eventChangeProjectEmailSubscriptionChoice(EventControler $evctl) {
		$err = '';
		
		if ((int)$evctl->idproject == 0) {
			$err = _('Missing project id');
		} elseif ((int)$evctl->subscriptionFlag == 0) {
			$err = _('Missing subscription value');
		} else {
			$this->getId($evctl->idproject);
			if ($this->getNumRows() == 0) {
				$err = _('Invalid project id');
			}
		}
		
		if ($err == '') {
			$iduser = $_SESSION['do_user']->iduser;
			$qry = "select * from project_email_subscription where idproject = ? and iduser = ?";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($evctl->idproject, $iduser));
			if ($stmt->rowCount() > 0) {
				$data = $stmt->fetch();
				$id = $data['idproject_email_subscription'];
				$upd = "update project_email_subscription set subscription_flag = ? where idproject_email_subscription = ?";
				$this->getDbConnection()->executeQuery($upd,array($evctl->subscriptionFlag, $id));
			} else {
				$ins = "
				insert into project_email_subscription (idproject, iduser, subscription_flag)
				values
				(?, ?, ?)
				";
				$this->getDbConnection()->executeQuery($ins,array($evctl->idproject, $iduser, $evctl->subscriptionFlag));
			}
			echo '1';
		} else {
			echo $err;
		}
	}
}