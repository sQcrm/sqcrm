<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt

/**
* Class FieldType11
* Field Type 11 : Password MD5
* @author Abhik Chakraborty
*/

class FieldType11 extends CRMFields {
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
		return _('MD5 Password') ;
	}
     
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '') {
		echo '<input type="password" class="'.$css.'" name="'.$name.'" id="'.$name.'" value="">';
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	* @param boolean $change_pass
	*/
	public static function display_value($value,$module='',$sqcrm_record_id ='',$fld_name='',$change_pass= false) {
		if ($change_pass === true) {
			$html = '
			<a href="#" class="btn btn-primary btn-mini bs-prompt" id= "change_'.$fld_name.'"<i class="icon-white icon-edit">'._('change').'</i></a>';
			$html .= 
			"\n".
			'<script>
				$(\'#change_'.$fld_name.'\').click(function() { 
						var href = \'/popups/change_password_modal?m='.$module.'&sqrecord='.$sqcrm_record_id.'&fieldname='.$fld_name.'\';
						if (href.indexOf(\'#\') == 0) {
							$(href).modal(\'open\');
						} else {
							$.get(href, function(data) {
								$(\'<div class="modal hide in" id="listdata_popup_selector" style="width:700px;">\' + data + \'</div>\').modal();
								}).success(function() { $(\'input:text:visible:first\').focus(); });
						}
					}); 
					</script>';
			return $html;
		} else {
			return $value ;
		}
	}
}