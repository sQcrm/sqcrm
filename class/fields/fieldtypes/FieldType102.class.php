<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt

/**
* Class FieldType102
* Field Type 102 : User popup 
* @author Abhik Chakraborty
*/

class FieldType102 extends CRMFields {
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
		return _('User Selector') ;
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
		//if($value == '')
		$user_name = '';
		if ((int)$value > 0) {
			$do_user = new User();
			$do_user->getId((int)$value);
			if ($do_user->getNumRows() > 0) $user_name = $do_user->user_name ;
		}
		$html .='<input type="text" class = "'.$css.'" name="user_'.$name.'" id="user_'.$name.'" value="'.$user_name.'">';
		$html .='&nbsp;&nbsp;<a href="#"  id="select_'.$name.'"  class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus-sign"></i></a>';
		$html .='&nbsp;&nbsp;<a href="#" id="remove_'.$name.'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-remove"></i></a>';
		$html .='<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'">';
		$html .= 
		"\n".'<script>
			$(\'#select_'.$name.'\').click(function(){ 
				var href = \'/popups/listdata_popup_modal?m=User&action=list&fielddisp=user&fieldname='.$name.'\';
				if (href.indexOf(\'#\') == 0) {
					$(href).modal(\'open\');
				} else {
					$.get(href, function(data) {
						$(\'<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="listdata_popup_selector">\' + data + \'</div>\').modal();
					}).success(function() { $(\'input:text:visible:first\').focus(); });
				}
			}); 
			$(\'#remove_'.$name.'\').click( function(){
					$("#user_'.$name.'").attr(\'value\',\'\');
					$("#'.$name.'").attr(\'value\',\'\');
			});
		</script>';
		echo $html;
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		if ($value > 0) {
			$do_user = new User();
			$do_user->getId($value);
			if ($do_user->getNumRows() > 0) {
				return $do_user->firstname.' '.$do_user->lastname.' ('.$do_user->user_name.')';
			} else { return '' ; }
		} else { return '' ; } 
	}
}