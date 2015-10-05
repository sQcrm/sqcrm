<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt

/**
* Class FieldType3
* Field Type 3 : Checkbox 
* @author Abhik Chakraborty
*/

class FieldType3 extends CRMFields {
	public $table = "fields";
	public $primary_key = "idfields";

	/**
	* Constructor function 
	*/
	function __construct(sqlConnect $conx=NULL, $table_name="") {
		parent::__construct($conx, $table_name);
	}

	/**
	* Function to get the field type, like Text Box, Text Area, Checkbox etc
	*/
	public static function get_field_type() {
		return _('Checkbox') ;
	}
     
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		$checked = '';
		if ($value == 1) {       
			$checked = 'CHECKED';
		}
		echo '<input type="checkbox" class="'.$css.'" name="'.$name.'" id="'.$name.'"  '.$checked.'>';
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		if ($value == 1) return _('Yes'); 
		else return _('No');
	}
}
