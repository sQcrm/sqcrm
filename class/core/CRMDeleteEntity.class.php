<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMDeleteEntity 
* Mantain the delete operation from different entity
* @author Abhik Chakraborty
*/
	

class CRMDeleteEntity extends DataObject {
	public $table = "";
	public $primary_key = "";
    
	/**
	* ajax function to delete multiple records from list view and related list view data
	* @param object $evctl
	* @return 1 or 0
	* @see view/listview.php
	* @see view/related_listview_entry.php
	*/
	function eventAjaxDeleteMultipleEntity(EventControler $evctl) { 
		$referrer = $evctl->referrer;
		$record_ids = $evctl->chk ;
		if ($referrer == 'list') {
			$module = $evctl->module;
		} elseif ($referrer == 'related') {
			$idrelated_information = (int)$evctl->related_record_id ;
			$do_related_information = new CRMRelatedInformation();
			$do_related_information->getId($idrelated_information);
			if ($do_related_information->getNumRows() > 0) {
				$module = $do_related_information->related_module ;
			}
		}
		if($module != ''){
			$module_id = $_SESSION["do_module"]->get_idmodule_by_name($module,$_SESSION["do_module"]);        
			$allow_delete = false ;
			if (is_array($record_ids) && count($record_ids) > 0) {
				foreach ($record_ids as $id) {
					$allow_del = $_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id,$id);
					if ($allow_del === false ) break;
				}
			}
			if ($allow_del === true) {
				$do_data_history = new DataHistory();
				$crm_entity = new CRMEntity();
				$do_feed_queue = new LiveFeedQueue();
				$do_process_plugins = new CRMPluginProcessor() ;
				//delete code for the record goes here
				foreach ($record_ids as $id) {
					// process before delete plugin
					$do_process_plugins->process_action_plugins($module_id,null,5,$id) ;
					if (strlen($do_process_plugins->get_error()) > 2) {
						echo $do_process_plugins->get_error() ;
						break;
					} else {
						$this->delete_record($id,$module) ;
						// Record the history
						$do_data_history->add_history($id,$module_id,'delete');
						// Add to feed
						$feed_other_assigne = array() ;
						$entity_assigned_to = $crm_entity->get_assigned_to_id($id,$module);
						if (is_array($entity_assigned_to) && sizeof($entity_assigned_to) > 0) {
							if (array_key_exists("idgroup",$entity_assigned_to) && $entity_assigned_to["idgroup"]) {
								$feed_other_assigne = array("related"=>"group","data" => array("key"=>"oldgroup","val"=>(int)$entity_assigned_to["idgroup"]));
							}
						}
						$record_identity = $crm_entity->get_entity_identifier($id,$module);
						$do_feed_queue->add_feed_queue($id,$module_id,$record_identity,'delete',$feed_other_assigne);
						// process after delete plugin
						$do_process_plugins->process_action_plugins($module_id,null,5,$id) ;
					}
					echo '1';
				}
			} else {
				echo '0';
			}
		} else { 
			echo '0';
		}
	}
    
	/**
	* function to delete single record from list view and related list view
	* @param object $evctl
	* @return 0 or 1
	* @see view/listview.php
	* @see view/related_listview_entry.php
	*/
	function eventAjaxDeleteSingleEntity(EventControler $evctl) {
		$referrer = $evctl->referrer;
		if ($referrer == 'list') {
			$module = $evctl->module;
		} elseif ($referrer == 'related') {
			$idrelated_information = (int)$evctl->related_record_id ;
			$do_related_information = new CRMRelatedInformation();
			$do_related_information->getId($idrelated_information);
			if ($do_related_information->getNumRows() > 0 ) {
				$module = $do_related_information->related_module ;
			}
		}
		if ($module != '') {
			$module_id = $_SESSION["do_module"]->get_idmodule_by_name($module,$_SESSION["do_module"]);
			$id = (int)$evctl->sqrecord ;
			$allow_del = $_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id,$id);
			if ($allow_del === true) {
				$do_data_history = new DataHistory();
				$crm_entity = new CRMEntity();
				$do_feed_queue = new LiveFeedQueue();
				$do_process_plugins = new CRMPluginProcessor() ;
				//delete code for the record goes here
				// process before delete plugin
				$do_process_plugins->process_action_plugins($module_id,null,5,$id) ;
				if (strlen($do_process_plugins->get_error()) > 2) {
					echo $do_process_plugins->get_error() ;
				} else {
					$this->delete_record($id,$module) ;
					$do_data_history->add_history($id,$module_id,'delete');
					// Add to feed
					$feed_other_assigne = array() ;
					$entity_assigned_to = $crm_entity->get_assigned_to_id($id,$module);
					if (is_array($entity_assigned_to) && sizeof($entity_assigned_to) > 0) {
						if (array_key_exists("idgroup",$entity_assigned_to) && $entity_assigned_to["idgroup"]) {
							$feed_other_assigne = array(
								"related"=>"group",
								"data" => array(
									"key"=>"oldgroup",
									"val"=>(int)$entity_assigned_to["idgroup"]
								)
							);
						}
					}
					$record_identity = $crm_entity->get_entity_identifier($id,$module);
					$do_feed_queue->add_feed_queue($id,$module_id,$record_identity,'delete',$feed_other_assigne);
					// process after delete plugin
					$do_process_plugins->process_action_plugins($module_id,null,5,$id) ;
					echo '1';
				}
			} else {
				echo '0';
			}
		} else {
			echo '0' ;
		}
	}
    
	/**
	* function to delete an entity from the CRM
	* @param integer $identity
	* @param string $module
	* sets the deleted = 1 for the record in the entity table for that module
	* TODO : delete the recurrent events when a calendar record is deleted. 
	* Needs feedback before implementing this feature
	*/
	public function delete_record($idrecord,$module) { 
		$module_obj = new $module();
		$q_upd = "
		update `".$module_obj->getTable()."` 
		set `deleted` = 1 
		where `".$module_obj->primary_key."` = ? limit 1 " ;
		$module_obj->query($q_upd,array($idrecord));
	}
}
