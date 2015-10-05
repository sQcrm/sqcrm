<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Profile
* Maintain the profile information of crm
* @author Abhik Chakraborty
*/


class Profile extends DataObject {
	public $table = "profile";
	public $primary_key = "idprofile";
    
	/**
	* Public function to get all the profiles
	*/
	public function get_all_profiles() {
		$this->query("select * from ".$this->getTable());
	}
	
	/**
	* Adds the profile 
	* @param string $profile_name
	* @param string $profile_desc
	* @returns the last inserted id
	*/
	public function add_profile($profile_name,$profile_desc) {
		$this->addNew();
		$this->profilename = CommonUtils::purify_input($profile_name);
		$this->description = CommonUtils::purify_input($profile_desc);
		$this->editable  = 1;
		$this->add();
		return $this->getInsertId();
	}

	/**
	* event method to process the step one of profile add
	* @param object $evctl
	* @Note : sets the data in session and finally on actual add method the values are used
	* @see : Profile::eventAddNewProfile()
	*/
	public function eventAddNewProfileStep1(EventControler $evctl) {
		$_SESSION["profilename"] = $evctl->profilename;
		$_SESSION["description"] = $evctl->description;
		$dis = new Display($evctl->next_page);
		$evctl->setDisplayNext($dis) ; 
	}

	/**
	* Event method for adding a new profile
	* @param object $evctl
	* @returns last inserted id
	* @see Profile::add_profile()
	*/
	public function eventAddNewProfile(EventControler $evctl) {
		$idprofile = $this->add_profile($_SESSION["profilename"],$_SESSION["description"]);
		// first check if the global permissions are set or not
		if ($evctl->global_view_all == 'on') { $global_view_all = 1; }else{ $global_view_all = 0; }
		if ($evctl->global_addedit_all == 'on') { $global_addedit_all = 1; } else { $global_addedit_all = 0; }
		// All the standard permissions allowed for modules, example Home module does not have any add/edit/dele permissions
		$do_module = new Module();
		
		$do_mod_standard_permission = new ModuleStandardPermission();
		$do_mod_standard_permission->getAll();
		// Getting All the standard permissions ends here
		// 1=add/edit, 2 = view, 3 = delete ,this is hard coded
		$standard_permissions = array(1,2,3);
		// Array to keep all the profile to module permissions
		$profile_to_mod_permission_values = array();
		// Array to keep all the profile to module standard permissions
		$profile_to_mod_standard_permission_values = array() ;
		$do_module->get_all_active_module();
		while ($do_module->next()) {
			$form_post_variable_mod_permission = 'mod_'.$do_module->idmodule;
			$profile_to_mod_permission_values[$do_module->idmodule] = $evctl->$form_post_variable_mod_permission;
			foreach ($standard_permissions as $std_permission) {
				$form_post_variable_mod_standard_permission = 'm_'.$do_module->idmodule.'_'.$std_permission;
				$profile_to_mod_standard_permission_values[$do_module->idmodule][$std_permission] = $evctl->$form_post_variable_mod_standard_permission;
			}
		}
		// Adding to profile_module_rel 
		$do_profile_to_module_rel = new ProfileToModuleRelation();
		foreach ($profile_to_mod_permission_values as $idmodule=>$permissions) {
			// add profile to module permissions
			if ($permissions == 'on') { $permission = 1; } else { $permission = 0; }
			$do_profile_to_module_rel->addNew();
			$do_profile_to_module_rel->idprofile = $idprofile ;
			$do_profile_to_module_rel->idmodule = $idmodule;
			$do_profile_to_module_rel->permission_flag = $permission;
			$do_profile_to_module_rel->add();  	
		}
		// Adding to profile_standard_permission_rel
		$do_profile_standard_permission_rel = new ProfileToStandardPermissionRelation();
		foreach ($profile_to_mod_standard_permission_values as $idmodule=>$permissions) {
			foreach ($permissions as $std_permission=>$permission) {
				if ($permission == 'on') { $permission = 1;} else { $permission = 0; }
				$do_profile_standard_permission_rel->addNew();
				$do_profile_standard_permission_rel->idmodule = $idmodule;
				$do_profile_standard_permission_rel->idprofile = $idprofile;
				$do_profile_standard_permission_rel->idstandard_permission = $std_permission;
				$do_profile_standard_permission_rel->permission_flag = $permission;
				$do_profile_standard_permission_rel->add();
			}
		}
		// And finally add the global permissions
		$do_profile_global_permission_rel = new ProfileToGlobalPermissionRelation();
		$do_profile_global_permission_rel->addNew();
		$do_profile_global_permission_rel->idprofile = $idprofile;
		$do_profile_global_permission_rel->idglobal_permission = 1;
		$do_profile_global_permission_rel->permission_flag = $global_view_all;
		$do_profile_global_permission_rel->add();
		
		$do_profile_global_permission_rel->addNew();
		$do_profile_global_permission_rel->idprofile = $idprofile ;
		$do_profile_global_permission_rel->idglobal_permission = 2;
		$do_profile_global_permission_rel->permission_flag = $global_addedit_all;
		$do_profile_global_permission_rel->add();
		// Setting the session values to NULL
		$_SESSION["profilename"] = '';
		$_SESSION["description"] = '';
		
		$next_page  = NavigationControl::getNavigationLink("Settings","profile_details");
		$dis = new Display($next_page);
		$dis->addParam("sqrecord",$idprofile);
		$evctl->setDisplayNext($dis) ;
	}

	/**
	* Update the profile permission information
	* @param object $evctl
	*/
	public function eventUpdateProfile(EventControler $evctl) {
		$idprofile  = $evctl->idprofile ; 
		if ($idprofile != '') {
			if ($evctl->global_view_all == 'on') { $global_view_all = 1; } else { $global_view_all = 0; }
			if ($evctl->global_addedit_all == 'on') { $global_addedit_all = 1; } else { $global_addedit_all = 0; }
			$do_module = new Module();
			$do_mod_standard_permission = new ModuleStandardPermission();
			$do_mod_standard_permission->getAll();
			// 1=add/edit, 2 = view, 3 = delete ,this is hard coded
			$standard_permissions = array(1,2,3);
			// Array to keep all the profile to module permissions
			$profile_to_mod_permission_values = array();
			// Array to keep all the profile to module standard permissions
			$profile_to_mod_standard_permission_values = array() ;
			$do_module->get_all_active_module();
			while ($do_module->next()) {
				$form_post_variable_mod_permission = 'mod_'.$do_module->idmodule;
				$profile_to_mod_permission_values[$do_module->idmodule] = $evctl->$form_post_variable_mod_permission;
				foreach ($standard_permissions as $std_permission) {
					$form_post_variable_mod_standard_permission = 'm_'.$do_module->idmodule.'_'.$std_permission;
					$profile_to_mod_standard_permission_values[$do_module->idmodule][$std_permission] = $evctl->$form_post_variable_mod_standard_permission;
				}
			}
			$do_profile_to_module_rel = new ProfileToModuleRelation();
			foreach ($profile_to_mod_permission_values as $idmodule=>$permissions) {
				// update profile to module permissions
				if ($permissions == 'on') { $permission = 1; } else { $permission = 0; }
				$qry = "
				update profile_module_rel 
				set permission_flag = ?
				where
				idprofile = ? AND idmodule = ? limit 1";
				$do_profile_to_module_rel->query($qry,array($permission,$idprofile,$idmodule));
			}
			// updating to profile_standard_permission_rel
			$do_profile_standard_permission_rel = new ProfileToStandardPermissionRelation();
			foreach ($profile_to_mod_standard_permission_values as $idmodule=>$permissions) {
				foreach ($permissions as $std_permission=>$permission) {
					if ($permission == 'on') { $permission = 1; } else { $permission = 0; }
					$qry = "
					update profile_standard_permission_rel 
					set permission_flag = ?
					where
					idmodule = ?
					AND idprofile = ?
					AND idstandard_permission = ? LIMIT 1";
					$do_profile_standard_permission_rel->query($qry,array($permission,$idmodule,$idprofile,$std_permission));
				}
			}
			// And finally update the global permissions
			$do_profile_global_permission_rel = new ProfileToGlobalPermissionRelation();
			$qry = "
			update profile_global_permission_rel 
			set permission_flag = ?
			where 
			idprofile = ?
			AND idglobal_permission = 1 LIMIT 1" ;
			$do_profile_global_permission_rel->query($qry,array($global_view_all,$idprofile));
			
			$qry = "
			update profile_global_permission_rel 
			set permission_flag = ?
			where 
			idprofile = ?
			AND idglobal_permission = 2 LIMIT 1";
			$do_profile_global_permission_rel->query($qry,array($global_addedit_all,$idprofile));
			$next_page  = NavigationControl::getNavigationLink("Settings","profile_details");
			$dis = new Display($next_page);
			$dis->addParam("sqrecord",$idprofile);
			$evctl->setDisplayNext($dis) ;
		}
	}
	
	/**
	* Event method to update the profile name and description
	* @param object $evctl
	*/
	public function eventRenameProfile(EventControler $evctl) { 
		if ($evctl->id!='' ) { 
			$this->cleanValues();
			$this->profilename = CommonUtils::purify_input($evctl->profilename);
			$this->description = CommonUtils::purify_input($evctl->description);
			$this->update((int)$evctl->id);
		}
	}
    
	/**
	* Event method to delete a profile
	* @param object $evctl
	* @see popups/delete_profile_modal.php
	*/
	public function eventDeleteRecord(EventControler $evctl) {
		$do_delete = false ;
		$idprofile = (int)$evctl->id;
		if ($idprofile > 0) {
			if ($idprofile == 1) {
				$msg = _('You are trying to delete a profile which is not allowed');
			} else {
				$this->getId($idprofile);
				if ($this->getNumRows() > 0) {
					if ($evctl->profile_transfer == 'yes') {
						if ((int)$evctl->idprofile_transfer == 0) {
							$msg = _('No profile selected for transfer roles!');
						} else { $do_delete = true; }
					} else { $do_delete = true; }
				} else {
					$msg = _('The profile you are trying to delete does not exist!');
				}
			}
		} else { 
			$msg = _('Missing profile id to perform delete operation!');
		}
		if ($do_delete === false) {
			$_SESSION["do_crm_messages"]->set_message('error',$msg);
			$dis = new Display($evctl->next_page);
			$evctl->setDisplayNext($dis) ;
		} else {
			$this->query("delete from `profile` where `idprofile` = ?",array($idprofile));
			$this->query("delete from `profile_global_permission_rel` where `idprofile` = ?",array($idprofile));
			$this->query("delete from `profile_module_rel` where `idprofile` = ?",array($idprofile));
			$this->query("delete from `profile_standard_permission_rel` where `idprofile` = ?",array($idprofile));
			if ($evctl->profile_transfer == 'yes') {
				$idprofile_transfer = (int)$evctl->idprofile_transfer ;
				$this->query("update `role_profile_rel` set `idprofile` = ? where `idprofile` = ?",
								array($idprofile_transfer,$idprofile)
							);
			}
			$_SESSION["do_crm_messages"]->set_message('success',_('Profile has been deleted successfully ! '));
			$dis = new Display($evctl->next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}

	/**
	* Get all the module permission for a profile
	* @param integer $idprofile
	* @return array containing the permission list
	*/
	public function get_all_module_permissions($idprofile ="") {
		$permission_array = array();
		if($idprofile == "") $idprofile = $this->idprofile ; 
		$module_permissions = new ProfileToModuleRelation();
		$module_permissions->get_profile_module_rel($idprofile);
		while ($module_permissions->next()) {
			$permission_array[$module_permissions->idmodule] = $module_permissions->permission_flag ;
		}
		return $permission_array;
	}
    
	/**
	* Get all the global permission
	* @param integer $idprofile
	* @return array containing the permission
	*/
	public function get_profile_global_permissions($idprofile = "") {
		if ($idprofile != "") $this->getId($idprofile);
		else $idprofile = $this->idprofile ; 
		$permission_array = array();
		$global_permissions = new ProfileToGlobalPermissionRelation();
		$global_permissions->get_global_permissions_by_profile($idprofile);
		while ($global_permissions->next()) {
			$permission_array[$global_permissions->idglobal_permission] = $global_permissions->permission_flag;
		}
		return $permission_array ;
	}

	/**
	* Get all the module standard permission for a profile
	* @param integer $idprofile
	* @return array containing the permission
	*/
	public function get_all_module_standard_permissions($idprofile = "") {
		$standard_permissions_array = array();
		if ($idprofile == "") $idprofile = $this->idprofile ;
		$standard_permissions = new ProfileToStandardPermissionRelation() ;
		$standard_permissions->get_profile_standard_permissions($idprofile);
		while ($standard_permissions->next()) {
			$standard_permissions_array[$standard_permissions->idmodule][$standard_permissions->idstandard_permission] = $standard_permissions->permission_flag;
		}
		return $standard_permissions_array ; 
	}
    
	/**
	* function to get all the roles which has the profile attached 
	* @param integer $idprofile
	* @return array if record found else false
	*/
	public function get_roles_attached_to_profile($idprofile) {
		$qry = "
		select `role`.`rolename`, `role`.`idrole` 
		from `role`
		inner join `role_profile_rel` on `role_profile_rel`.`idrole` = `role`.`idrole`
		where `role_profile_rel`.`idprofile` = ?";
		$this->query($qry,array($idprofile));
		if ($this->getNumRows() > 0) {
			$return_data = array();
			while ($this->next()) {
				$data = array("idrole"=>$this->idrole,"rolename"=>$this->rolename);
				$return_data[] = $data ;
			}
			return $return_data ;
		} else { return false ; }
	}

}