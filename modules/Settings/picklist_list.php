<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Pick List/ Multi select combo values manage page
* @author Abhik Chakraborty
*/  
$do_crm_fields = new CRMFields();
$do_combo_values = new ComboValues();
$non_editable_combo_fields = $do_combo_values->get_non_editable_combo_fields();

// modules which does not have the custom fields or any fields 
$ignore_modules = array(1,8,9);  

if (isset($_GET["cmid"]) && $_GET["cmid"] != '') {
	$cf_module = (int)$_GET["cmid"] ;
} else {
	$cf_module = 3 ; 
}

$modules_info = $_SESSION["do_module"]->get_modules_with_full_info();
$do_crm_fields->get_pick_multiselect_fields($cf_module); 
$data_array = array();
if ($do_crm_fields->getNumRows() > 0) {
	while ($do_crm_fields->next()) {
		$combo_data = array();
		if (in_array($do_crm_fields->idfields,$non_editable_combo_fields)) continue ;
		$do_combo_values->get_combo_values($do_crm_fields->idfields);
		if ($do_combo_values->getNumRows() > 0) {
			$combo_data = array();
			while ($do_combo_values->next()) {
				$combo_data[] = $do_combo_values->combo_value;
			}
		}
		$data_array[$do_crm_fields->idfields]["field_label"] = $do_crm_fields->field_label ;
		$data_array[$do_crm_fields->idfields]["combo_data"] = $combo_data ;
	}
}

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/picklist_entry_view.php');
} else {
	require_once('view/picklist_view.php');
}
?>