<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* import_step2.php, will read the uploaded csv file in step1 and will parse it for mapping
* @author Abhik Chakraborty
*/

$allow_step2 = true ;
$file_name = $_SESSION["do_import"]->get_csv_file_name();
$has_header = $_SESSION["do_import"]->get_has_header();
$import_module_id = $_SESSION["do_import"]->get_import_module_id() ;

if ($file_name == '') {
	$allow_step2 = false ;
	$msg = _('No CSV file found to do mapping !');
} else {
	$parse_data = $_SESSION["do_import"]->parse_import_file($file_name,3);
	$_SESSION["do_import"]->set_csv_full_length();
	if (is_array($parse_data) && sizeof($parse_data) > 0) {
		$rows = $parse_data["rows"];
		$length_rows = sizeof($rows);
		$mapping_first_row = $rows[0];
		if ($length_rows > 1) $mapping_second_row = $rows[1];
		if ($length_rows > 2) $mapping_third_row = $rows[2];
		if (is_array($mapping_second_row) && sizeof($mapping_second_row) > 0) {
			foreach ($mapping_second_row as $key=>$val) {
				if (strlen($val) > 30) $mapping_second_row[$key] = substr($val,0,30)." ...";
			}
		}
		if (is_array($mapping_third_row) && sizeof($mapping_third_row) > 0) {
			foreach ($mapping_third_row as $key=>$val) {
				if (strlen($val) > 30) $mapping_third_row[$key] = substr($val,0,30)." ...";
			}
		}
		$do_crmfields = new CRMFields(); 
		$do_crmfields->get_field_information_by_module($_SESSION["do_import"]->get_import_module_id());
		$module_fields = array() ;
		while ($do_crmfields->next()) {
			if ($do_crmfields->field_name == 'assigned_to') continue ;
			if ($import_module_id == 4) {
				if ($do_crmfields->field_name == 'contact_avatar' || $do_crmfields->field_name == 'portal_user' 
				|| $do_crmfields->field_name == 'support_start_date' || $do_crmfields->field_name == 'support_end_date'
				) continue ;
			}
			if ($import_module_id == 5) {
				if ($do_crmfields->field_name == 'related_to') {
					$related_to_contact = $do_crmfields->field_label.' ('.$_SESSION["do_module"]->modules_full_details[4]["name"].')';
					$related_to_org = $do_crmfields->field_label.' ('.$_SESSION["do_module"]->modules_full_details[6]["name"].')';
					$data = array(
						"field_name"=>"pot_related_to_contact",
						"field_label"=>$related_to_contact,
						"field_validation"=>$do_crmfields->field_validation
					);
					$module_fields["pot_related_to_contact"] = $data ;
			
					$data = array(
						"field_name"=>"pot_related_to_organization",
						"field_label"=>$related_to_org,
						"field_validation"=>$do_crmfields->field_validation
					);
					$module_fields["pot_related_to_organization"] = $data ;     
					continue ;
				}
			}
			if ($do_crmfields->field_type == 165) {
				$tax_settings = new TaxSettings();
				$product_service_tax = $tax_settings->product_service_tax();
				if (is_array($product_service_tax) && count($product_service_tax) > 0) {
					foreach ($product_service_tax as $key=>$val) {
						$module_fields[$val["tax_name"]] = array(
							"field_name"=>$val["tax_name"],
							"field_label"=>$val["tax_name"],
							"field_validation"=>''
						);
					}
					continue ;
				}
			}
			$data = array(
				"field_name"=>$do_crmfields->field_name,
				"field_label"=>$do_crmfields->field_label,
				"field_validation"=>$do_crmfields->field_validation
			);
			$module_fields[$do_crmfields->idfields] = $data ;
		}
		$row_length = sizeof($mapping_first_row);
		$_SESSION["do_import"]->set_csv_row_length($row_length);
    
		$mandatory_fields = array();
		$do_crmfields->get_field_validation_info($import_module_id);
		if ($do_crmfields->getNumRows() > 0) {
			while ($do_crmfields->next()) {
				if ($do_crmfields->field_name == 'assigned_to') continue ;
				if ($do_crmfields->field_name == 'related_to' && $import_module_id == 5) {
					$related_to_contact = $do_crmfields->field_label.' ('.$_SESSION["do_module"]->modules_full_details[4]["name"].')';
					$related_to_org = $do_crmfields->field_label.' ('.$_SESSION["do_module"]->modules_full_details[6]["name"].')';
					$field_label = $related_to_contact.' '._('or').' '.$related_to_org;
					$data = array(
						"field_name"=>$do_crmfields->field_name,
						"field_label"=>$field_label,
						"field_type"=>$do_crmfields->field_type,
						"field_validation"=>$do_crmfields->field_validation
					);
				} else {
					$data = array(
						"field_name"=>$do_crmfields->field_name,
						"field_label"=>$do_crmfields->field_label,
						"field_type"=>$do_crmfields->field_type,
						"field_validation"=>$do_crmfields->field_validation
					);
				}
				$mandatory_fields[] = $data;
			}
		}
    
		$saved_map = $_SESSION["do_import"]->get_saved_maps($import_module_id);  
	} else {
		$allow_step2 = false ;
		$msg = _('Mapping can not be done, file could not be parsed !');
	}
}

require_once('view/import_step2_view.php');
?>