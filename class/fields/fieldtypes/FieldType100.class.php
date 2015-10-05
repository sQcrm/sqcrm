<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt

/**
* Class FieldType100
* Field Type 100 : User Name Text Box UNIQUE
* @author Abhik Chakraborty
*/

class FieldType100 extends CRMFields {
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
		return _('User Name') ;
	}
     
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		echo '<input type="text" class="'.$css.'" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		return $value ;
	}
}
