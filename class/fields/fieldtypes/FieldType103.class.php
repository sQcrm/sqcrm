<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType103
* Field Type 103 : Role Selector 
* @author Abhik Chakraborty
*/

class FieldType103 extends CRMFields {
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
		return _('Role Selector') ;
	}

	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$css = '',$ignore='') {
		$html = '';
		$role_name = '';
		if ($value != '') {
			$do_role = new Roles();
			$role_detail = $do_role->get_role_detail($value);
			if (is_array($role_detail) && count($role_detail) > 0) {
				$role_name = $role_detail["rolename"];
			}
		}
		
		$html .='<input type="text" class = "'.$css.'" name="role_name" id="role_name" value="'.$role_name.'" readonly>';
		$html .='&nbsp;&nbsp;<a href="#" onclick="get_roles_popup();" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus-sign"></i></a>';
		$html .='&nbsp;&nbsp;<a href="#" onclick="remove_role(\''.$name.'\');" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-remove"></i></a>';
		$html .='<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
		$html .= 
		"\n".'<script>
			function get_roles_popup(){ 
				var href = \'/popups/roles_popup_modal?m=Settings&action=list&fieldname='.$name.'&ignore='.$ignore.'\';
				if (href.indexOf(\'#\') == 0) {
					$(href).modal(\'open\');
				} else {
					$.get(href, function(data) {
						$(\'<div class="modal fade" tabindex="-1" role="dialog" id="role_selector">\' + data + \'</div>\').modal();
					}).success(function() { $(\'input:text:visible:first\').focus(); });
				}
			}
		</script>';
		echo $html;
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		if ($value != '') {
			$do_roles = new Roles();
			$roles_data = $do_roles->get_role_detail($value);
			if (is_array($roles_data) && count($roles_data)> 0) {
				return $roles_data["rolename"];
			} else { return ''; }
		} else { return $value ; }
	}
}