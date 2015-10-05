<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Class Group
* Maintain the Group information of crm
* @author Abhik Chakraborty
*/


class Group extends DataObject {
	public $table = "group";
	public $primary_key = "idgroup";


	/**
	* Public function to get all groups
	*/
	public function get_all_groups() {
		$this->query("select * from `".$this->getTable()."` order by group_name");
	}

	/**
	* event method to process the adding of a new group and the user related to the group
	* @param object $evctl
	* @see modules/Settings/group_add.php
	*/
	public function eventAddNewGroup(EventControler $evctl) {
		if ($evctl->group_name!= '') {
			if (is_array($evctl->select_to) && count($evctl->select_to) > 0) {
				$this->insert(
					"`".$this->getTable()."`",
					array(
						"group_name"=>CommonUtils::purify_input($evctl->group_name),
						"description"=>CommonUtils::purify_input($evctl->description)
					)
				);
				$idgroup = $this->getInsertId() ;
				foreach ($evctl->select_to as $iduser) {
					$group_user_rel = new GroupUserRelation();
					$group_user_rel->addNew();
					$group_user_rel->idgroup = $idgroup ;
					$group_user_rel->iduser = $iduser;
					$group_user_rel->add();
					$group_user_rel->free();
				}
				$_SESSION["do_crm_messages"]->set_message('success',_('New group has been added successfully ! '));
				$dis = new Display($evctl->next_page);
				$dis->addParam("sqrecord",$idgroup);
				$evctl->setDisplayNext($dis) ;
			} else {
				$_SESSION["do_crm_messages"]->set_message('error',_('Unable to add the group since no group member is assigned ! '));
				$dis = new Display($evctl->error_page);
				$evctl->setDisplayNext($dis) ;
			}
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('Unable to add the group since no group name is specified ! '));
			$dis = new Display($evctl->error_page);
			$evctl->setDisplayNext($dis) ;
		}
	}

	/**   
	* Event function to update the group
	* Also updates the related member information of the group
	* @param object $evctl
	* @see modules/Settings/group_edit.php
	*/
	public function eventEditGroup(EventControler $evctl) {
		if ($evctl->idgroup != '' && $evctl->group_name) {
			$qry = "
			UPDATE `".$this->getTable()."` 
			set `group_name` = ?,
			`description` = ?
			where `idgroup` = ? LIMIT 1" ;
			$this->query(
				$qry,
				array(
					CommonUtils::purify_input($evctl->group_name),
					CommonUtils::purify_input($evctl->description),
					$evctl->idgroup
				)
			);
			if (is_array($evctl->select_to) && count($evctl->select_to) > 0) {
				$do_group_user_rel = new GroupUserRelation();
				$do_group_user_rel->update_group_related_to_user($evctl->select_to,$evctl->idgroup);
			}
			$_SESSION["do_crm_messages"]->set_message('success',_('Group has been updated successfully !'));
			$dis = new Display($evctl->next_page);
			$dis->addParam("sqrecord",$evctl->idgroup);
			$evctl->setDisplayNext($dis) ;
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('Unable to update the group, either group name or id is missing !'));
			$dis = new Display($evctl->error_page);
			$evctl->setDisplayNext($dis) ;
		}
	}
    
	/**
	* function to delete a group
	* while deleting a group the related data will be transferred to user or group which is selected
	* which is selected during the delete process
	* @param object $evctl
	* @see self :: transfer_group_data_to_user()
	* @see self :: transfer_group_data_to_group()
	* @see popups/delete_group_modal.php
	*/
	public function eventDeleteRecord(EventControler $evctl) {
		$id = (int)$evctl->id;
		$group_transfer_opt = false ;
		$transfer_to_user = false ;
		$transfer_to_group = false ;
		
		if ($evctl->group_transfer_opt == 'yes') { 
			$group_transfer_opt = true ;
		}
		if ($group_transfer_opt === true) { 
			if ($evctl->assigned_to_selector == 'user') {
				$transfer_to_user = true ;
			} elseif ($evctl->assigned_to_selector == 'group') {
				$transfer_to_group = true ;
			}
		} else { $transfer_to_user = true ; }
		if(( $transfer_to_user === true || $transfer_to_group === true ) && $id > 0) {
			$do_module = new Module();
			$do_module->getAll();
			while ($do_module->next()) {
				if ($do_module->idmodule == 1 || $do_module->idmodule ==7 
				|| $do_module->idmodule ==8 || $do_module->idmodule == 9) continue ;
				$module_name = $do_module->name ;
				$object = new $module_name();
				if ($transfer_to_user === true) {
					// transfer group data to selected user
					$idtransfer = (int)$evctl->user_selector ;
					$this->transfer_group_data_to_user($object,$id,$idtransfer);
				} elseif ($transfer_to_group === true) {
					// transfer group data to selected group
					$idtransfer = (int)$evctl->group_selector ;
					$this->transfer_group_data_to_group($object,$id,$idtransfer);
				}
			}
			$this->query("delete from `group` where `idgroup` = ?",array($id));
			$_SESSION["do_crm_messages"]->set_message('success',_('Group has been deleted successfully and related data has been transferred !'));
		} else {
			$_SESSION["do_crm_messages"]->set_message('error',_('Unable to delete the group,id is missing !'));
		}
	}
    
	/**
	* function to transfer the group data to an user while deleting the group
	* @param object $object
	* @param integer $idgroup
	* @param integer $idtransfer
	* @see self :: eventDeleteRecord()
	*/
	public function transfer_group_data_to_user($object,$idgroup,$idtransfer) {
		$qry = "select * from `".$object->module_group_rel_table."` where `idgroup` = ?";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idgroup));
		if ($stmt->rowCount() > 0) {
			$pk = $object->primary_key ;
			while ($data=$stmt->fetch()) {
				$upd = "
				update `".$object->getTable()."` 
				set `iduser` = ?
				where `".$object->primary_key."` = ?";
				$this->getDbConnection()->executeQuery($upd,array($idtransfer,$data[$pk]));
			}
		}
		$this->query("delete from `".$object->module_group_rel_table."` where `idgroup` = ?",array($idgroup));
	}
    
	/**
	* function to transfer the group data to a group while deleting the group
	* @param object $object
	* @param integer $idgroup
	* @param integer $idtransfer
	* @see self :: eventDeleteRecord()
	*/
	public function transfer_group_data_to_group($object,$idgroup,$idtransfer) {
		$qry = "
		update `".$object->module_group_rel_table."` 
		set `idgroup` = ? 
		where `idgroup` = ? ";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idtransfer,$idgroup));
	}
}