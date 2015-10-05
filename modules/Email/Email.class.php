<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Class to maintain the Email module 
*/
class Email extends EmailTemplate {
	public $table = "emailtemplate";
	public $primary_key = "idemailtemplate";

	/*variable to store the total of list query , does not consider the limit and where into account */
	protected $list_tot_rows = 0 ;

	/* public array to hold the field information for the list view */
	public $list_view_field_information = array();

	public $list_view_fields = array("name","subject","sendername","senderemail","assigned_to");

	/* Array holding the field values to be displayed by the popup section */
	public $popup_selection_fields = array("name","subject","sendername","senderemail","assigned_to");

	/* On popup select returned field, should be one of popup_selection_fields*/
	public $popup_selection_return_field = "subject";

	/* default order by in the list view */
	protected $default_order_by = "`emailtemplate`.`name`";
  
	public $module_group_rel_table = "email_to_grp_rel";
  
	function __construct($conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}
  
  
}

?>
