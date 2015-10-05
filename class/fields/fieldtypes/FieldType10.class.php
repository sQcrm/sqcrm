<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType10
* Field Type 10 : Time 
* @author Abhik Chakraborty
*/

class FieldType10 extends CRMFields {
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
		return _('Time Picker') ;
	}

	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		if($value != '') $value = self::display_value($value);
		$html =  '<div class="input-append bootstrap-timepicker">';
		$html .= '<input type="text" id="'.$name.'" name="'.$name.'" class="input-small" readonly="readonly" value="'.$value.'">';
		$html .= '<span class="add-on"><i class="icon-time"></i></span>';
		$html .= '</div>';
		$html .= 
		"\n".
		'<script>
			$(function(){
				$("#'.$name.'").timepicker();
			});
		</script>
		';
		echo $html;
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		return date('h:i A',strtotime($value));
	}
    
	public static function convert_before_save($value) {
		$ret_val = date("H:i:s",strtotime($value));
		return $ret_val ;
	}
}
