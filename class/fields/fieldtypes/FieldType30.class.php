<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType30
* Field Type 30 : Amount Text Box 
* @author Abhik Chakraborty
*/

class FieldType30 extends CRMFields{
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
		return _('Amount Text Box') ;
	}

	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		$currency = $_SESSION["do_global_settings"]->get_setting_data_by_name('currency_setting');
		$currency_data = json_decode($currency,true);
		if ($value > 0) {
			$value = number_format(
				$value,
				$currency_data["decimal_point"], 
				$currency_data["decimal_symbol"],
				$currency_data["thousand_seperator"]
			);
		}
		if ($currency_data["currency_symbol_position"] == "left") {
			echo $currency_data["currency_sysmbol"];
			echo " ";
			echo '<input type="text" class="'.$css.'" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
		} elseif ($currency_data["currency_symbol_position"] == "right") {
			echo '<input type="text" class="'.$css.'" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
			echo " ";
			echo $currency_data["currency_sysmbol"];
		}
		echo '<br /><script type="text/javascript">
			$("#'.$name.'").maskMoney(
				{ 
					thousands:\''.$currency_data["thousand_seperator"].'\', 
					decimal:\''.$currency_data["decimal_symbol"].'\',
					precision:'.$currency_data["decimal_point"].'
				}
				);
		</script>';
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		if ($value===null || $value == '') $value = 0.00; //-- handle empty and null values
		$currency = $_SESSION["do_global_settings"]->get_setting_data_by_name('currency_setting');
		$currency_data = json_decode($currency,true);
		$formated_val = number_format(
			$value,
			$currency_data["decimal_point"], 
			$currency_data["decimal_symbol"],
			$currency_data["thousand_seperator"]
		);
		if ($currency_data["currency_symbol_position"] == "left") {
			return $currency_data["currency_sysmbol"]." ".$formated_val ;
		} elseif($currency_data["currency_symbol_position"] == "right") {
			return $formated_val." ".$currency_data["currency_sysmbol"] ;
		}
	}
    
	public static function convert_before_save($value) {
		$s = str_replace(',','.',$value);
		$s= preg_replace("/[^0-9\.]/","",$s);
		$has_decimal_val = (substr($s,-3,1) == '.');
		$s = str_replace('.','',$s);
		if ($has_decimal_val) {
			$s = substr($s,0,-2).'.'.substr($s,-2);
		}
		return (float)$s;
	}
}