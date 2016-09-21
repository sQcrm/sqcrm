<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType1
* Field Type 210 : Twittter Handler 
* @author Abhik Chakraborty
*/

class FieldType210 extends CRMFields {
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
		return _('Twitter Handler') ;
	}

	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = 'form-control input-sm') {
		echo '<div class="input-group">';
		echo '<span class="input-group-addon">@</span>';
		echo '<input type="text" class="'.$css.'" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
		echo '</div>';
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	* @param boolean $format
	*/
	public static function display_value($value,$format=true) {
		$return_val = '';
		if (true === $format) {
			if ($value != '') {
				$return_val = '<a href="https://twitter.com/'.$value.'" target="_blank">@'.$value.'</a>';
			}
		} else {
			if ($value != '') {
				$return_val = '@'.$value ;
			}
		}
		return $return_val;
	}
}
