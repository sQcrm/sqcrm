<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Tasks
* Handles project tasks
* @author Abhik Chakraborty
*/ 
	
class Tasks extends DataObject {
	public $table = "tasks";
	public $primary_key = "idtasks";
	
	/**
	* sub module id for Tasks 
	* @WARNING: do not change this id
	*/
	protected $sub_module_id = 9000;
	
	/**
	* function to get the sub module id
	* @return sub_module_id
	*/
	public function get_sub_module_id() {
		return $this->sub_module_id;
	}
	
	
	public function get_tasks($idproject,$iduser = 0) {
	}
	
	/**
	* function to get the task by id
	* @param integer $id
	* @return DataObject
	*/
	public function getId($id) {
		$qry = "
		select t.*, 
		u.user_name,
		u.firstname,
		u.lastname,
		u.email,
		u.user_avatar,
		ts.status as task_status_name,
		tp.priority as task_priority 
		from tasks t 
		join user u on u.iduser = t.created_by 
		left join task_status ts on ts.idtask_status = t.task_status 
		left join task_priority tp on tp.idtask_priority = t.priority
		where t.idtasks = ?
		";
		$this->query($qry,array($id));
		return $this->next();
	}
	
	/**
	* function to get the task activity
	* @param integer $idtasks
	* @param integer $start_after
	* @return void
	*/
	public function get_task_activity($idtasks, $start_after = 0) {
		if ((int)$start_after == 0) {
			$qry = "
			select ta.*,  
			u.user_name,
			u.firstname,
			u.lastname,
			u.email,
			u.user_avatar
			from task_activity ta
			join user u on u.iduser = ta.iduser
			where ta.idtasks = ?
			order by ta.idtask_activity
			";
			$this->query($qry,array($idtasks));
		} else {
			$qry = "
			select ta.*,  
			u.user_name,
			u.firstname,
			u.lastname,
			u.email,
			u.user_avatar
			from task_activity ta
			join user u on u.iduser = ta.iduser
			where ta.idtasks = ? and ta.idtask_activity > ?
			order by ta.idtask_activity
			";
			$this->query($qry,array($idtasks,$start_after));
		}
	}
	
	/**
	* function to add the task activity
	* @param integer $idtasks
	* @param array $data
	* @param integer $iduser
	* @return integer last insert id
	*/
	public function add_task_activity($idtasks, $data, $iduser=0) {
		if ((int)$iduser == 0) $iduser = $_SESSION["do_user"]->iduser;
		if ((int)$idtasks == 0) return ;
		if (!is_array($data)) return;
		
		$allow_note_edit = 0;
		if ($data['activity_type'] == 1 && array_key_exists('allow_note_edit',$data)) {
			$allow_note_edit = $data['allow_note_edit'];
		}
		
		$time_log = 0;
		if ($data['activity_type'] == 1 && array_key_exists('time_log',$data)) {
			$time_log= $data['time_log'];
		}
		
		$qry = "
		insert into `task_activity` 
		(`idtasks`,`iduser`,`description`,`date_added`,`activity_type`,`allow_note_edit`,`time_log`)
		values
		(?, ?, ?, ?, ?, ?, ?)
		";
		$stmt = $this->getDbConnection()->executeQuery(
			$qry,
			array(
				$idtasks,
				$iduser,
				$data['description'],
				date('Y-m-d H:i:s'),
				$data['activity_type'],
				$allow_note_edit,
				$time_log
			)
		);
		
		return $this->getDbConnection()->lastInsertId();
	}
	
	/**
	* function to get the task priority
	* @param integer $idpriority
	* @return array
	*/
	public function get_task_priority($idpriority = 0) {
		$qry = "select * from `task_priority` order by `idtask_priority`";
		$stmt = $this->getDbConnection()->executeQuery($qry);
		$return_data = array();
		
		while ($data = $stmt->fetch()) {
			$return_data[] = array(
				'id'=>$data['idtask_priority'],
				'priority'=>$data['priority'],
				'selected'=>($data['idtask_priority'] == $idpriority ? 1: 0)
			);
		}
		
		return $return_data;
	}
	
	/**
	* function to get the task assignee
	* @param integer $idtasks
	* @return array
	*/
	public function get_task_assignees($idtasks) {
		$qry = "
		select `u`.* from `user` `u`
		join `task_assignee` `ta` on `ta`.`iduser` = `u`.`iduser`
		where `ta`.`idtasks` = ?
		";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks));
		if ($stmt->rowCount() > 0) {
			return $stmt->fetchAll();
		} else {
			return array();
		}
	}
	
	/**
	* function to add the task assignee
	* @param integer $idtasks
	* @param array $assignee
	* @return void
	*/
	public function add_task_assignees($idtasks, $assignee) {
		$assignee_array = explode(',',$assignee);
		if (is_array($assignee_array) && count($assignee_array) > 0) {
			foreach ($assignee_array as $iduser) {
				$qry = "
				insert into `task_assignee`
				(`idtasks`,`iduser`) 
				values
				(?,?)
				";
				$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks,$iduser));
			}
		}
	}
	
	/**
	* function to remove the task assignee
	* @param integer $idtasks
	* @param integer $assignee
	*/
	public function remove_task_assignee($idtasks, $assignee) {
		$qry = "delete from task_assignee where idtasks = ? and iduser = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks,$assignee));
	}
	
	/**
	* function to add a task label
	* @param string $label
	* @return integer last insert id
	*/
	public function add_new_task_label($label) {
		$label = strtolower(trim($label));
		$qry = "insert into `task_labels` (`label`) values (?)";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($label));
		return $this->getDbConnection()->lastInsertId(); 
	}
	
	/**
	* function to check if the label exists by id
	* @param integer $id
	* @return boolean 
	*/
	public function check_label_exists_by_id($id) {
		$qry = "select * from `task_labels` where `idtask_labels` = ? ";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($id));
		if ($stmt->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* function to get all the task labels
	* @return array
	*/
	public function get_task_labels() {
		$qry = "select `idtask_labels` as `id`,`label` as `name` from `task_labels`";
		$stmt = $this->getDbConnection()->executeQuery($qry);
		
		if ($stmt->rowCount() > 0) {
			return $stmt->fetchAll();
		} else {
			return array();
		}
	}
	
	/**
	* function to get the attached task labels for a given task
	* @param integer $idtasks
	* @return array
	*/
	public function get_attached_task_labels($idtasks) {
		$qry = "
		select `l`.* from `task_labels` `l` 
		inner join `task_label_rel` `tlr` on `tlr`.`idtask_labels` = `l`.`idtask_labels`
		where `tlr`.`idtasks` = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks));
		
		if ($stmt->rowCount() > 0) {
			return $stmt->fetchAll();
		} else {
			return array();
		}
	}
	
	/**
	* function to get the note count for a given task
	* @param integer $idtasks
	* @return integer
	*/
	public function get_task_note_count($idtasks) {
		$qry = "select count(*) as total from task_activity where idtasks = ? and activity_type = 1";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks));
		$data = $stmt->fetch();
		return $data['total'];
	}
	
	/**
	* function to get the count of task participants
	* @param integer $idtasks
	* @return integer
	*/
	public function get_task_participants_count($idtasks) {
		$qry = "select count(distinct iduser) as total from task_activity where idtasks = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks));
		$data = $stmt->fetch();
		return $data['total'];
	}
	
	/**
	* function to add a label to a given task
	* @param integer $idtasks
	* @param array $labels
	* @return void
	*/
	public function add_attached_task_labels($idtasks, $labels) {
		if (is_array($labels) && count($labels) > 0) {
			$qry = "
			insert into `task_label_rel` 
			(`idtasks`,`idtask_labels`)
			values
			(?,?)
			";

			foreach ($labels as $key=>$val) {
				if (true === $this->check_label_exists_by_id($val)) {
					$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks,$val));
				} else {
					if (strlen($val) > 2) {
						$id = $this->add_new_task_label($val);
						$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks,$id));
					}
				}
			}
		}
	}
	
	/**
	* function to delete a attached task label for a given task
	* @param integer $idtasks
	* @param integer $id
	* @return void
	*/
	public function delete_attached_task_label($idtasks, $id) {
		$qry = "delete from task_label_rel where idtasks = ? and idtask_labels = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idtasks,$id));
	}
	
	/**
	* function to update attached tasks label for a given task
	* @param integer $idtasks
	* @param array $labels
	* @return void
	*/
	public function update_attached_task_labels($idtasks, $labels) {
		$attached_labels = $this->get_attached_task_labels($idtasks);
		$attached_labels_ids = array();
		$attached_labels_names = array();
		
		if (count($attached_labels) > 0) {
			foreach ($attached_labels as $key=>$val) {
				$attached_labels_ids[] = $val['idtask_labels'];
				$attached_labels_names[$val['idtask_labels']] = $val['label'];
			}
		}
		
		if (is_array($labels) && count($labels) > 0) {
			$removed = array_diff($attached_labels_ids,$labels);
			$added = array_diff($labels,$attached_labels_ids);
			
			if (is_array($added) && count($added) > 0) {
				$this->add_attached_task_labels($idtasks,$added);
				$activity_descrption = '';
				$qry = "select * from task_labels where idtask_labels = ?";
				foreach ($added as $label) {
					$stmt = $this->getDbConnection()->executeQuery($qry,array($label));
					if ($stmt->rowCount() > 0) {
						$data = $stmt->fetch();
						$activity_descrption .= $data['label'].',';
					} else {
						$activity_descrption = $label.',';
					}
				}
				
				if ($activity_descrption != '') {
					$this->add_task_activity(
						$idtasks,
						array(
							'activity_type'=>2,
							'description'=>rtrim($activity_descrption,',')
						),
						$current_user
					);
				}
			}
			
			if (is_array($removed) && count($removed) > 0) {
				$activity_descrption = '';
				foreach ($removed as $label) {
					$this->delete_attached_task_label($idtasks,$label);
					if (array_key_exists($label,$attached_labels_names)) {
						$activity_descrption .= $attached_labels_names[$label].',';
					}
				}
				
				if ($activity_descrption != '') {
					$this->add_task_activity(
						$idtasks,
						array(
							'activity_type'=>5,
							'description'=>rtrim($activity_descrption,',')
						),
						$current_user
					);
				}
			}
		}
	}
	
	/**
	* event function to add a new task
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) {
		$err = '';
		$current_user = $_SESSION["do_user"]->iduser;
		
		if ((int)$evctl->idproject == 0) {
			$err = _('Missing project id for the task to be added');
		} elseif ($evctl->task_title == '' || strlen($evctl->task_title) < 3) {
			$err = _('Task title should be minimum of 3 character long');
		} else {
			$allow_action = false;
			$do_project = new Project();
			$do_project->getId($evctl->idproject);
			if ($do_project->getNumRows() > 0) {
				$project_members = $do_project->get_project_members($do_project);
				$allow_action = $do_project->check_additional_permissions($do_project, 'task_create');
				
				if (false === $allow_action) {
					$err = _('You are not autorized for this operation');
				}
				
			} else {
				$err = _('Project does not exists.');
			}
		}
		
		if (strlen($err) > 0) {
			$_SESSION["do_crm_messages"]->set_message('error',$err);
			
			if ((int)$evctl->idproject > 0) {
				$next_page = '/modules/Project/'.$evctl->idproject.'/task/add';
			} else {
				$next_page = NavigationControl::getNavigationLink('Project',"list");
			}
			
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ; 
		} else {
			$this->addNew();
			$this->idproject = $evctl->idproject;
			$this->task_title = $evctl->task_title;
			$this->created_by = $current_user;
			$this->date_created = date('Y-m-d H:i:s');
			$this->priority = $evctl->priority;
			$this->task_status = 1;
			$this->due_date = ($evctl->due_date != '' ? FieldType9::convert_before_save($evctl->due_date):'');
			$this->add();
			$idtasks = $this->getInsertId();
			
			$user_assigned = false;
			
			// check if note is added during a new task creation and if yes add to activity
			if ($evctl->task_note != '') {
				$this->add_task_activity(
					$idtasks,
					array(
						'activity_type'=>1,
						'description'=>$evctl->task_note,
						'allow_note_edit'=>($evctl->allow_note_edit == 'on' ? 1: 0),
						'time_log' => $evctl->time_log,
					),
					$current_user
				);
			}
			
			// check if label is added and if yes add to rel table and task activity
			if (is_array($evctl->task_labels) && count($evctl->task_labels) > 0) {
				$this->add_attached_task_labels($idtasks,$evctl->task_labels);
				$attached_labels = $this->get_attached_task_labels($idtasks);
				
				if (count($attached_labels) > 0) {
					$str = '';
					foreach ($attached_labels as $key=>$data) {
						$str .= $data['label'].',';
					}
					$str = rtrim($str,',');
					
				}
				
				$this->add_task_activity(
					$idtasks,
					array(
						'activity_type'=>2,
						'description'=>$str
					),
					$current_user
				);
			}
			
			// check if due date is added then add to activity
			if ($evctl->due_date != '') {
				$this->add_task_activity(
					$idtasks,
					array(
						'activity_type'=>3,
						'description'=>FieldType9::convert_before_save($evctl->due_date)
					),
					$current_user
				);
			}
			
			// check if task assignee is added then add to activity
			if ($evctl->task_assignee_users != '') {
				$user_assigned = true;
				$this->add_task_assignees($idtasks,$evctl->task_assignee_users);
				$this->add_task_activity(
					$idtasks,
					array(
						'activity_type'=>4,
						'description'=>$evctl->task_assignee_users
					),
					$current_user
				);
			}
			
			// send email
			$project_emailer = new ProjectEmailer();
			$email_data = array(
				'task_note' => $evctl->task_note_formatted,
				'idproject' => $evctl->idproject,
				'task_title' => $evctl->task_title,
				'project_name' => $do_project->project_name,
				'firstname' => $_SESSION['do_user']->firstname,
				'lastname' => $_SESSION['do_user']->lastname,
				'email' => $_SESSION['do_user']->email,
				'task_url' => SITE_URL.'/modules/Project/'.$evctl->idproject.'/task/'.$idtasks
			);
			$project_emailer->send_new_task_email($project_members, $email_data, $current_user);
			
			// send the assignee email
			if (true === $user_assigned) {
				$users_tobe_assigned = explode(',',$evctl->task_assignee_users);
				if (is_array($users_tobe_assigned) && count($users_tobe_assigned) > 0) {
					foreach ($users_tobe_assigned as $user_id) {
						$project_emailer->send_task_assigned_email(
						$user_id,
							array (
								'assignee_firstname'=>$_SESSION["do_user"]->firstname,
								'assignee_lastname'=>$_SESSION["do_user"]->lastname,
								'task_title'=>$evctl->task_title,
								'project_name'=>$do_project->project_name,
								'task_url' => SITE_URL.'/modules/Project/'.$evctl->idproject.'/task/'.$idtasks
							)
						);
					}
				}
			}
			
			$next_page = '/modules/Project/'.$evctl->idproject.'/task/'.$idtasks;
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ; 
		}
	}
	
	/**
	* event function to update a task
	* @param object $evctl
	* @return string
	*/
	public function eventUpdateRecord(EventControler $evctl) {
		$err = '';
		$current_user = $_SESSION["do_user"]->iduser;
		
		if ((int)$evctl->idproject == 0) {
			$err = _('Missing project id for the task to be added');
		} elseif ((int)$evctl->idtasks == 0) {
			$err = _('Invalid task id');
		} elseif ($evctl->edit_task_title == '' || strlen($evctl->edit_task_title) < 3) {
			$err = _('Task title should be minimum of 3 character long');
		} else {
			$allow_action = false;
			$do_project = new Project();
			$do_project->getId($evctl->idproject);
			if ($do_project->getNumRows() == 0) {
				$err = _('Project does not exists.');	
			} 
		}
		
		if ($err == '') {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ((int)$this->idproject == (int)$evctl->idproject) {
					$allow_action = $do_project->check_additional_permissions($do_project, 'task_edit', $this);
					
					if (false === $allow_action) {
						echo  _('You are not autorized for this operation');
						exit();
					}
					
					$record_priority_change = false;
					$record_title_change = false;
					
					if (trim($this->task_title) != trim($evctl->edit_task_title)) {
						$record_title_change = true;
					}
					
					if ((int)$this->priority != (int)$evctl->edit_priority) {
						$record_priority_change = true;
					}
					
					$qry = "
					update tasks set task_title = ?, priority = ?
					where idtasks = ?
					";
					$stmt = $this->getDbConnection()->executeQuery($qry,array($evctl->edit_task_title,$evctl->edit_priority, $evctl->idtasks));
					
					if (true === $record_title_change) {
						$this->add_task_activity(
							$evctl->idtasks,
							array(
								'activity_type'=>7,
								'description'=>$evctl->edit_task_title
							),
							$current_user
						);
					}
					
					if (true === $record_priority_change) {
						$this->add_task_activity(
							$evctl->idtasks,
							array(
								'activity_type'=>8,
								'description'=>$evctl->edit_priority
							),
							$current_user
						);
					}
					
					echo '1';
					
				} else {
					echo _('Invalid task id');
				}
			} else {
				echo _('Invalid task id');
			}
		} else {
			echo $err;
		}
	}
	
	/**
	* event function to update labels on given task
	* @param object $evctl
	* @return string
	*/
	public function eventUpdateLabels(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->idtasks == 0) {
			$err .= _('Missing task id.');
		} else {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ($this->idproject == (int)$evctl->idproject) {
					$do_project = new Project();
					$do_project->getId($evctl->idproject);
					$allow_task_edit = false;
					$signed_in_user = $_SESSION["do_user"]->iduser;
					$allow_task_edit = $do_project->check_additional_permissions($do_project, 'task_edit', $this);
					
					if (false === $allow_task_edit) {
						$err .= _('You are not authorized for this operation');
					} 
				} else {
					$err .= _('Invalid task id.');
				}
			} else {
				$err .= _('Invalid task id.');
			}
		}
		
		if ($err == '') {
			$this->update_attached_task_labels($evctl->idtasks, $evctl->labels);
			echo '1';
		} else {
			echo $err;
		}
	}
	
	/**
	* event function to add task assignee
	* @param object $evctl
	* @return string
	*/
	public function eventAddTaskAssignee(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->iduser == 0) {
			$err .= _('Missing user id to be added.');
		} elseif ((int)$evctl->idtasks == 0) {
			$err .= _('Missing task id.');
		} else {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ($this->idproject == (int)$evctl->idproject) {
					$do_project = new Project();
					$do_project->getId($evctl->idproject);
					$allow_task_assignees = false;
					$signed_in_user = $_SESSION["do_user"]->iduser;
					$allow_task_assignees = $do_project->check_additional_permissions($do_project, 'task_assignees', $this);
					
					if (false === $allow_task_assignees) {	
						$err .= _('You are not authorized for this operation');
					}
				} else {
					$err .= _('Invalid task id.');
				}
			} else {
				$err .= _('Invalid task id.');
			}
		}
		
		if ($err == '') {
			$this->add_task_assignees($evctl->idtasks,$evctl->iduser);
			$project_emailer = new ProjectEmailer();
			$project_emailer->send_task_assigned_email(
				$evctl->iduser,
				array (
					'assignee_firstname'=>$_SESSION["do_user"]->firstname,
					'assignee_lastname'=>$_SESSION["do_user"]->lastname,
					'task_title'=>$do_task->task_title,
					'project_name'=>$do_project->project_name
				)
			);
			$this->add_task_activity(
				$evctl->idtasks,
				array(
					'activity_type'=>4,
					'description'=>$evctl->iduser
				),
				$signed_in_user
			);
			echo '1';
		} else {
			echo $err;
		}
	}
	
	/**
	* event function to remove a task assignee
	* @param object $evctl
	* @return string
	*/
	public function eventRemoveTaskAssignee(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->iduser == 0) {
			$err .= _('Missing user id to be removed.');
		} elseif ((int)$evctl->idtasks == 0) {
			$err .= _('Missing task id.');
		} else {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ($this->idproject == (int)$evctl->idproject) {
					$do_project = new Project();
					$do_project->getId($evctl->idproject);
					$allow_task_assignees = false;
					$signed_in_user = $_SESSION["do_user"]->iduser;
					$allow_task_assignees = $do_project->check_additional_permissions($do_project, 'task_assignees', $this);
					
					if (false === $allow_task_assignees) {	
						$err .= _('You are not authorized for this operation');
					}
				} else {
					$err .= _('Invalid task id.');
				}
			} else {
				$err .= _('Invalid task id.');
			}
		}
		
		if ($err == '') {
			$this->remove_task_assignee($evctl->idtasks,$evctl->iduser);
			$this->add_task_activity(
				$evctl->idtasks,
				array(
					'activity_type'=>6,
					'description'=>$evctl->iduser
				),
				$signed_in_user
			);
			echo '1';
		} else {
			echo $err;
		}
	}
	
	/**
	* event function to change the task due date
	* @param object $evctl
	* @return string
	*/
	public function eventChangeTaskDueDate(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->idtasks == 0) {
			$err .= _('Missing task id.');
		} elseif ($evctl->due_date == '') {
			$err .= _('Please select a date before saving.');
		} else {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ($this->due_date == FieldType9::convert_before_save($evctl->due_date)) {
					$err .= _('Nothing to save.');
				} elseif ($this->idproject == (int)$evctl->idproject) {
					$do_project = new Project();
					$do_project->getId($evctl->idproject);
					$project_members = $do_project->get_project_members($do_project);
					$additional_permissions = $do_project->get_additional_permissions($this->idproject);
					$allow_task_edit = false;
					$signed_in_user = $_SESSION["do_user"]->iduser;
					$allow_task_edit = $do_project->check_additional_permissions($do_project, 'task_edit', $this);
					
					if (false === $allow_task_edit) {
						$err .= _('You are not authorized for this operation');
					} 
				} else {
					$err .= _('Invalid task id.');
				}
			} else {
				$err .= _('Invalid task id.');
			}
		}
		
		if ($err == '') {
			$due_date = FieldType9::convert_before_save($evctl->due_date);
			$qry = "update tasks set due_date = ? where idtasks = ?";
			$stmt = $this->getDbConnection()->executeQuery($qry,array($due_date, $evctl->idtasks));
			$this->add_task_activity(
				$evctl->idtasks,
				array(
					'activity_type'=>3,
					'description'=>$due_date
				),
				$signed_in_user
			);
			echo '1';
		} else {
			echo $err;
		}
	}
	
	/**
	* event function to add task note
	* @param object $evctl
	* @return string
	*/
	public function eventAddTaskNote(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->idtasks == 0) {
			$err = _('Missing task id');
		} elseif ((int)$evctl->sqrecord == 0) {
			$err = _('Missing project id');
		} elseif ($evctl->new_task_note == '') {
			$err = _('Please add some note before saving');
		} else {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ($this->idproject == (int)$evctl->sqrecord) {
					$do_project = new Project();
					$do_project->getId($evctl->sqrecord);
					$project_members = $do_project->get_project_members($do_project);
					$signed_in_user = $_SESSION["do_user"]->iduser;
					if (!array_key_exists($signed_in_user,$project_members['assigned_to']) && !array_key_exists($signed_in_user,$project_members['other_assignee'])) {
						$err = _('You are not authorized to perform this operation');
					}
				} else {
					$err = _('Invalid project id');
				}
			} else {
				$err = _('Invalid task id');
			}
		}
		
		$return_data = array();
		
		if ($err == '') {
			// add the note 
			$time_log =  ($evctl->new_time_log > 0 ? $evctl->new_time_log : 0);
			$qry = "
			insert into task_activity 
			(idtasks,iduser,description,date_added,activity_type,allow_note_edit,time_log)
			values
			(?,?,?,?,?,?,?)
			";
			$stmt = $this->getDbConnection()->executeQuery($qry,
				array(
					$evctl->idtasks,
					$signed_in_user,
					$evctl->new_task_note,
					date('Y-m-d H:i:s'),
					1,
					($evctl->allow_note_edit_new == 'on' ? 1: 0),
					$time_log
				)
			);
			
			$idactivity =  $this->getDbConnection()->lastInsertId(); 
			
			//if there are attached file then upload and link to the note
			if ((int)$idactivity > 0) {
				$files_count = count($_FILES["note_files_add"]["name"]);
				if ($files_count > 0) {
					for ($i=0;$i<$files_count;$i++) {
						$this->upload_task_note_files(
							$_FILES["note_files_add"]["name"][$i],
							$_FILES["note_files_add"]["tmp_name"][$i],
							$_FILES["note_files_add"]["type"][$i],
							$_FILES["note_files_add"]["size"][$i],
							$idactivity
						);
					}
				}
				
				// send email
				$email_data = array(
					'task_note' => $evctl->new_task_note_formatted,
					'idproject' => $evctl->sqrecord,
					'task_title' => $this->task_title,
					'project_name' => $do_project->project_name,
					'firstname' => $_SESSION['do_user']->firstname,
					'lastname' => $_SESSION['do_user']->lastname,
					'email' => $_SESSION['do_user']->email,
					'task_note_url' => SITE_URL.'/modules/Project/'.$evctl->sqrecord.'/task/'.$evctl->idtasks.'#activity-'.$idactivity
				);
				
				$project_emailer = new ProjectEmailer();
				$project_emailer->send_task_discussion_email($project_members,$email_data,$signed_in_user);
				
				echo '1';
				
			} else {
				echo _('Note could not be added, please try again !!');
			}
		} else {
			echo $err;
		}
	}
	
	/**
	* event function to delete task note (note along with attached files/ or just single attached file)
	* @param object $evctl
	* @return string
	*/
	public function eventDeleteTaskNote(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->idtasks == 0) {
			$err = _('Missing task id');
		} elseif ((int)$evctl->activity_id == 0) {
			$err = _('Missing note id');
		} elseif ((int)$evctl->sqrecord == 0) {
			$err = _('Missing project id');
		} elseif ($evctl->type != 'note' && $evctl->type != 'file') {
			$err = _('Invalid delete type');
		} else {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ($this->idproject == (int)$evctl->sqrecord) {
					$do_project = new Project();
					$do_project->getId($evctl->sqrecord);
					$project_members = $do_project->get_project_members($do_project);
					$signed_in_user = $_SESSION["do_user"]->iduser;
					if (array_key_exists($signed_in_user,$project_members['assigned_to']) || array_key_exists($signed_in_user,$project_members['other_assignee'])) {
						$qry = "select * from task_activity where idtask_activity = ?";
						$stmt = $this->getDbConnection()->executeQuery($qry,array($evctl->activity_id));
						if ($stmt->rowCount() > 0) {
							$data = $stmt->fetch();
							
							if ($data['iduser'] != $signed_in_user && $data['allow_note_edit'] == 0) {
								$err = _('You are not authorized to perform this operation');
							}
							
							if ($data['activity_type'] != 1) {
								$err = _('You are not authorized to perform this operation');
							}
						} else {
							$err = _('Invalid note id');
						}
					} else {
						$err = _('You are not authorized to perform this operation');
					}
				} else {
					$err = _('Invalid project id');
				}
			} else {
				$err = _('Invalid task id');
			}
		}
		
		if ($err == '') {
			if ($evctl->type == 'note') {
				$qry = "delete from task_activity where idtask_activity = ?";
				$stmt = $this->getDbConnection()->executeQuery($qry,array($evctl->activity_id));
				$do_files_and_attachment = new CRMFilesAndAttachments();
				$do_files_and_attachment->get_uploaded_files($this->get_sub_module_id(),(int)$evctl->activity_id);
				if ($do_files_and_attachment->getNumRows() > 0) {
					while ($do_files_and_attachment->next()) {
						$file_name = $do_files_and_attachment->file_name;
						$file_extension = $do_files_and_attachment->file_extension;
						$do_files_and_attachment->delete_record($do_files_and_attachment->idfile_uploads);
						FieldType21::remove_file($file_name,$file_extension);
					}
				}
				echo '1';
			} elseif ($evctl->type == 'file') {
				$do_files_and_attachment = new CRMFilesAndAttachments();
				$do_files_and_attachment->getId($evctl->flid);
				if ($do_files_and_attachment->getNumRows() > 0) {
					if ($do_files_and_attachment->idmodule == $this->get_sub_module_id()) {
						if ($do_files_and_attachment->id_referrer == $evctl->activity_id) {
							$file_name = $do_files_and_attachment->file_name;
							$file_extension = $do_files_and_attachment->file_extension;
							$do_files_and_attachment->delete_record($do_files_and_attachment->idfile_uploads);
							FieldType21::remove_file($file_name,$file_extension);
							echo '1';
						} else {
							echo _('Invalid file');
							exit();
						}
					} else {
						echo _('Invalid file');
						exit();
					}
				} else {
					echo _('Invalid file');
					exit();
				}
			}
		} else {
			echo $err;
		}
	}
	
	/**
	* event function to update a task note
	* @param object $evctl
	* @return string
	*/
	public function eventUpdateTaskNote(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->idtasks == 0) {
			$err = _('Missing task id');
		} elseif ((int)$evctl->activity_id == 0) {
			$err = _('Missing note id');
		} elseif ((int)$evctl->sqrecord == 0) {
			$err = _('Missing project id');
		} else {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ($this->idproject == (int)$evctl->sqrecord) {
					$do_project = new Project();
					$do_project->getId($evctl->sqrecord);
					$project_members = $do_project->get_project_members($do_project);
					$signed_in_user = $_SESSION["do_user"]->iduser;
					if (array_key_exists($signed_in_user,$project_members['assigned_to']) || array_key_exists($signed_in_user,$project_members['other_assignee'])) {
						$qry = "select * from task_activity where idtask_activity = ?";
						$stmt = $this->getDbConnection()->executeQuery($qry,array($evctl->activity_id));
						if ($stmt->rowCount() > 0) {
							$data = $stmt->fetch();
							
							if ($data['iduser'] != $signed_in_user && $data['allow_note_edit'] == 0) {
								$err = _('You are not authorized to perform this operation');
							}
							
							if ($data['activity_type'] != 1) {
								$err = _('You are not authorized to perform this operation');
							}
						} else {
							$err = _('Invalid note id');
						}
					} else {
						$err = _('You are not authorized to perform this operation');
					}
				} else {
					$err = _('Invalid project id');
				}
			} else {
				$err = _('Invalid task id');
			}
		}
		
		$return_data = array();
		
		if ($err == '') {
			$qry = "
			update task_activity set 
			description = ?,
			allow_note_edit = ?,
			time_log = ?
			where idtask_activity = ?
			";
			
			$allow_note_edit = ($evctl->allow_note_edit == 'on' ? 1: 0);
			
			$stmt = $this->getDbConnection()->executeQuery($qry,array(
				$evctl->task_note_edit,
				$allow_note_edit,
				$evctl->time_log,
				$evctl->activity_id
			));
			
			$files_count = count($_FILES["note_files_edit"]["name"]);
			if ($files_count > 0) {
				for ($i=0;$i<$files_count;$i++) {
					$this->upload_task_note_files(
						$_FILES["note_files_edit"]["name"][$i],
						$_FILES["note_files_edit"]["tmp_name"][$i],
						$_FILES["note_files_edit"]["type"][$i],
						$_FILES["note_files_edit"]["size"][$i],
						$evctl->activity_id
					);
				}
			}
			
			$return_data = array('status'=>'ok', 'id'=>$evctl->activity_id, 'data'=>FieldType200::display_value($evctl->task_note_edit));	
		} else {
			$return_data = array('status'=>'fail','id'=>$evctl->activity_id, 'err'=>$err);
		}
		
		echo json_encode($return_data);
	}
	
	/**
	* event function to parse the note data for emoji and mentions
	* @param object $evctl
	* @return string
	*/
	public function eventParseTaskNote(EventControler $evctl) {
		echo FieldType200::display_value($evctl->note,false);
	}
	
	/**
	* function to render the task priority html
	* @param integer $id
	* @param string $text
	* @return string
	*/
	public function render_task_priority_display($id,$text) {
		$priority = '';
		if ($id == 1) {
			$priority = '<span class="label" style="background-color:grey;">'.$text.'</span>';
		} elseif ($id == 2) {
			$priority = '<span class="label" style="background-color:green;">'.$text.'</span>';
		} elseif ($id == 3) {
			$priority = '<span class="label" style="background-color:blue;">'.$text.'</span>';
		} elseif ($id == 4) {
			$priority = '<span class="label" style="background-color:orange;">'.$text.'</span>';
		} elseif ($id == 5) {
			$priority = '<span class="label" style="background-color:red;">'.$text.'</span>';
		}
		
		return $priority;
	}
	
	/**
	* event function to render the task priority html
	* @param object $evctl
	* @return string
	*/
	public function eventRenderTaskPriorityDisplay(EventControler $evctl) {
		echo $this->render_task_priority_display($evctl->id, $evctl->priority);
	}
	
	/**
	* event function to close and re-open a task
	* @param object $evctl
	* @return string
	*/
	public function eventCloseReopenTask(EventControler $evctl) {
		$err = '';
		if ((int)$evctl->idtasks == 0) {
			$err = _('Missing task id');
		} elseif ((int)$evctl->idproject == 0) {
			$err = _('Missing project id');
		} elseif ((int)$evctl->type == 0) {
		} else {
			$this->getId($evctl->idtasks);
			if ($this->getNumRows() > 0) {
				if ($this->idproject == (int)$evctl->idproject) {
					$do_project = new Project();
					$do_project->getId($evctl->idproject);
					$project_members = $do_project->get_project_members($do_project);
					$signed_in_user = $_SESSION["do_user"]->iduser;
					$allow_task_close = false;
					$allow_task_close = $do_project->check_additional_permissions($do_project, 'task_close', $this);
					if (false === $allow_task_close) {
						$err = _('You are not authorized to perform this operation');
					}
				} else {
					$err = _('Invalid project id');
				}
			} else {
				$err = _('Invalid task id');
			}
		}
		
		if ($err == '') {
			$qry = "update tasks set task_status = ? where idtasks = ?";
			if ((int)$evctl->type == 1) {
				$stmt = $this->getDbConnection()->executeQuery($qry, array(2,$evctl->idtasks));
			} elseif ((int)$evctl->type == 2) {
				$stmt = $this->getDbConnection()->executeQuery($qry, array(1,$evctl->idtasks));
			}
			
			$this->add_task_activity(
				$evctl->idtasks,
				array(
					'activity_type'=>9,
					'description'=>$evctl->type
				),
				$signed_in_user
			);
			echo '1';
		} else {
			echo $err;
		}
	}
	
	/**
	* function to upload task notes
	* @param string $name
	* @param string $tempname
	* @param string $type
	* @param integer $sqrecord
	* @return void
	*/
	public function upload_task_note_files($name,$tempname,$type,$size,$sqrecord) {
		$field_value = FieldType21::upload_file($tempname,$name);
		$do_files_and_attachment = new CRMFilesAndAttachments();
		$do_files_and_attachment->addNew();
		$do_files_and_attachment->file_name = $field_value["name"];
		$do_files_and_attachment->file_mime = $type;
		$do_files_and_attachment->file_size = $size;
		$do_files_and_attachment->file_extension = $field_value["extension"];
		$do_files_and_attachment->idmodule = $this->get_sub_module_id();
		$do_files_and_attachment->id_referrer = $sqrecord;
		$do_files_and_attachment->iduser = $_SESSION["do_user"]->iduser ;
		$do_files_and_attachment->file_description = $name;
		$do_files_and_attachment->date_modified = date("Y-m-d H:i:s");
		$do_files_and_attachment->add();
		$do_files_and_attachment->free();
	}
	
}