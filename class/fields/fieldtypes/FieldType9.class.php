<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType9
* Field Type 9 : Date 
* @author Abhik Chakraborty
*/

class FieldType9 extends CRMFields {
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
		return _('Date') ;
	}

	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		if ($_SESSION["do_user"]->iduser != '') {
			$date_format = $_SESSION["do_user"]->date_view ;
		} else { 
			$date_format = 'mm/dd/yyyy'; 
		}
		if ($value != '' && $value != '0000-00-00') {
			$formated_date = self::convert_to_date_format($date_format,$value);
		} else {
			$formated_date = '';
		}
		$html = '';
		$html .= '
		<div class="input-append date" data-date="'.$formated_date.'" data-date-format="'.$date_format.'" id="dd_'.$name.'" >
			<input name="'.$name.'" id="'.$name.'" value="'.$formated_date.'" readonly="readonly" type="text"><span class="add-on"><span class="glyphicon glyphicon-calendar" aria-hidden="true" style="cursor: pointer;"></span></span>
		</div>';
		$html .= 
		"\n".'
		<script>
			$(function() {
				$(\'#dd_'.$name.'\').datepicker({autoclose: true});
			});
		</script>
		';
		echo $html ;
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		if ($_SESSION["do_user"]->iduser != '') {
			$date_format = $_SESSION["do_user"]->date_view ;
		} else { 
			$date_format = 'mm/dd/yyyy'; 
		}
		if ($value != '' && $value != '0000-00-00') {
			$formated_date = self::convert_to_date_format($date_format,$value,$time);
		} else {
			$formated_date = '';
		}
		return $formated_date ;
	}
    
	/**
	* function to convert the date to user's date format
	* @param string $date_format
	* @param string $value
	*/
	public static function convert_to_date_format($date_format,$value) {
		$ret_val = $value;
		switch ($date_format) {
			case 'mm-dd-yyyy':
				$ret_val =  date("m-d-Y",strtotime($value));
				break;
			case 'mm/dd/yyyy':
				$ret_val =  date("m/d/Y",strtotime($value));
				break;
			case 'dd-mm-yyyy':
				$ret_val =  date("d-m-Y",strtotime($value));
				break;
			case 'dd/mm/yyyy':
				$ret_val =  date("d/m/Y",strtotime($value));
				break;
			case 'yyyy-mm-dd':
				$ret_val =  $value ;
				break;  
			case 'yyyy/mm/dd':
				$ret_val =  date("Y/m/d",strtotime($value));
				break;  
		}
		return $ret_val;
	}
    
	public static function convert_before_save($value) {
		if ($value != '') {
			if ($_SESSION["do_user"]->iduser != '') {
				$date_format = $_SESSION["do_user"]->date_view ;
			} else { 
				$date_format = 'mm/dd/yyyy'; 
			}
			switch($date_format) {
				case 'mm-dd-yyyy':
					$date_explode = explode("-",$value);
					$ret_val = $date_explode[2].'-'.$date_explode[0].'-'.$date_explode[1];
					break;
				case 'mm/dd/yyyy':
					$date_explode = explode("/",$value);
					$ret_val = $date_explode[2].'-'.$date_explode[0].'-'.$date_explode[1];
					break;
				case 'dd-mm-yyyy':
					$ret_val =  date("Y-m-d",strtotime($value));
					break;
				case 'dd/mm/yyyy':
					$ret_val =  date("Y-m-d",strtotime($value));
					break;
				case 'yyyy-mm-dd':
					$ret_val =  $value ;
					break;  
				case 'yyyy/mm/dd':
					$ret_val =  date("Y-m-d",strtotime($value));
					break;  
			}
			return $ret_val;
		}
	}
}

class FieldType91 extends FieldType9 {
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
		return _('Date Time') ;
	}
  
	public static function display_value($value) {
		$value = TimeZoneUtil::convert_to_user_timezone($value,true);
		$val = explode(" ",$value);
		$date = parent::display_value($val[0]);
		$time = i18nDate::i18n_time($val[1]);
		return $date.' '.$time ;
	}
  
}
