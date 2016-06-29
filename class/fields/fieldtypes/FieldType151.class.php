<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType151
* Field Type 151 : All module selectors
* @author Abhik Chakraborty
*/

class FieldType151 extends CRMFields {
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
		return _('Related to Modules') ;
	}
    
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @param integer $idmodule
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '',$idmodule=0) {
		$module_selectors = array(3,4,5,6);
		$html = '';  
		
		$leads_style = '';
		$organization_style = 'style="display:none;"';
		$contact_style = 'style="display:none;"';
		$potential_style = 'style="display:none;"';
		
		$leads_val = '';
		$organization_val = '';
		$contact_val = '';
		$potential_val = '';
		
		if ($value != '' && (int)$idmodule > 0) {
			$value = (int)$value ;
			$mid = (int)$idmodule ;
			switch ($mid) {
				case 3 :  
					$leads_style = 'style="display:block;"';
					$contact_style = 'style="display:none;"';
					$organization_style = 'style="display:none;"';
					$potential_style = 'style="display:none;"';
					$leads_val = $value ;
					break;
				case 4 : 
					$contact_style = 'style="display:block;"';
					$organization_style = 'style="display:none;"';
					$leads_style = 'style="display:none;"';
					$potential_style = 'style="display:none;"';
					$contact_val = $value ;
					break;
				case 5 : 
					$potential_style = 'style="display:block;"';
					$contact_style = 'style="display:none;"';
					$leads_style = 'style="display:none;"';
					$organization_style = 'style="display:none;"';
					$potential_val = $value ;
					break;
				case 6 : 
					$organization_style = 'style="display:block;"';
					$contact_style = 'style="display:none;"';
					$leads_style = 'style="display:none;"';
					$potential_style = 'style="display:none;"';
					$organization_val = $value ;
					break;
			}
		} else {
			$mid = 3 ;
		}
		$html .='<div style="float:left;">';
		$html .='<select class="input-large" name="related_to_opt" id="related_to_opt">';
		foreach ($module_selectors as $module_selectors) {
			$select = '';
			if ($module_selectors == $mid) $select = 'SELECTED';
			$html .= '<option value= "'.$module_selectors.'" '.$select.'>'.$_SESSION["do_module"]->modules_full_details[$module_selectors]["name"].'</option>'."\n";
		}
		$html .='</select>';
		$html .='<input type="hidden" name = "'.$name.'" id= "'.$name.'" value="'.$value.'">';
		
		$html .='<div id="related_to_leads" '.$leads_style.'>';
		$html .= FieldType132::display_field('lead_field:::'.$name,$leads_val);
		$html .='</div>';
		
		$html .='<div id="related_to_contacts" '.$contact_style.'>';
		$html .= FieldType130::display_field('contacts_field:::'.$name,$contact_val);
		$html .='</div>';
		
		$html .='<div id="related_to_organization" '.$organization_style.'>';
		$html .= FieldType131::display_field('organization_field:::'.$name,$organization_val);
		$html .='</div>';
		
		$html .='<div id="related_to_potentials" '.$potential_style.'>';
		$html .= FieldType133::display_field('potentials_field:::'.$name,$potential_val);
		$html .='</div>';
		
		$html .='</div>';
		$html .= 
		"\n".'<script>
			$(\'#related_to_opt\').change( function(){
				var mid = $(\'#related_to_opt\').val() ;
				if(mid == 3){
					$("#related_to_leads").show();
					$("#related_to_contacts").hide();
					$("#related_to_organization").hide();
					$("#related_to_potentials").hide();
				}
				if(mid == 4){
					$("#related_to_leads").hide();
					$("#related_to_contacts").show();
					$("#related_to_organization").hide();
					$("#related_to_potentials").hide();
				}
				if(mid == 5){
					$("#related_to_leads").hide();
					$("#related_to_contacts").hide();
					$("#related_to_organization").hide();
					$("#related_to_potentials").show();
				}
				if(mid == 6){
					$("#related_to_leads").hide();
					$("#related_to_contacts").hide();
					$("#related_to_organization").show();
					$("#related_to_potentials").hide();
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
		} elseif ($idmodule_related_to == 3) {
			return FieldType132::display_value($related_to_id,$related_to_value,$linked);
		} elseif ($idmodule_related_to == 5) {
			return FieldType133::display_value($related_to_id,$related_to_value,$linked);
		}
	}
    
}