<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class CRMOwner 
* Mantain the Owner of the CRM
* @author Abhik Chakraborty
*/
	

class CRMOwner extends DataObject {
	public $table = "crm_owner";
	public $primary_key = "idcrm_owner";

	/**
	* Function to get the CRM owner
	* @return idcrm_owner
	*/
	public static function get_crm_owner() {
		//return 1;
		return 'sQcrm' ;
	}
}
