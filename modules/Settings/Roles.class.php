<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Class Roles
* Maintain the Roles and user hierarchy information of crm
* @author Abhik Chakraborty
*/


class Roles extends DataObject {
	public $table = "role";
	public $primary_key = "idrole";
    
	/**
	* function to generate the roles hierarchy tree
	* @param integer $depth
	* @param string $parentrole
	*/
	public function render_role_hierarchy($depth=0,$parentrole="N1") {
		global $module;
		$next_depth = $depth +1 ;
		$obj_name = $q.$depth;
		$current_parent_role = $parentrole ;
		$qry = "select * from ".$this->getTable()." where depth= ? AND parentrole like ?" ;
		$obj_name = $this->getDbConnection()->executeQuery($qry,array($depth,$parentrole.'%'));
		if ($obj_name->rowCount() > 0) {
			echo '<ul>'."\n";
			while ($data = $obj_name->fetch()) {
				echo '<li><input type="checkbox" id="'.$data["idrole"].'" checked="checked" />'."\n";
				echo '<label for="'.$data["idrole"].'" class="role_hierarchy">'."\n";
				echo $data["rolename"] ;
				if ($data["editable"] == 0)
					echo '<span style="display:none;" class="role_hierarchy_opt">
							<a href="'.NavigationControl::getNavigationLink($module,'roles_add','','?parentrole='.$data["idrole"]).'">
								<i class="icon-plus"></i>
							</a>
							</span>'."\n" ;
				else
					if ($data["idrole"] == "N2")
						echo '<span style="display:none;" class="role_hierarchy_opt">
								<a href="'.NavigationControl::getNavigationLink($module,'roles_add','','?parentrole='.$data["idrole"]).'">
									<i class="icon-plus"></i>
								</a>
								<a href="'.NavigationControl::getNavigationLink($module,'roles_edit','','?idrole='.$data["idrole"]).'">
									<i class="icon-pencil"></i>
								</a>
								</span>'."\n" ;
					else
						echo '<span style="display:none;" class="role_hierarchy_opt">
								<a href="'.NavigationControl::getNavigationLink($module,'roles_add','','?parentrole='.$data["idrole"]).'">
									<i class="icon-plus"></i>
								</a>
								<a href="'.NavigationControl::getNavigationLink($module,'roles_edit','','?idrole='.$data["idrole"]).'">
									<i class="icon-pencil"></i>
								</a>
								<a class="del_role" href="'.NavigationControl::getNavigationLink($module,'roles_delete','','?idrole='.$data["idrole"]).'">
									<i class="icon-remove-sign"></i>
								</a>
								</span>'."\n" ;
				echo '</label>'."\n";
				$this->render_role_hierarchy($next_depth,$data["parentrole"]);
				echo '</li>';   
			}
			echo '</ul>';
		} else {
			if ($this->get_max_depth() >= $next_depth)
				$this->render_role_hierarchy($next_depth,$current_parent_role);
		}
	}

	/**
	* function to generate the roles hierarchy tree for the popup roles selector
	* The function is specific to FieldType103
	* @param string $fieldname
	* @param integer $depth
	* @param string $parentrole
	*/
	public function render_role_hierarchy_popup_selection($fieldname = '',$depth=0,$parentrole="N1",$ignore='') {
		global $module;
		$ignore_role = $ignore ;
		$next_depth = $depth +1 ;
		$obj_name = $q.$depth;
		$current_parent_role = $parentrole ;
		$qry = "select * from ".$this->getTable()." where depth= ? AND parentrole like ?" ;
		$obj_name = $this->getDbConnection()->executeQuery($qry,array($depth,$parentrole.'%'));
		if ($obj_name->rowCount() > 0) {
			echo '<ul>'."\n";
			while ($data = $obj_name->fetch()) {
				echo '<li><input type="checkbox" id="'.$data["idrole"].'" checked="checked" />'."\n";
				echo '<label for="'.$data["idrole"].'" class="role_hierarchy">'."\n";
				if ($ignore_role == $data["idrole"]) {
					echo '<strong>'.$data["rolename"].'</strong>';
				} else {
					echo '<a href="#" data-dismiss="modal" onclick = "return_roles_selected_item(\''.$data["idrole"].'\',\''.$data["rolename"].'\',\''.$fieldname.'\')">'.$data["rolename"].'</a>';
				}
				echo '</label>'."\n";
				$this->render_role_hierarchy_popup_selection($fieldname,$next_depth,$data["parentrole"],$ignore_role);
				echo '</li>';   
			}
			echo '</ul>';
		} else {
			if ($this->get_max_depth() >= $next_depth)
			$this->render_role_hierarchy($fieldname,$next_depth,$current_parent_role,$ignore_role);
		}
	}
    
	/**
	* function to get the roles having depth 0
	* @return string idrole
	*/
	public function get_depth_zero_role() {
		$this->query("select idrole from `".$this->getTable()."` where `depth` = 0");
		$this->next();
		return $this->idrole ; 
	}

	/**
	* function to get the max depth in the role hierarchy
	* @return integer $max_depth
	* @see self::render_role_hierarchy_popup_selection
	* @see self::render_role_hierarchy
	*/
	public function get_max_depth() {
		$qry = "select max(depth) as `max_depth` from `role`";
		$stmt = $this->getDbConnection()->executeQuery($qry);
		$data = $stmt->fetch();
		$max_depth =  $data["max_depth"];
		return $max_depth ;
	}
    
	/**
	* event function to add a new role
	* @param object $evctl
	*/
	public function eventAddNewRole(EventControler $evctl) {
		if ($evctl->rolename != '' && $evctl->parentrole != '') {
			$role_detail = $this->get_role_detail($evctl->parentrole);
			if (is_array($role_detail) && count($role_detail) > 0) {
				$depth_lookup = $role_detail["depth"] + 1 ;
				$qry = "select max(idrole) as max_role from `role`";
				$stmt = $this->getDbConnection()->executeQuery($qry);
				$data = $stmt->fetch();
				$max_role = $data["max_role"];
				if ($max_role != '') {
					$role_int = str_replace("N","",$max_role);
					$new_role_int = $role_int+1;
					$new_role = "N".$new_role_int;
					$new_parent_role = $role_detail["parentrole"]."::".$new_role;
					$this->insert(
						$this->getTable(),
						array(
							"idrole"=>$new_role,
							"rolename"=>CommonUtils::purify_input($evctl->rolename),
							"parentrole"=>$new_parent_role,
							"depth"=>$depth_lookup,
							"editable"=>1
						)
					);
					// Adding role profile relation
					$profiles = $evctl->select_to ;
					foreach ($profiles as $idprofile) {
						$do_role_prof_rel = new RoleProfileRelation();
						$do_role_prof_rel->addNew();
						$do_role_prof_rel->idrole = $new_role;
						$do_role_prof_rel->idprofile = $idprofile;
						$do_role_prof_rel->add();
						$do_role_prof_rel->free();
					}
					$dis = new Display($evctl->next_page);
					$dis->addParam("sqrecord",$idprofile);
					$evctl->setDisplayNext($dis) ;
				}
			}
		}
	}

	/**
	* Event method to update a role and associated profiles
	* @param object $evctl
	*/
	public function eventEditRole(EventControler $evctl) {
		if ($evctl->idrole != '' && $evctl->rolename != '') {
			$qry = "
			update ".$this->getTable()." 
			set rolename = ? 
			where idrole = ?
			limit 1";
			$this->getDbConnection()->executeQuery($qry,array($evctl->rolename,$evctl->idrole));
			if (is_array($evctl->select_to) && count($evctl->select_to) > 0) {
				$do_role_prof_rel = new RoleProfileRelation();
				$do_role_prof_rel->update_profile_related_to_role($evctl->select_to,$evctl->idrole);
			}
		}
	}
    
	/**
	* Public function to get role details
	* @param string $idrole
	* @returns array role_detail
	*/
	public function get_role_detail($idrole) {
		$this->query("select * from ".$this->getTable()." where idrole = ?",array($idrole));
		if ($this->getNumRows() > 0) {
			$this->next();
			$roles_detail = array(
				"rolename"=>$this->rolename,"idrole"=>$this->idrole,
				"parentrole"=>$this->parentrole,"depth"=>$this->depth
			);
		}
		return $roles_detail;
	}
    
	/**
	* function getting users by idrole
	* @param string $idrole
	* @return array if users found else return false
	*/
	public function get_users_with_idrole($idrole) {
		$qry = "select * from `user` where `idrole` = ? and `user`.`deleted` = 0 ";
		$this->query($qry,array($idrole));
		$return_data = array();
		if ($this->getNumRows() > 0) {
			while ($this->next()) {
				$data = array("iduser"=>$this->iduser,"user_name"=>$this->user_name,"full_name"=>$this->firstname.' '.$this->lastname);
				$return_data[] = $data ;
			}
			return $return_data;
		} else {
			return false ;
		}
	}
    
	/**
	* event function to delete the role
	* before deleting it will set the idrole of users to a new selected role which were earlier attached with the role to be deleted
	* @param object $evctl
	* @see popups/role_delete.php
	*/
	public function eventDeleteRole(EventControler $evctl) {
		$do_delete = false ;
		$msg = '';
		if ($evctl->idrole != '') {
			if ($evctl->idrole == 'N1' || $evctl->idrole == 'N2') {
				$msg = _('The role you are trying to delete is not allowd !');
			} else {
				$role_detail = $this->get_role_detail($evctl->idrole);
				if (count($role_detail) > 0) {
					if ($evctl->role_transfer == 'yes') {
						if ($evctl->idrole_transfer == '') {
							$msg = _('No role selected to re-assign users !');
						} else { $do_delete = true ; }
					} else { $do_delete = true ; }
				} else {
					$msg = _('The role you are trying to delete does not exist !');
				}
			}
		} else {
			$msg = _('Invalid roleid to perform delete operation !');
		}
		if ($do_delete === false) {
			$_SESSION["do_crm_messages"]->set_message('error',$msg);
			$dis = new Display($evctl->next_page);
			$evctl->setDisplayNext($dis) ;
		} else {
			$qry = "select * from `role` where `parentrole` like ? AND `idrole` <> ?";
			$this->query($qry,array($role_detail["parentrole"].'%',$role_detail["idrole"]));
			if ($this->getNumRows() > 0) {
				while ($this->next()) {
					$depth = $this->depth ;
					$depth = $depth-1;
					$qry1 = "update `role` set `depth` = ? where `idrole` = ? ";
					$this->getDbConnection()->executeQuery($qry1,array($depth,$this->idrole));
				}
			}
			$this->query("delete from `role` where `idrole` = ?",array($role_detail["idrole"]));
			$this->query("delete from `role_profile_rel` where `idrole` = ?",array($role_detail["idrole"]));
			if ($evctl->idrole_transfer != '') {
				$q_upd = "
				update `user` 
				set `idrole` = ?
				where `idrole` = ?
				" ;
				$this->query($q_upd,array($evctl->idrole_transfer,$role_detail["idrole"]));
			}
			$_SESSION["do_crm_messages"]->set_message('success',_('Role has been deleted successfully ! '));
			$dis = new Display($evctl->next_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
}