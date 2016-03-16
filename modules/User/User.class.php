<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class User, maintain user related actions
* @author Abhik Chakraborty
*/ 
	

class User extends DataObject {
	public $table = "user";
	public $primary_key = "iduser";
	
	/* variable to store the role information */
	protected $user_role_info = array();

	/* variable to hold the module privileges for the crm */
	protected $user_module_privileges = array() ;

	/* varibale to hold the subordinate users */
	protected $subordinate_users = array();

	/* variable to hold the top level data share permission for modules */
	protected $module_data_share_permissions = array();

	protected $user_related_to_groups = array();

	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("user_name","firstname","lastname","email","is_admin","user_avatar");

	/* Array holding the field values to be displayed by the popup section for the Contacts */
	public $popup_selection_fields = array("user_name","firstname","lastname");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "user_name";
	
	/* default order by in the list view */
	protected $default_order_by = "`user`.`user_name`";
    

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
		$qry = "select * from ".$this->getTable()." where `deleted` = 0 ";
		$this->setSqlQuery($qry);
	}

	/**
	* function to get all the users 
	* @return the object with the data
	* @see modules/Settings/group_add.php
	*/
	public function get_all_users() {
		$sql = "select * from ".$this->getTable()." where `deleted` = 0  order by firstname asc" ;
		$this->query($sql);
	}
	
	/**
	* function get the active users
	* @return array
	*/
	public function get_active_users() {
		$sql = "select * from ".$this->getTable()." where `deleted` = 0  order by firstname asc" ;
		$this->query($sql) ;
		$return_array = array() ;
		while ($this->next()) {
			$return_array[] = array(
				"iduser"=>$this->iduser,
				"user_name"=>$this->user_name,
				"firstname"=>$this->firstname,
				"lastname"=>$this->lastname,
				"email"=>$this->email
			);
		}
		return $return_array ;
	}

	/**
	* Event function to add User
	* @param object $evctl
	*/
	public function eventAddRecord(EventControler $evctl) { 
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			$do_crm_fields = new CRMFields();
			$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
			$data_array = array() ; 
			foreach ($crm_fields as $crm_fields) {
				$field_name = $crm_fields["field_name"];
				$field_value = $do_crm_fields->convert_field_value_onsave($crm_fields,$evctl);
				if (is_array($field_value) && count($field_value) > 0) {
					if ($field_value["field_type"] == 12) {
						$value = $field_value["name"];
						$avatar_array[] = $field_value ;
					}
				} else { $value = $field_value ; }
				$data_array[$field_name] = $value ;
			}
			$this->insert($this->getTable(),$data_array);
			$iduser = $this->getInsertId() ;
			if ($iduser > 0) {
			// check if the avatar is uploaded and if yes update the files and attachment object
				if (is_array($avatar_array) && count($avatar_array) > 0) {
					foreach ($avatar_array as $avatar) {
						if (is_array($avatar) && array_key_exists('name',$avatar)) {
							$do_files_and_attachment = new CRMFilesAndAttachments();
							$do_files_and_attachment->addNew();
							$do_files_and_attachment->file_name = $avatar["name"];
							$do_files_and_attachment->file_mime = $avatar["mime"];
							$do_files_and_attachment->file_size = $avatar["file_size"];
							$do_files_and_attachment->file_extension = $avatar["extension"];
							$do_files_and_attachment->idmodule = 7;
							$do_files_and_attachment->id_referrer = $iduser;
							$do_files_and_attachment->iduser = $iduser;
							$do_files_and_attachment->date_modified = date("Y-m-d H:i:s");
							$do_files_and_attachment->add() ;
						}
					}
				}
				$_SESSION["do_crm_messages"]->set_message('success',_('New user has been added successfully ! '));
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('User could not be added !'));
			}
			$dis = new Display(NavigationControl::getNavigationLink("User","users"));
			$evctl->setDisplayNext($dis) ; 
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to add record !'));
			$next_page = NavigationControl::getNavigationLink("User","list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}

	/**
	* Event Function to update user information
	* @param object $evctl
	*/
	public function eventEditRecord(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		$iduser = (int)$evctl->sqrecord;
		if ($iduser > 0 && true === $permission) {
			$this->getId($iduser);
			$do_crm_fields = new CRMFields();
			$crm_fields = $do_crm_fields->get_field_information_by_module_as_array((int)$evctl->idmodule);
			$data_array = array() ; 
			foreach ($crm_fields as $crm_fields) {
				if ($crm_fields["field_type"] == 11) continue ;
				$field_name = $crm_fields["field_name"];
				$field_value = $do_crm_fields->convert_field_value_onsave($crm_fields,$evctl,'edit');
				if (is_array($field_value) && count($field_value) > 0) {
					if($field_value["field_type"] == 12){
						$value = $field_value["name"];
						$avatar_array[] = $field_value ;
					}
				} else { $value = $field_value ; }
				$data_array[$field_name] = $value ;
			}
			$this->update(array($this->primary_key=>$iduser),$this->getTable(),$data_array);
			if (is_array($avatar_array) && count($avatar_array) >0) {
				foreach ($avatar_array as $avatar) {
					if (is_array($avatar) && array_key_exists('name',$avatar)) {
						$do_files_and_attachment = new CRMFilesAndAttachments();
						$do_files_and_attachment->addNew();
						$do_files_and_attachment->file_name = $avatar["name"];
						$do_files_and_attachment->file_mime = $avatar["mime"];
						$do_files_and_attachment->file_size = $avatar["file_size"];
						$do_files_and_attachment->file_extension = $avatar["extension"];
						$do_files_and_attachment->idmodule = 7;
						$do_files_and_attachment->id_referrer = $iduser;
						$do_files_and_attachment->iduser = 1;
						$do_files_and_attachment->date_modified = date("Y-m-d H:i:s");
						$do_files_and_attachment->add() ;
					}
				}
			}
			// Record the history
			$do_data_history = new DataHistory();
			$do_data_history->add_history($iduser,7,'edit');
			$do_data_history->add_history_value_changes($iduser,7,$this,$evctl);
			$_SESSION["do_crm_messages"]->set_message('success',_('Data updated successfully !'));
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to add record !'));
			$next_page = NavigationControl::getNavigationLink("User","list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}

	/**
	* event function to reset the password
	* @param object $evctl
	*/
	public function eventChangePassword(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			if ($evctl->fieldname !='' && $evctl->sqrecord > 0) {
				if ($evctl->password !='') {
					$sql = "
					update ".$this->getTable()." 
					set `".$evctl->fieldname."` = ? 
					where iduser = ? ";
					$this->getDbConnection()->executeUpdate($sql,array(MD5($evctl->password),$evctl->sqrecord));
					$_SESSION["do_crm_messages"]->set_message('success',_('Password is changed successfully !'));
				} else {
					$_SESSION["do_crm_messages"]->set_message('error',_('Error updating the password !'));
				}
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('Error updating the password !'));
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to add record !'));
			$next_page = NavigationControl::getNavigationLink("User","list");
			$dis = new Display($next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}

	/**
	* function to check the unique value for a field
	* @param string $field_name
	* @param string $field_val
	* @param string $action
	* @param string $sqrecord
	* in the add action checks if the value is already in the database then return false.
	* in the edit action check if the new value is already in the database then return false
	*/
	public function check_unique($field_name,$field_val,$action,$sqrecord) {
		$retval = true ;
		if ($action == 'add') {
			$this->query("select iduser from ".$this->getTable()." where `".$field_name."` = ?",array($field_val));
			if ($this->getNumRows() > 0) {
				$retval = false ;
			}
		} else {
			if ($sqrecord > 0) {
				$this->query("select * from ".$this->getTable()." where iduser = ?",array($sqrecord));
				if ($this->getNumRows() > 0) {
					$this->next();
					$existing_fld_value = $this->$field_name;
					if ($existing_fld_value == $field_val) {
						$retval = true ;
					} else {
						$this->query("select iduser from ".$this->getTable()." where `".$field_name."` = ?",array($field_val));
						if ($this->getNumRows() > 0) {
							$retval = false ;
						}
					}
				}
			}
		}
		return $retval ;
	}
	
	/**
	* Event function for login
	* @param object $evctl 
	*/
	public function eventLogin(EventControler $evctl) {
		$login_success = false ;
		if ($evctl->user_name !='' && $evctl->user_password !='') {
			$qry = "
			select * from ".$this->getTable()." 
			where `user_name` = ? AND `password` = ?" ;
			$this->query($qry,array($evctl->user_name,MD5($evctl->user_password)));
			if ($this->getNumRows() == 1) { 
				$this->next(); // fetch the first row
				$iduser = $this->iduser;
				if ($this->is_active <> 'Yes') {
					$_SESSION["do_crm_messages"]->set_message('error',_('The account is not active, please ask your admin to check this !'));
				} else {
					$login_success = true ;
				}
			} elseif ($this->getNumRows() > 1) {
				$_SESSION["do_crm_messages"]->set_message('info',_('This is not your fault, you have entered correct login details but some other user has same login details, which is very unlikely. Please ask your admin to change the username or password. !'));
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('Wrong login details !'));
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('Please enter a valid username and password !'));
		}
		if ($login_success === true) {
			$this->sessionPersistent("do_user","logout.php",TTL_LONG);
			$this->set_user_crm_privileges();
			$subordinate_users = $this->get_subordinate_users_by_iduser($iduser);
			$this->set_subordinate_users($subordinate_users);
			$do_mod_datashare_permission = new ModuleToDatashareRelation();
			$this->set_module_data_share_permissions($do_mod_datashare_permission->get_all_datashare_permissions());
			$dis = new Display($evctl->goto); //@see view/login_view
			if ((int)$evctl->sqrecord > 0) {
				$dis->addParam("sqrecord",(int)$evctl->sqrecord);
			}
			//do login audit
			$do_login_audit = new LoginAudit();
			$do_login_audit->do_login_audit();
			//load the global setting object
			if (!is_object($_SESSION["do_global_settings"])) {
				$do_global_settings = new CRMGlobalSettings();
				$do_global_settings->sessionPersistent("do_global_settings", "logout.php", TTL);
			}
			//update the unseen feed to viewed = 1 on login
			$do_livefeed_display = new LiveFeedDisplay();
			$do_livefeed_display->set_feed_viewed_onlogin($iduser);
			//finally do the re-direct
			$evctl->setDisplayNext($dis) ; 
		}	
	}
		 
	/**
	* function to set the different privileges for the CRM
	* the privileges are all defined on the profile so loading all the different privileges
	* sets the data in the form of an arrray in the persistent user object so that the data is
	* available across the CRM in the current session.
	* NOTE : any change in the profile permissions would require the user to logout so that on next 
	* login the new privileges are loaded again and become available for the current session.
	* This idea is to ignore same set of queries again and again for each time the privileges are checked
	* @see User::eventLogin()
	*/
	protected function set_user_crm_privileges() {
		$do_roles = new Roles();
		//Get the role details of the user
		$role_id = $this->idrole ;
		$this->set_user_role_info($do_roles->get_role_detail($role_id));
		
		// Set the groups to which the user is associated
		$do_group_user_rel = new GroupUserRelation();
		$this->set_user_associated_to_groups($do_group_user_rel->get_groups_by_user($_SESSION["do_user"]->iduser,array(),true)) ;

		// Now lets find the profile and actual permissions set in the profile 
		$do_profile = new Profile();
		$do_role_profile_rel = new RoleProfileRelation();
		$do_module_standard_permission = new ModuleStandardPermission();
		$do_role_profile_rel->get_pofiles_related_to_role($role_id);
		$module_permissions = array();
		$module_standard_permissions_per_profile_array = array();
		if ($do_role_profile_rel->getNumRows() > 0) {
			$associated_profiles = array();
			while ($do_role_profile_rel->next()) {
				$associated_profiles[]= $do_role_profile_rel->idprofile ;
			}
			// Loading the active modules for the CRM available. The object "do_module" is persistent and is instantiated in module.php 
			if (!is_object($_SESSION["do_module"])) {
				$do_module = new Module();
				$do_module->sessionPersistent("do_module", "logout.php", TTL);
				$_SESSION["do_module"]->load_active_modules();
			}
			$active_modules = $_SESSION["do_module"]->get_active_modules_for_crm();
			// variables to hold the permissions when user is associated with multiple roles
			$profile_standard_permission_rel_previous = array() ;
			$profile_module_rel_previous = array() ;
			foreach ($associated_profiles as $idprofile) {
				// Getting all the module standard permissions vailable to the profile
				$profile_standard_permission_rel = $do_profile->get_all_module_standard_permissions($idprofile);
				// Getting if the module is permitted for the profile
				$profile_module_rel = $do_profile->get_all_module_permissions($idprofile);
				foreach ($active_modules as $module=>$idmodule) {
					if (array_key_exists($profile_module_rel[$idmodule],$profile_module_rel)) {
						if (count($profile_module_rel_previous) > 0 && array_key_exists($profile_module_rel_previous[$idmodule],$profile_module_rel_previous)) {
							if ($profile_module_rel_previous[$idmodule] > $module_permissions[$idmodule]["module_permission"]) {
								$module_permissions[$idmodule]["module_permission"] = $profile_module_rel_previous[$idmodule];
							} else {
								$module_permissions[$idmodule]["module_permission"] = $profile_module_rel[$idmodule];
							}
						} else {
							$module_permissions[$idmodule]["module_permission"] = $profile_module_rel[$idmodule];
						}
						$profile_module_rel_previous[$idmodule] = $profile_module_rel[$idmodule] ;
					}
					// Loading the module standard permissions
					$do_module_standard_permission->get_module_standard_permissions($idmodule);
					if ($do_module_standard_permission->getNumRows() > 0) {
						while ($do_module_standard_permission->next()) {
							if (array_key_exists($profile_standard_permission_rel[$idmodule][$do_module_standard_permission->idstandard_permission],$profile_standard_permission_rel)) {
								if (count($profile_standard_permission_rel_previous) > 0 && array_key_exists($profile_standard_permission_rel_previous[$idmodule][$do_module_standard_permission->idstandard_permission],$profile_standard_permission_rel_previous)) {
									if ($profile_standard_permission_rel_previous[$idmodule][$do_module_standard_permission->idstandard_permission] > $profile_standard_permission_rel[$idmodule][$do_module_standard_permission->idstandard_permission]) {
										$module_standard_permissions_per_profile_array[$idmodule][$do_module_standard_permission->idstandard_permission] = $profile_standard_permission_rel_previous[$idmodule][$do_module_standard_permission->idstandard_permission] ;
									} else {
										$module_standard_permissions_per_profile_array[$idmodule][$do_module_standard_permission->idstandard_permission] = $profile_standard_permission_rel[$idmodule][$do_module_standard_permission->idstandard_permission] ;
									}
								} else {
									$module_standard_permissions_per_profile_array[$idmodule][$do_module_standard_permission->idstandard_permission] = $profile_standard_permission_rel[$idmodule][$do_module_standard_permission->idstandard_permission] ;
								}
								$profile_standard_permission_rel_previous[$idmodule][$do_module_standard_permission->idstandard_permission] = $profile_standard_permission_rel[$idmodule][$do_module_standard_permission->idstandard_permission] ;
							}
						}
					} else { 
						$module_standard_permissions_per_profile_array[$idmodule][2] = 1;
					}
				}
			}
			foreach ($module_standard_permissions_per_profile_array as $idmodule=>$standard_permissions) {
				$module_permissions[$idmodule]["standard_permissions"] = $standard_permissions ;
			}
		}
		$this->set_user_module_privileges($module_permissions);
	}
    
	/**
	* event function to delete users by selecting and clicking on the delete button in list page
	* multiple users could be deleted by this action
	* @param object $evctl
	* @see popups/delete_user_modal.php
	* @see modules/User/UserDelete.class.php
	*/
	public function eventDeleteRecordMul(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			$ids = $evctl->ids;
			$id_to_transfer = $evctl->user_selector ;
			include_once("modules/User/UserDelete.class.php");
			$do_user_delete = new UserDelete() ;
			if ($do_user_delete->delete_multiple_user($ids,$id_to_transfer) === true) {
				$_SESSION["do_crm_messages"]->set_message('success',_('Users have been deleted successfully and the related data has been transferred to the selected user !'));
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('Users can not be deleted !'));
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to delete record !'));
		}
	}
		
	/**
	* event function to delete users by clicking on the delete link in list page
	* @param object $evctl
	* @see popups/delete_user_modal.php
	* @see modules/User/UserDelete.class.php
	*/
	public function eventDeleteRecord(EventControler $evctl) {
		$permission = ($_SESSION["do_user"]->is_admin == 1 ? true:false);
		if (true === $permission) {
			$id = (int)$evctl->id;
			$id_to_transfer = $evctl->user_selector ;
			include_once("modules/User/UserDelete.class.php");
			$do_user_delete = new UserDelete() ;
			if ($do_user_delete->delete_single_user($id,$id_to_transfer) === true) {
				$_SESSION["do_crm_messages"]->set_message('success',_('User has been deleted successfully and the related data has been transferred to the selected user !'));
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('User can not be deleted !'));
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('You do not have permission to delete record !'));
		}
	}
		
	/**
	* function to set the user role information
	* @param array $info
	*/
	public function set_user_role_info($info) {
		$this->user_role_info = $info;
	}
		
	/**
	* function to get the user role info
	* @return array user_role_info
	*/
	public function get_user_role_info() {
		return $this->user_role_info ;
	}

	/**
	* function to set the user module privileges
	* @param array privileges
	*/
	public function set_user_module_privileges($privileges) {
		$this->user_module_privileges = $privileges ;
	}

	/**
	* function to get the user module privileges
	* @return array user_module_privileges
	*/
	public function get_user_module_privileges() {
		return $this->user_module_privileges ;
	}

	/**	
	* function to set the subordinate users in a member variable
	* @param array $users
	*/
	public function set_subordinate_users($users) {
		$this->subordinate_users = $users ;
	}
		
	/**
	* function to get the subordinate users which was set in the member variable
	* @return array $subordinate_users
	*/
	public function get_subordinate_users() {
		return $this->subordinate_users ;
	}
		
	/**
	* function to set the module data share permissions on the persistent User Object
	* @param array $permission
	* @see eventLogin()
	* @see modules/Settings/ModuleToDatashareRelation.class.php
	*/
	public function set_module_data_share_permissions($permission) {
		$this->module_data_share_permissions = $permission;
	}

	/**
	* function to get the module data share permission from the persistent User Object
	* @return array $module_data_share_permissions
	*/
	public function get_module_data_share_permissions() {
		return $this->module_data_share_permissions ;
	}

	/**
	* function to get the list of all the users whos data is accessible by the user
	* Checks the user role and then get all the users who are subordinate as per the role hierarchy.
	* Also gets the users who are reporting to the user
	* Make sure to exclude the user from the return array for which we are finding the subordinate user.
	* @param integer $iduser
	* @return array $subordinate_users , containing all the subordinate_users
	*/
	public function get_subordinate_users_by_iduser($iduser = '') {
		$subordinate_users = array();
		if ($iduser == '') {
			$iduser = $_SESSION["do_user"]->iduser ;
			$role_id = $_SESSION["do_user"]->idrole ;
		} else {
			$qry = "select * from user where iduser = ?" ;
			$stmt = $this->getDbConnection()->executeQuery($qry,array($iduser));
			$row = $stmt->fetch() ;
			$role_id = $row["idrole"] ;
		}
		$do_role = new Roles();
		$do_role->getId($role_id);
		$parent_role = $do_role->parentrole ;
		$qry = "
		select u.iduser from ".$this->getTable()." u 
		join role r on r.idrole = u.idrole
		where 
		r.parentrole like ?
		AND u.idrole <> ?
		AND u.iduser <> ?
		" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array("$parent_role%",$role_id,$iduser));
		if ($stmt->rowCount() > 0) {
			while ($row = $stmt->fetch()) {
				$subordinate_users[] = $row["iduser"];
			}
		}
		//Get the users assigned to
		$qry = "select iduser from ".$this->getTable()." where reports_to = ?" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($iduser));
		if ($stmt->rowCount() > 0) {
			while ($row = $stmt->fetch()) {
				$subordinate_users[] = $row["iduser"];
			}
		}
		return $subordinate_users;
	}
		
	/**
	* function to get the parent users by iduser
	* @param integer $iduser
	* @return array if record found else return false
	*/
	public function get_parent_users_by_iduser($iduser = '') {
		$parent_users = array();
		if ($_SESSION["do_user"]->iduser > 0) {
			$iduser = $_SESSION["do_user"]->iduser ;
			$role_id = $_SESSION["do_user"]->idrole ;
		} else {
			$this->getId($iduser);
			$role_id = $this->role_id ;
		}
		$do_role = new Roles();
		$do_role->getId($role_id);
		$parent_role = $do_role->parentrole ;
		
		$explode_parent_roles = explode("::",$parent_role);
		$paresed_roles = '' ;
		if (count($explode_parent_roles) > 0) {
			foreach ($explode_parent_roles as $parent_roles) {
				$users = array();
				if ($paresed_roles == '') {
					$paresed_roles = $parent_roles;
				} else {
					$paresed_roles = $paresed_roles.'::'.$parent_roles;
				}
				$users = $this->get_user_by_parentrole($paresed_roles); 
				$parent_users = array_merge($parent_users,$users);
			}
		}
		if (count($parent_users) > 0) {
			return array_diff(array_unique($parent_users),array($iduser));
		} else {
			return false ;
		}
	}

	/**
	* function to get the users by parent role
	* @param string parent_role
	* @return array
	*/
	public function get_user_by_parentrole($parent_role) {
		$qry = "
		select u.iduser from ".$this->getTable()." u
		join role r on r.idrole = u.idrole
		where r.parentrole = ?
		" ;
		$stmt = $this->getDbConnection()->executeQuery($qry,array($parent_role));
		$ret_array = array();
		if ($stmt->rowCount() > 0) {
			while ($row = $stmt->fetch()) {
				$ret_array[] = $row["iduser"];
			}
		}
		return $ret_array ;
	}
    
	/**
	* function to set the user associated to groups
	* @param array $groups
	*/
	public function set_user_associated_to_groups($groups) {
		$this->user_related_to_groups = $groups ;
	}
    
	/**
	* function to get the user associated with groups
	* @return array $user_related_to_groups
	*/
	public function get_user_associated_to_groups() {
		return $this->user_related_to_groups ;
	}
    
	/**
	* event function to signout
	* @param object $evctl
	*/
	public function eventLogout(EventControler $evctl) {
		//do login audit
		$do_login_audit = new LoginAudit();
		$do_login_audit->do_login_audit("Logout");
		$this->setFree();
		$this->free();
		// Unset all of the session variables.
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		// Finally, destroy the session.
		session_destroy();
		$dis = new Display(NavigationControl::getNavigationLink("User","login"));
		$evctl->setDisplayNext($dis) ; 
	}	
	
	/**
	* event function to change the user profile update
	* @param object $evctl
	* @return string
	*/
	public function eventUploadUserAvatar(EventControler $evctl) { 
		if ($_FILES["user_avatar"]["name"] == '') {
			echo '0' ;
		} else {
			if ($_FILES['user_avatar']['tmp_name'] != '') {
				$file_size = $_FILES['user_avatar']['size'] ;
				$hidden_file_name = 'upd_user_avatar' ;
				$current_file_name_in_db = $evctl->$hidden_file_name  ;
				if ($current_file_name_in_db != '') {
					FieldType12::remove_thumb($current_file_name_in_db) ;
				}
				$value = FieldType12::upload_avatar($_FILES['user_avatar']['tmp_name'],$_FILES['user_avatar']['name']) ;
				if (is_array($value) && array_key_exists('name',$value)) {
					$qry = "
					update `".$this->getTable()."`
					set `user_avatar` = ?
					where `iduser` = ?
					" ;
					$this->getDbConnection()->executeQuery($qry,array($value['name'],$_SESSION["do_user"]->iduser)) ;
					$do_files_and_attachment = new CRMFilesAndAttachments();
					$do_files_and_attachment->addNew();
					$do_files_and_attachment->file_name = $value["name"];
					$do_files_and_attachment->file_mime = $value["mime"];
					$do_files_and_attachment->file_size = $file_size ;
					$do_files_and_attachment->file_extension = $value["extension"];
					$do_files_and_attachment->idmodule = 7;
					$do_files_and_attachment->id_referrer = $_SESSION["do_user"]->iduser;
					$do_files_and_attachment->iduser = $_SESSION["do_user"]->iduser;
					$do_files_and_attachment->date_modified = date("Y-m-d H:i:s");
					$do_files_and_attachment->add() ;
					$_SESSION["do_user"]->user_avatar = $value["name"] ;
					echo FieldType12::get_file_name_with_path($value["name"],'s') ;
				} else {
					echo '0' ;
				}
			} else {
				echo '0' ;
			}
		}
	}
}