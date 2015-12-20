<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType20
* Field Type 20 : Text Area Expanding
* @author Abhik Chakraborty
*/

class FieldType20 extends CRMFields {
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
		return _('Expanding Text Area') ;
	}
     
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		echo '<textarea class="'.$css.'" name="'.$name.'" id="'.$name.'">'.$value.'</textarea>';
		echo 
		"\n".'<script>
			$("#'.$name.'").expandingTextarea();
		</script>'."\n";
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		echo nl2br($value) ;
	}
}
