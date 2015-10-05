<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Create invoice from quote 
* @author Abhik Chakraborty
*/  
    
$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module(15);//-- get block information for invoice

$do_global_settings = new CRMGlobalSettings();
$inv_terms_cond = $do_global_settings->get_inventory_terms_condition();
$tems_condition = $inv_terms_cond["invoice_terms_condition"];

$module_obj = new Quotes();
$module_obj->getId($sqcrm_record_id);

//-- get lineitems for quote
$do_lineitems = new Lineitems();
$do_lineitems->get_line_items(13,$sqcrm_record_id);

//update the properties
$module_obj->terms_condition = $tems_condition;
$module_obj->inv_billing_address = $module_obj->q_billing_address;
$module_obj->inv_shipping_address = $module_obj->q_shipping_address;
$module_obj->inv_billing_po_box = $module_obj->q_billing_po_box;
$module_obj->inv_shipping_po_box = $module_obj->q_shipping_po_box;
$module_obj->inv_billing_po_code = $module_obj->q_billing_po_code;
$module_obj->inv_shipping_po_code = $module_obj->q_shipping_po_code;
$module_obj->inv_billing_city = $module_obj->q_billing_city;
$module_obj->inv_shipping_city = $module_obj->q_shipping_city;
$module_obj->inv_billing_state = $module_obj->q_billing_state;
$module_obj->inv_shipping_state = $module_obj->q_shipping_state;
$module_obj->inv_billing_country = $module_obj->q_billing_country;
$module_obj->inv_shipping_country = $module_obj->q_shipping_country;

$do_products = new Products();
$lineitems = array();
if ($do_lineitems->getNumRows() > 0) {
	while ($do_lineitems->next()) {
		$product_available_tax = '';
		if ($do_lineitems->item_type == 'product') {
			$product_available_tax = $do_products->get_products_tax($do_lineitems->item_value);
		}
		$lineitems[] = array(
			"idlineitems"=>$do_lineitems->idlineitems,
			"item_type"=>$do_lineitems->item_type,
			"item_name"=>$do_lineitems->item_name,
			"item_value"=>$do_lineitems->item_value,
			"item_description"=>$do_lineitems->item_description,
			"item_quantity"=>$do_lineitems->item_quantity,
			"item_price"=>$do_lineitems->item_price,
			"discount_type"=>$do_lineitems->discount_type,
			"discount_value"=>$do_lineitems->discount_value,
			"discounted_amount"=>$do_lineitems->discounted_amount,
			"tax_values"=>$do_lineitems->tax_values,
			"product_available_tax"=>$product_available_tax,
			"taxed_amount"=>$do_lineitems->taxed_amount,
			"total_after_discount"=>$do_lineitems->total_after_discount,
			"total_after_tax"=>$do_lineitems->total_after_tax,
			"net_total"=>$do_lineitems->net_total
		);
	}
}
$target_module = 'Invoice';
if (isset($_GET["return_page"]) && $_GET["return_page"] != '') {
	$return = $_GET["return_page"] ;
	$cancel_return = NavigationControl::getNavigationLink('Quotes',$return,$sqcrm_record_id);
} else {
	$cancel_return = NavigationControl::getNavigationLink($module,"list");
}
//Assigned to iduser or group ?
if ($module_obj->iduser > 0) {
	$assigned_to = 'user_'.$module_obj->iduser;
} elseif ($module_obj->idgroup > 0) {
	$assigned_to = 'group_'.$module_obj->idgroup;
}

//--overwrite module id to 15 for edit_view_form_fields
$module_id = 15;
require_once('view/create_invoice_from_quote_view.php');
?>