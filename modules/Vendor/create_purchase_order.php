<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Create purchase order from vendor 
* @author Abhik Chakraborty
*/  
    
$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module(16);//-- get block information for purchase order

$do_global_settings = new CRMGlobalSettings();
$inv_terms_cond = $do_global_settings->get_inventory_terms_condition();
$tems_condition = $inv_terms_cond["purchaseorder_terms_condition"];

$module_obj = new Vendor();
$module_obj->getId($sqcrm_record_id);

//update the properties
$module_obj->terms_condition = $tems_condition;
$module_obj->po_billing_address = $module_obj->vendor_street;
$module_obj->po_shipping_address = $module_obj->vendor_street;
$module_obj->po_billing_po_box = $module_obj->vendor_po_box;
$module_obj->po_shipping_po_box = $module_obj->vendor_po_box;
$module_obj->po_billing_po_code = $module_obj->vendor_postal_code;
$module_obj->po_shipping_po_code = $module_obj->vendor_postal_code;
$module_obj->po_billing_city = $module_obj->vendor_city;
$module_obj->po_shipping_city = $module_obj->vendor_city;
$module_obj->po_billing_state = $module_obj->vendor_state;
$module_obj->po_shipping_state = $module_obj->vendor_state;
$module_obj->po_billing_country = $module_obj->vendor_country;
$module_obj->po_shipping_country = $module_obj->vendor_country;

$target_module = 'PurchaseOrder';
if (isset($_GET["return_page"]) && $_GET["return_page"] != '') {
	$return = $_GET["return_page"] ;
	$cancel_return = NavigationControl::getNavigationLink('Vendor',$return,$sqcrm_record_id);
} else {
	$cancel_return = NavigationControl::getNavigationLink($module,"list");
}
//Assigned to iduser or group ?
if ($module_obj->iduser > 0) {
	$assigned_to = 'user_'.$module_obj->iduser;
} elseif ($module_obj->idgroup > 0) {
	$assigned_to = 'group_'.$module_obj->idgroup;
}

//--overwrite module id to 14 for edit_view_form_fields
$module_id = 16;
require_once('view/create_po_from_vendor_view.php');
?>