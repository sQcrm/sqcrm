<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType5
* Field Type 5 : Drop down combo 
* @author Abhik Chakraborty
*/

class FieldType5 extends CRMFields {
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
		return _('Pick List') ;
	}
	
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$idfield,$value = '',$css = '') {
		$combo_values = new ComboValues();
		$combo_values->get_combo_values($idfield);
		echo '<select class="'.$css.'" name = "'.$name.'" id="'.$name.'">'."\n";
		if ($combo_values->getNumRows() > 0) {
			while ($combo_values->next()) {
				$select = '';
				if ($value == $combo_values->combo_value) { $select = 'Selected' ; }
				echo '<option value = "'.$combo_values->combo_option.'" '.$select.'>'.$combo_values->combo_value.'</option>'."\n";
			}
		}
		echo '</select>';
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		return $value;
	}
}
