<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMListView 
* @author Abhik Chakraborty
*/
	

class CRMListView extends DataObject {
	public $table = "";
	public $primary_key = "";

	/**
	* function to get the field information for a list view (list,popup,related)
	* gets the object name with the module and the list type and then generates the 
	* persistant object for the module and returns the fields information.
	* @param string $module
	* @param integer $module_id
	* @param string $list_type
	* @return array with the field information
	*/
	public function get_listview_field_info($module,$module_id,$list_type,$custom_view_id=0) {
		$object_name = $this->generate_list_view_object_name($module,$list_type);
		$do_list = new $module();
		$do_list->sessionPersistent($object_name,"logout.php", TTL);
		if ((int)$custom_view_id > 0 ) {
			$do_customview_fields = new CustomViewFields();
			$fields_info = $do_customview_fields->get_custom_view_fields_information($custom_view_id);
		} else {
			// fall back to the module's member property list view field information
			$do_crm_fields = new CRMFields();
			$fields_info = $do_crm_fields->get_specific_fields_information($_SESSION[$object_name]->list_view_fields,$module_id,true);
		}
		$_SESSION[$object_name]->list_view_field_information = $fields_info ;
		return $fields_info ; 
	}
  
	/**
	* function to get the object name which was set in get_listview_field_info for a module in list view
	* @param string $module
	* @param string $list_type
	* @return persistant object name
	* @see self::get_listview_field_info()
	* @see self::generate_list_view_object_name()
	*/
	public function get_list_view_object($module,$list_type) {
		$object_name = $this->generate_list_view_object_name($module,$list_type);
		return $_SESSION[$object_name] ;
	}
  
	/**
	* function to generate an object name
	* @param string $module
	* @param string $list_type
	* @return string object name
	*/
	protected function generate_list_view_object_name($module,$list_type) {
		$object_name = "";
		if ($list_type == 'list') {
			$object_name = "do_".strtolower($module)."_list";
		} elseif ($list_type == 'popup') {
			$object_name = "do_".strtolower($module)."_list_popup";
		} elseif($list_type == 'related') {
			$object_name = "do_".strtolower($module)."_relatedlist";
		}
		return $object_name ;
	}
}
