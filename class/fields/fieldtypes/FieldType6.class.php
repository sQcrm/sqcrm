<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType6
* Field Type 6 : Multi select combo 
* @author Abhik Chakraborty
*/

class FieldType6 extends CRMFields {
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
		return _('Multi select combo') ;
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
		if ($value != '') {
			$val_to_array = explode(",",$value);
		}
		echo '<select class="'.$css.'" name = "'.$name.'[]" id="'.$name.'" size= 5 multiple>'."\n";
		if ($combo_values->getNumRows() > 0 ) {
			while ($combo_values->next()) {
				$select = '';
				if ($value != '' && in_array($combo_values->combo_value,$val_to_array)) { $select = 'Selected' ; }
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
    
	/**
	* convert the data to comma seperated string before saving the data
	* @param array $value
	* @return comma seperated string
	*/
	public static function convert_before_save($value) {
		$retval = '';
		if (is_array($value) && count($value) > 0) {
			$retval = implode(",",$value);
		}
		return $retval ;
	}
}
