<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Create purchase order from product 
* @author Abhik Chakraborty
*/  
    
$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module(16);//-- get block information for purchase order

$do_global_settings = new CRMGlobalSettings();
$inv_terms_cond = $do_global_settings->get_inventory_terms_condition();
$tems_condition = $inv_terms_cond["purchaseorder_terms_condition"];

$module_obj = new Products();
$module_obj->getId($sqcrm_record_id);

//update the properties
$module_obj->terms_condition = $tems_condition;

//-- create a new object for Products so that the previously created object is not overwritten
$do_products = new Products();
$product_available_tax = $do_products->get_products_tax($sqcrm_record_id);

$lineitems[] = array(
	"idlineitems"=>1,
	"item_type"=>"product",
	"item_name"=>$module_obj->product_name,
	"item_value"=>$sqcrm_record_id,
	"item_description"=>$module_obj->description,
	"item_quantity"=>'',
	"item_price"=>$module_obj->product_price,
	"discount_type"=>'',
	"discount_value"=>'',
	"discounted_amount"=>'',
	"tax_values"=>'',
	"product_available_tax"=>$product_available_tax,
	"taxed_amount"=>'',
	"total_after_discount"=>'',
	"total_after_tax"=>'',
	"net_total"=>''
);
$target_module = 'PurchaseOrder';
if (isset($_GET["return_page"]) && $_GET["return_page"] != '') {
	$return = $_GET["return_page"] ;
	$cancel_return = NavigationControl::getNavigationLink('Products',$return,$sqcrm_record_id);
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