<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Custom field mapping
* @author Abhik Chakraborty
*/  
$modules_info = $_SESSION["do_module"]->get_modules_with_full_info();
$do_fields_mapping = new CRMFieldsMapping();
$custom_fields_mapping_info = $do_fields_mapping->get_custom_field_mappings();
$custom_fields = new CustomFields();
$contacts_custom_fields = $custom_fields->get_custom_fields_as_array(4);
$potentials_custom_fields = $custom_fields->get_custom_fields_as_array(5);
$organization_custom_fields = $custom_fields->get_custom_fields_as_array(6);
require_once('view/customfield_mapping_view.php');
?>