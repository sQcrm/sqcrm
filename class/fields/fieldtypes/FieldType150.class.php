<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType150
* Field Type 150 : Combination of Contacts and Organization Selector 
* @author Abhik Chakraborty
*/

class FieldType150 extends CRMFields{
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
		return _('Related to (Contacts / Organization)') ;
	}
    
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param integer $idmodule
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$idmodule=0) {
		$html = '';  
		$organization_style = '';
		$contact_style = 'style="display:none;"';
		$contact_selected = '';
		$organization_selected = '';
		$organization_val = '';
		$contact_val = '';
		if ($value != '' && (int)$idmodule > 0) {
			$value = (int)$value ;
			$mid = (int)$idmodule ;
			switch ($mid) {
				case 4 : 
					$contact_style = 'style="display:block;"';
					$organization_style = 'style="display:none;"';
					$contact_selected = "SELECTED";
					$contact_val = $value ;
					break;
				case 6 : 
					$contact_style = 'style="display:none;"';
					$organization_style = 'style="display:block;"';
					$organization_selected = "SELECTED";
					$organization_val = $value ;
					break;
				
			}
		} else {
			$mid = 6 ;
		}
		$html .='<select class="input-large" name="related_to_opt" id="related_to_opt">';
		$html .='<option value="6" '.$organization_selected.'>'._('Organization').'</option>';
		$html .='<option value="4" '.$contact_selected.'>'._('Contacts').'</option>';
		$html .='</select>';
		$html .='<input type="hidden" name = "'.$name.'" id= "'.$name.'" value="'.$value.'">';
		$html .='<div id="related_to_organization" '.$organization_style.'>';
		$html .= FieldType131::display_field('organization_field:::'.$name,$organization_val);
		$html .='</div>';
		
		$html .='<div id="related_to_contacts" '.$contact_style.'>';
		$html .= FieldType130::display_field('contacts_field:::'.$name,$contact_val);
		$html .='</div>';
		$html .= 
		"\n".
		'<script>
		$(\'#related_to_opt\').change( function() {
			var mid = $(\'#related_to_opt\').val() ;
			if(mid == 4) {
				$("#related_to_contacts").show();
				$("#related_to_organization").hide();
			}
			if(mid == 6) {
				$("#related_to_organization").show();
				$("#related_to_contacts").hide();
			}
		});
		</script>'."\n";        
		echo $html;
	}

	/**
	* Static function to display the data in detail view
	* @param integer $related_to_id
	* @param integer $idmodule_related_to
	* @param string $related_to_value
	* @param boolean $linked
	*/
	public static function display_value($related_to_id,$idmodule_related_to,$related_to_value='',$linked=true) {
		if ($idmodule_related_to == 4) {
			return FieldType130::display_value($related_to_id,$related_to_value,$linked);
		} elseif ($idmodule_related_to == 6) {
			return FieldType131::display_value($related_to_id,$related_to_value,$linked);
		}
	}
    
}
