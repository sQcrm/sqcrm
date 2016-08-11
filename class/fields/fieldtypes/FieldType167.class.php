<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Class FieldType167
* Field Type 167 : Spipping/Handling
* @author Abhik Chakraborty
*/

class FieldType167 extends CRMFields {
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
		return _('Spipping/Handling Tax') ;
	}
    
	/**
	* Static function to display form for the field type
	* @param string $name
	* @param string $value
	* @return html for the form containing the field
	*/
	public static function display_field($name,$value = '') {
		$html = '';  
		$do_tax_settings = new TaxSettings();
		if (strlen($value) > 5) {
			$html .= '<table>';
			$i=1;
			$tax_settings = $do_tax_settings->shipping_handling_tax();
			$tax_setting_names  = array();
			$tax_setting_values = array();
			foreach ($tax_settings as $key=>$val) {
				$tax_setting_names[]= $val["tax_name"];
				$tax_setting_values[$val["tax_name"]] = $val["tax_value"];
			}
			$used_tax_values = array();
			$tax_name_value_pair = explode(',',$value);
			foreach ($tax_name_value_pair as $taxes) {
				$html .= '<tr>';
				$tax_values = explode('::',$taxes);
				$used_tax_values[] = $tax_values[0];
				$html .= '
				<td style="width:90px;">
					<input type="checkbox" name="'.$name.'[]" CHECKED id = "sh_tax_ft_'.$i.'" value="'.$tax_values[0].'" onclick="show_shtax_val_ft(\''.$i.'\')"><span style="font-size: 12px;margin-left:4px;">'.$tax_values[0]. ' ( % )</span>
				</td>
				<td style="margin-left:5px;"><span id="tax_vl_sh_ft_'.$i.'" style="display:block;"><input type="text" value="'.$tax_values[1].'" class="form-control input-sm" name="'.$name.'_'.$i.'"></td>';
				$html .= '</tr>';			
				$i++;
			}
			$diff = array_diff($tax_setting_names,$used_tax_values);
			foreach ($diff as $t_name=>$t_val) {
				$html .= '
				<td style="width:90px;">
					<input type="checkbox" name="'.$name.'[]" id ="sh_tax_ft_'.$i.'" value="'.$t_val.'" onclick="show_shtax_val_ft(\''.$i.'\')"><span style="font-size: 12px;margin-left:4px;">'.$t_val. ' ( % )</span>
				</td>
				<td style="margin-left:5px;"><span id="tax_vl_sh_ft_'.$i.'" style="display:none;"><input type="text" value="'.$tax_setting_values[$t_val].'" class="form-control input-sm" name="'.$name.'_'.$i.'"></td>';
				$html .= '</tr>';		
				$i++;
			}
			$html .= '</table>';
		} else {
			$qry = "select * from shipping_handling_tax";
			$do_tax_settings->query($qry);
			if ($do_tax_settings->getNumRows() > 0) {
				$html .= '<table>';
				$i=1;
				while ($do_tax_settings->next()) {
					$html .= '<tr>';
					$html .= '
					<td style="width:90px;">
						<input type="checkbox" name="'.$name.'[]" id = "sh_tax_ft_'.$i.'" value="'.$do_tax_settings->tax_name.'" onclick="show_shtax_val_ft(\''.$i.'\')"><span style="font-size: 12px;margin-left:4px;">'.$do_tax_settings->tax_name. ' ( % )</span>
					</td>
					<td style="margin-left:5px;"><span id="tax_vl_sh_ft_'.$i.'" style="display:none;"><input type="text" value="'.$do_tax_settings->tax_value.'" class="form-control input-sm" name="'.$name.'_'.$i.'"></td>';
					$html .= '</tr>';			
					$i++;
				}
				$html .= '</table>';
			}
		}
		$html .= "\n".'<script>';
		$html .= 
		"\n".
		'function show_shtax_val_ft(id){
			var span_id = \'tax_vl_sh_ft_\'+id;
			var check_box_id = \'sh_tax_ft_\'+id;
			if($("#"+check_box_id).is(\':checked\')){
				$("#"+span_id).show(\'slow\');
			}else{
				$("#"+span_id).hide(\'slow\');
			}
		}';
		$html .= '</script>';
		echo $html;
	}

	/**
	* Static function to display the data in detail view
	* @param string $value
	*/
	public static function display_value($value) {
		return $value;
	}
}
