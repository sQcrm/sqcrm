<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Inventory settigs page
* @author Abhik Chakraborty
*/  
$crm_global_settings = new CRMGlobalSettings();
$inventory_prefixes = $crm_global_settings->get_inventory_prefixes();
$inventory_terms_cond = $crm_global_settings->get_inventory_terms_condition();
//$inventory_logo = $crm_global_settings->get_inventory_logo();
$inventory_logo = $crm_global_settings->get_setting_data_by_name('inventory_logo');
$company_address = $crm_global_settings->get_setting_data_by_name('company_address');
require_once('view/inventory_settings_view.php');
?>