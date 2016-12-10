<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class DataHistory
* @author Abhik Chakraborty
*/
	

class DataHistory extends DataObject {
	public $table = "data_history";
	public $primary_key = "iddata_history";
	
	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();
	
	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;
    
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
	* function to add data history
	* @param integer $id_referrer
	* @param integer $idmodule
	* @param string $action
	* @param object $obj
	* @param object $evctl
	* TODO : The table will become huge for large number of data so its wise to maintain this with some nosql db like mongodb
	*/
	public function add_history($id_referrer,$idmodule,$action,$obj=NULL,$evctl = NULL) {
		//if($GLOBALS['NOSQL_DB'] === true ) return ;
		if ($action == 'value_changes' && is_object($obj)) {
			$this->add_history_value_changes($id_referrer,$idmodule,$obj,$evctl);
		} else {
			$this->addNew();
			$this->id_referrer = (int)$id_referrer ;
			$this->iduser = $_SESSION["do_user"]->iduser ;
			$this->idmodule = $idmodule ;
			$this->date_modified = date("Y-m-d H:i:s");
			$this->action = $action ;
			$this->add();
		}
	}
    
	/**
	* function to add the history of field value changes
	* @param integer $id_referrer
	* @param integer $idmodule
	* @param object $obj
	* @param object $evctl
	* TODO : The table will become huge for large number of data so its wise to maintain this with some nosql db like mongodb
	*/
	public function add_history_value_changes($id_referrer,$idmodule,$obj,$evctl=NULL) {
		//if($GLOBALS['NOSQL_DB'] === true ) return ;
		if (is_object($obj)) { 
			$DataHistoryFieldOption = new DataHistoryFieldOption();
			$fields_for_history = $DataHistoryFieldOption->get_data_history_fields($idmodule);
			if (is_array($fields_for_history) && count($fields_for_history) > 0 ) {
				foreach($fields_for_history as $idfields){
					$do_crm_fields = new CRMFields();
					$do_crm_fields->getId($idfields);
					$field_name = $do_crm_fields->field_name ;
					$field_type = $do_crm_fields->field_type;
					$record_history = false ;
					/*check if the field value has changed, i.e. old value on $obj->$field_name is not equal to $evctl->$field_name then add 
					to history,
					*/
					if ($field_type == 15) {
						$assigned_to_changed = false ;
						$assigned_to = $evctl->assigned_to_selector;
						if ($assigned_to == 'user') {
							$user_selector = $evctl->user_selector ;
							if ($obj->iduser > 0) {
								if ($obj->iduser != $user_selector) {
									$assigned_to_changed = true ;
								} 
							} elseif($obj->idgroup > 0 ) {
								$assigned_to_changed = true ;
							}
							if ($assigned_to_changed === true) {
								$old_value = $obj->assigned_to ;
								$do_user = new User();
								$do_user->getId($user_selector);
								$new_value = $do_user->user_name ;
								$record_history = true ;
							}
						} else {
							$group_selector = $evctl->group_selector ;
							if ($obj->idgroup > 0 ) {
								if ($obj->idgroup != $group_selector) {
									$assigned_to_changed = true ;
								}
							} elseif($obj->iduser > 0) {
								$assigned_to_changed = true ;
							}
							if ($assigned_to_changed === true) {
								$old_value = $obj->assigned_to ;
								$do_group = new Group();
								$do_group->getId($group_selector);
								$new_value = $do_group->group_name ;
								$record_history = true ;
							}
						}
					} elseif($field_type == 30) {
						$old_value = $obj->$field_name;
						$new_value = FieldType30::convert_before_save($evctl->$field_name);
						if ($old_value != $new_value) {
							$old_value = FieldType30::display_value($old_value);
							$new_value = FieldType30::display_value($new_value);
							$record_history = true ;
						}
					} elseif($field_type == 150) {
						if ($obj->potentials_related_to_idmodule != $evctl->related_to_opt) {
							if ($obj->$field_name != $evctl->$field_name) {
								$old_value = $obj->potentials_related_to_value ;
								$new_value = FieldType150::display_value($evctl->$field_name,$evctl->related_to_opt,'',false);
								$record_history = true ;
							}
						} elseif ($obj->$field_name != $evctl->$field_name) {
							$old_value = $obj->potentials_related_to_value ;
							$new_value = FieldType150::display_value($evctl->$field_name,$evctl->related_to_opt,'',false);
							$record_history = true ;
						}
					} elseif ($field_type == 151) {
						if ($obj->events_related_to_idmodule != $evctl->related_to_opt) {
							if ($obj->$field_name != $evctl->$field_name) {
								$old_value = $obj->events_related_to_value ;
								$new_value = FieldType151::display_value($evctl->$field_name,$evctl->related_to_opt,'',false);
								$record_history = true ;
							}
						} elseif ($obj->$field_name != $evctl->$field_name) {
							$old_value = $obj->events_related_to_value ;
							$new_value = FieldType151::display_value($evctl->$field_name,$evctl->related_to_opt,'',false);
							$record_history = true ;
						}
					} elseif ($field_type == 9) {
						if (FieldType9::convert_before_save($evctl->$field_name) != $obj->$field_name) {
							$old_value = $obj->$field_name;
							$new_value = FieldType9::convert_before_save($evctl->$field_name);
							$record_history = true ;
						}
					} elseif ($field_type == 10) {
						if (FieldType10::display_value($obj->$field_name) != $evctl->$field_name) {
							$old_value = FieldType10::display_value($obj->$field_name) ;
							$new_value = $evctl->$field_name;
							$record_history = true ;
						}
					} elseif ($field_type == 3) {
						$old_value = $obj->$field_name;
						$new_value = $evctl->$field_name;
						$new_value = ($new_value == 'on' ? 1:0);
						$old_value = FieldType3::display_value($old_value);
						$new_value = FieldType3::display_value($new_value);
						if ($new_value != $old_value) {
							$record_history = true;
						}
					} elseif ($obj->$field_name != $evctl->$field_name) {
						$record_history = true ;
						if ($field_type == 130 || $field_type == 131 || $field_type == 132 || $field_type == 133 || 
							$field_type == 141 || $field_type == 142 || $field_type == 143 || $field_type == 160 || 
							$field_type == 166 || $field_type == 170 || $field_type == 180
						){
							$old_value = $this->get_field_values_entity($obj->$field_name,$field_type);
							$new_value = $this->get_field_values_entity($evctl->$field_name,$field_type);
						} else {
							$old_value = $obj->$field_name;
							$new_value = $evctl->$field_name ;
						}
					}
					if ($record_history === true) {
						$this->addNew();
						$this->id_referrer = (int)$id_referrer ;
						$this->iduser = $_SESSION["do_user"]->iduser ;
						$this->idmodule = $idmodule ;
						$this->date_modified = date("Y-m-d H:i:s");
						$this->action = 'value_changes';
						$this->idfields = $idfields ;
						$this->old_value = $old_value;
						$this->new_value = $new_value ;
						$this->add();
					}
					$do_crm_fields->free();
				}
			}
		}
	}
    
    public function add_custom_history($id_referrer, $idmodule, $history_text) {
		$this->addNew();
		$this->id_referrer = (int)$id_referrer ;
		$this->iduser = $_SESSION["do_user"]->iduser ;
		$this->idmodule = $idmodule ;
		$this->date_modified = date("Y-m-d H:i:s");
		$this->action = 'custom_history';
		$this->idfields = 0 ;
		$this->old_value = '';
		$this->new_value = $history_text ;
		$this->add();
    }
    
	/**
	* function to get the field value for the entity
	* @param integer $identity
	* @param integer $field_type
	* @return string value
	*/
	public function get_field_values_entity($identity,$field_type) {
		$fieldobject = 'FieldType'.$field_type;
		return $fieldobject::get_value($identity);
	}
    
    
}