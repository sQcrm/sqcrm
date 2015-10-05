<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMEntity 
* @author Abhik Chakraborty
*/
	

class CRMEntity extends DataObject {
	public $table = "";
	public $primary_key = "";

	/**
	* function to get the entity identity like combination of firstname and lastname etc 
	* @param integer $idrecord
	* @param string $module
	* @param object $object
	* @return string $retval
	*/
	public function get_entity_identifier($idrecord,$module,$object=NULL) {
		$retval = '';
		if (is_object($object) && $object->getNumRows() > 0) {
			$return_fields = $object->popup_selection_return_field ;
		} else {
			$object = new $module();
			$return_fields = $object->popup_selection_return_field ;
			$qry = "
			select $return_fields
			from `".$object->getTable()."` 
			where `".$object->primary_key."`= ?" ;
			$object->query($qry,array($idrecord));
			$object->next(); 
		}  
		$retrun_field_list = explode(",",$return_fields); 
		$cnt_return_fields = 0 ;
		foreach ($retrun_field_list as $retrun_fields) {
			if($cnt_return_fields > 0 ) $retval .= ' ';
			$retval .= $object->$retrun_fields;
			$cnt_return_fields++;
		}
		return $retval;
	}
  
	/**
	* function to get assigned_to id for a record
	* @param integer $idrecord
	* @param string $module
	* @param object $object
	* @return array
	*/
	public function get_assigned_to_id($idrecord,$module,$object=NULL) {
		$return_data = array();
		if (is_object($object) && $object->getNumRows() > 0) {
			if ((int)$object->iduser > 0) {
				$return_data["iduser"] = (int)$object->iduser ;
			} elseif ((int)$object->idgroup > 0) {
				$return_data["idgroup"] = (int)$object->idgroup ;
			}
		} else {
			$object = new $module();
			$object->query("select `iduser` from `".$object->getTable()."` where `".$object->primary_key."` = ?",array($idrecord));
			if ($object->getNumRows() > 0) {
				if ($object->iduser > 0) {
					$return_data["iduser"] = (int)$object->iduser ;
				} else {
					if ($object->module_group_rel_table != '') {
						$object->query("select `idgroup` from `".$object->module_group_rel_table."` 
								where `".$object->primary_key."` = ?",array($idrecord));
						if ($object->getNumRows() > 0) {
							if ($object->idgroup > 0) $return_data["idgroup"] = (int)$object->idgroup ;
						}
					}
				}
			}
		}
		return $return_data ;
	}
  
	/**
	* function to get the last update of a record
	* @param integer $idrecord
	* @param integer $idmodule
	* @param object $module_object
	* @param array
	*/
	public function get_last_updates($idrecord,$idmodule,$module_object) {
		$updates = array();
		if ($module_object->last_modified_by > 0) {
			$qry = "
			select user.firstname, user.lastname , file_uploads.file_extension,user.user_avatar 
			from user
			left join file_uploads on user.user_avatar = file_uploads.file_name 
			where user.iduser = ?" ;
			$stmt = $this->getDbConnection()->executeQuery($qry,array($module_object->last_modified_by)) ;
			if ($stmt->rowCount() > 0) {
				$data = $stmt->fetch();
				$avatar = '';
				if ($data["user_avatar"] != '') {
					$avatar_path = $GLOBALS['AVATAR_DISPLAY_PATH'] ;
					$avatar = $avatar_path.'/ths_'.$data["user_avatar"].'.'.$data["file_extension"] ;
				}
				$updates["update"] = array(
					"modified"=>i18nDate::i18n_long_date(TimeZoneUtil::convert_to_user_timezone($module_object->last_modified,true),true),
					"user_name"=>$data["firstname"].' '.$data["lastname"],
					"user_avatar"=>$avatar
				);
			}
		}
		$qry = "
		select `data_history`.*, user.firstname, user.lastname , file_uploads.file_extension,user.user_avatar 
		from `data_history`
		inner join `user` on `user`.`iduser` = `data_history`.`iduser`
		left join file_uploads on user.user_avatar = file_uploads.file_name 
		where `data_history`.`idmodule` = ?
		AND `data_history`.`id_referrer` = ?
		order by `data_history`.`iddata_history` asc limit 1 ";
		$stmt = $this->getDbConnection()->executeQuery($qry,array($idmodule,$idrecord)) ;
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			$avatar = '';
			if ($data["user_avatar"] != '') {
				$avatar_path = $GLOBALS['AVATAR_DISPLAY_PATH'] ;
				$avatar = $avatar_path.'/ths_'.$data["user_avatar"].'.'.$data["file_extension"] ;
			}
			$updates["add"] = array(
				"modified"=>i18nDate::i18n_long_date(TimeZoneUtil::convert_to_user_timezone($data["date_modified"],true),true),
				"user_name"=>$data["firstname"].' '.$data["lastname"],
				"user_avatar"=>$avatar
			);
		}
		return $updates ;
	}

	/**
	* event function to change the assigned to for entity for a module
	* The method will check the assigned to value, if its group or user
	* and then accordingly will change the assigned to
	* @param object $evctl
	*/
	function eventChangeAssignedToEntity(EventControler $evctl) {
		$next_page = $evctl->next_page;
		$record_ids = $evctl->ids ;
		$group_transfer_opt = false ;
		$transfer_to_user = false ;
		$transfer_to_group = false ;
		$module_name = $evctl->module ;
		$module_id = $evctl->module_id ;
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
		if (($transfer_to_user === true || $transfer_to_group === true ) && sizeof($record_ids) > 0) {
			$do_data_history  = new DataHistory();
			$do_feed_queue = new LiveFeedQueue();
			$module = new $module_name();
			if ($transfer_to_user === true) {
				$do_user = new User();
				$do_user->getId((int)$evctl->user_selector);
				$new_assigned_to = $do_user->user_name ;
				foreach ($record_ids as $id) {
					$feed_other_assigne = array();
					$module->getId($id);
					$old_assigned_to = $module->assigned_to ;
					if ($module->idgroup > 0) {
						$feed_other_assigne = array("related"=>"group","data" => array("key"=>"oldgroup","val"=>$module->idgroup)); 
					}
					$record_identifier = $this->get_entity_identifier('','',$module);
					// query to change the user for the record
					$qry = "
					update `".$module->getTable()."` 
					set `iduser` = ?
					where `".$module->primary_key."` = ?";
					$this->query($qry,array($evctl->user_selector,$id));
					//qry to delete from the group rel if data exists
					$qry = "
					delete from `".$module->module_group_rel_table."` 
					where 
					`".$module->primary_key."` = ?";
					$this->query($qry,array($id));
					// add to data history
					$do_data_history->addNew();
					$do_data_history->id_referrer = $id ;
					$do_data_history->iduser = $_SESSION["do_user"]->iduser ;
					$do_data_history->idmodule = $module_id ;
					$do_data_history->date_modified = date("Y-m-d H:i:s");
					$do_data_history->action = 'value_changes';
					$do_data_history->idfields = (int)$evctl->fieldid ;
					$do_data_history->old_value = $old_assigned_to;
					$do_data_history->new_value = $new_assigned_to ;
					$do_data_history->add();
					// add to feed
					$do_feed_queue->add_feed_queue($id,$module_id,$record_identifier,'changed_assigned_to',$feed_other_assigne);
				}
			} elseif ($transfer_to_group === true){
				$do_group = new Group();
				$do_group->getId((int)$evctl->group_selector);
				$new_assigned_to = $do_group->group_name ;
				foreach ($record_ids as $id) {
					$module->getId($id);
					$old_assigned_to = $module->assigned_to ;
					if ($module->idgroup > 0) { $feed_other_assigne = array("related"=>"group","data" => array("key"=>"oldgroup","val"=>$module->idgroup)); }
					$feed_other_assigne = array("related"=>"group","data" => array("key"=>"newgroup","val"=>(int)$evctl->group_selector));
					$record_identifier = $this->get_entity_identifier('','',$module);
					// query to change the iduser to 0 for the record
					$qry = "
					update `".$module->getTable()."` 
					set `iduser` = 0 
					where `".$module->primary_key."` = ?";
					$this->query($qry,array($id));
					// now check if the record is already assigned to a different group then update else add a new entry
					$qry_check = "
					select * from `".$module->module_group_rel_table."` 
					where 
					`".$module->primary_key."` = ?";
					$this->query($qry_check,array($id));
					if ($this->getNumRows() > 0){
						$qry = "
						update `".$module->module_group_rel_table."` 
						set `idgroup` = ?
						where `".$module->primary_key."` = ?";
						$this->query($qry,array($evctl->group_selector,$id));
					} else {
						$this->insert($module->module_group_rel_table,array($module->primary_key=>$id,'idgroup'=>$evctl->group_selector));
					}
			
					// add to data history
					$do_data_history->addNew();
					$do_data_history->id_referrer = $id ;
					$do_data_history->iduser = $_SESSION["do_user"]->iduser ;
					$do_data_history->idmodule = $module_id ;
					$do_data_history->date_modified = date("Y-m-d H:i:s");
					$do_data_history->action = 'value_changes';
					$do_data_history->idfields = (int)$evctl->fieldid ;
					$do_data_history->old_value = $old_assigned_to;
					$do_data_history->new_value = $new_assigned_to ;
					$do_data_history->add();
					// add to feed
					$do_feed_queue->add_feed_queue($id,$module_id,$record_identifier,'changed_assigned_to',$feed_other_assigne);
				}
			}
			$_SESSION["do_crm_messages"]->set_message('success',_('Data updated successfully !'));
		}
	}
  
}