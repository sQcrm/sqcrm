<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType160
* Field Type 160 : Vendor Selector 
* @author Abhik Chakraborty
*/

class FieldType160 extends CRMFields {
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
		return _('Vendor Selector') ;
	}
    
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		$html = '';  
		// if($value == '')
		if ((int)$value > 0) {
			$object = new Vendor();
			$retrun_fields = $object->popup_selection_return_field;
			$retrun_field_list = explode(",",$retrun_fields); 
			$object->query("select ".$retrun_fields." from `vendor` where `idvendor` = ?",array($value));
			if ($object->getNumRows() > 0) {
				$object->next();
				$cnt_return_fields = 0 ;
				foreach ($retrun_field_list as $retrun_fields) {
					if ($cnt_return_fields > 0) $display_val .= ' ';
					$display_val .= $object->$retrun_fields;
					$cnt_return_fields++;
				}
			}
		}
		$special_field = false ;
		if (preg_match("/:::/", $name)) {
			$name_explode = explode(':::',$name);
			$name = 'vendor_'.$name_explode[1];
			$special_field = true ;
		}
		$html .='<input type="text" class = "'.$css.'" name="vendor_'.$name.'" id="vendor_'.$name.'" value="'.$display_val.'" readonly>';
		$html .='&nbsp;&nbsp;<a href="#"  id="select_'.$name.'"  class="btn btn-primary btn-mini"><i class="icon-white icon-plus-sign"></i></a>';
		$html .='&nbsp;&nbsp;<a href="#" id="remove_'.$name.'" class="btn btn-primary btn-mini"><i class="icon-white icon-remove"></i></a>';
		$html .='<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
		$html .= "\n".'<script>';
		// add icon click function
		$html .= 
		"\n".
			'$(\'#select_'.$name.'\').click(function(){ ';
		if ($special_field === true) {
			$html .=' var href = \'/popups/listdata_popup_modal?m=Vendor&action=list&special_field=yes&special_field_name='.$name_explode[1].'&fielddisp=vendor&fieldname='.$name.'\';';
		} else {
			$html .=' var href = \'/popups/listdata_popup_modal?m=Vendor&action=list&fielddisp=vendor&fieldname='.$name.'\';';
		}
		
		$html .=
		"\n".
		'if (href.indexOf(\'#\') == 0) {
			$(href).modal(\'open\');
		} else {
			$.get(href, function(data) {
				//ugly heck to prevent the content getting append when opening the same modal multiple time
				$("#listdata_popup_selector").html(\'\'); 
				$("#listdata_popup_selector").attr("id","ugly_heck");
				$(\'<div class="modal hide fade in" id="listdata_popup_selector" style="width:700px;">\' + data + \'</div>\').modal();
			}).success(function() { $(\'input:text:visible:first\').focus(); });
		}';
		$html .= 
		'});';
		// click function ends here
		
		// remove icon click function
		if ($special_field === true) {
			$html .=  
			"\n".
			'$(\'#remove_'.$name.'\').click( function(){
				$("#vendor_'.$name.'").attr(\'value\',\'\');
				$("#'.$name.'").attr(\'value\',\'\');
				$("#'.$name_explode[1].'").attr(\'value\',\'\');
			});';
		} else {
			$html .= 
			"\n".
			'$(\'#remove_'.$name.'\').click( function(){
				$("#vendor_'.$name.'").attr(\'value\',\'\');
				$("#'.$name.'").attr(\'value\',\'\');
			});';
		}
		// remove icon click function ends here
		$html .= '</script>';
		if ($special_field === true) {
			return $html;
		} else {
			echo $html;
		}
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	* @param string $vendor_name
	* @param boolean $linked
	*/
	public static function display_value($value,$vendor_name='',$linked=true) {
		if ($vendor_name != '') {
			if ($linked === true)
				return '<a href="'.NavigationControl::getNavigationLink('Vendor',"detail",$value).'">'.$vendor_name.'</a>';
			else
				return $vendor_name;
		} else {
			$display_val = self::get_value($value);
			if ($display_val == '') {
				return $display_val ;
			} else {
				if ($linked === true)
					return '<a href="'.NavigationControl::getNavigationLink('Vendor',"detail",$value).'">'.$display_val.'</a>';
				else
					return $display_val;
			}
		}
	}
    
	/**
	* Function to get the value of the entity 
	* @param integer $value
	* @return string $retval
	*/
	public static function get_value($value) {
		$retval = '';
		if ((int)$value > 0) {
			$object = new Vendor();
			$retrun_fields = $object->popup_selection_return_field;
			$retrun_field_list = explode(",",$retrun_fields); 
			$object->query("select ".$retrun_fields." from vendor where idvendor = ?",array($value));
			if ($object->getNumRows() > 0) {
				$object->next();
				$cnt_return_fields = 0 ;
				foreach ($retrun_field_list as $retrun_fields) {
					if ($cnt_return_fields > 0) $retval .= ' ';
					$retval .= $object->$retrun_fields;
					$cnt_return_fields++;
				}
			}
		}
		return $retval ;
	}
}