<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType15
* Field Type 15 : Assigned to option for the crm entity
* It is a combination of all idusers and the groups from the CRM
* @author Abhik Chakraborty
*/

class FieldType15 extends CRMFields {
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
		return _('Assigned To') ;
	}

	/**
	* Static function to display form for the field type
	* @param string $value
	* @param string $css
	* @return html for the form containing the field
	*/
	public static function display_field($value = '',$css = '',$idmodule) {
		// make sure group option is disabled for sharing rule "Only Me" @v-0.9
		$module_data_share_permissions = $_SESSION["do_user"]->get_module_data_share_permissions();
		$hide_group = false ;
		if ($module_data_share_permissions[$idmodule] == 5) {
			$hide_group = true ;
		}
		$html = '';  
		$assigned_to_type = '';
		$do_user = new User();
		$do_group = new Group();
		$do_user->get_all_users();
		$do_group->get_all_groups();
		$assigned_to_selector_userschecked = '';
		$assigned_to_selector_groupchecked = '' ; 
		$assigned_to_show_userlist = 'style="display:none;"';
		$assigned_to_show_grouplist = 'style="display:none;"';
		
		if ($value == '') {
			$assigned_to = $_SESSION["do_user"]->iduser ; 
			$assigned_to_selector_userschecked = "CHECKED";
			$assigned_to_show_userlist = 'style="display:block;"';
		} else {
			if (preg_match("/user_/",$value,$matches)) { 
				$assigned_to_type = 'user';
			} elseif(preg_match("/group_/",$value,$matches)) {
				$assigned_to_type = 'group';
			}
			$val_exploded = explode("_",$value);
			$assigned_to = $val_exploded[1];
			if ($assigned_to_type == 'user') {
				$assigned_to_selector_userschecked = "CHECKED";
				$assigned_to_show_userlist = 'style="display:block;"';
			} elseif ($assigned_to_type == 'group') {
				$assigned_to_selector_groupchecked = "CHECKED";
				$assigned_to_show_grouplist = 'style="display:block;"';
			}
		}
		$html .= '<div class="btn-group" data-toggle="buttons-radio">';
		$html .= '<label class="btn"><input type = "radio" name="assigned_to_selector" value="user" '.$assigned_to_selector_userschecked.'>'._('User').'</label>';
		if (false === $hide_group ) {
			$html .= '<label class="btn"><input type = "radio" name="assigned_to_selector" value="group" '.$assigned_to_selector_groupchecked.'>'._('Group').'</label>';
		}
		$html .= '</div>';
		$html .= '<div id="user_selector_block" '.$assigned_to_show_userlist.'>';
		$html .= '<select name="user_selector" id="user_selector">';
		while ($do_user->next()) {
			$selected = '';
			if ($assigned_to == $do_user->iduser) $selected = "SELECTED" ; 
			$html .= '<option value="'.$do_user->iduser.'" '.$selected.'>'.$do_user->firstname.' '.$do_user->lastname.' ('.$do_user->user_name.')</option>';
		}
		$html .='</select>';
		$html .='</div>';

		$html .= '<div id="group_selector_block" '.$assigned_to_show_grouplist.'>';
		$html .= '<select name="group_selector" id="group_selector">';
		while ($do_group->next()) {
			$selected = '';
			if ($assigned_to == $do_group->idgroup) $selected = "SELECTED" ; 
			$html .= '<option value="'.$do_group->idgroup.'" '.$selected.'>'.$do_group->group_name.'</option>';
		}
		$html .='</select>';
		$html .='</div>';

		$html .= 
		"\n".
		'<script>
			$("input[name=\'assigned_to_selector\']").bind("click",assigned_to_selector_clicked);
				function assigned_to_selector_clicked(){
					if ($(this).val() == \'group\') {
						$("#user_selector_block").hide();
						$("#group_selector_block").show();
					}
					if ($(this).val() == \'user\') {
						$("#user_selector_block").show();
						$("#group_selector_block").hide();
					}
			}';
		$html .= "\n".'</script>';
		echo $html;
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		return $value ;
	}
}