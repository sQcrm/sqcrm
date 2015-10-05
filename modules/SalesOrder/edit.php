<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* SalesOrder edit 
* @author Abhik Chakraborty
*/  
    
$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$do_global_settings = new CRMGlobalSettings();
$inv_terms_cond = $do_global_settings->get_inventory_terms_condition();
$tems_condition = $inv_terms_cond["salesorder_terms_condition"];

$module_obj = new SalesOrder();
$module_obj->getId($sqcrm_record_id);

$do_lineitems = new Lineitems();
$do_lineitems->get_line_items($module_id,$sqcrm_record_id);

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
//print_r($lineitems);
if (isset($_GET["return_page"]) && $_GET["return_page"] != '') {
	$return = $_GET["return_page"] ;
	$cancel_return = NavigationControl::getNavigationLink($module,$return,$sqcrm_record_id);
} else {
	$cancel_return = NavigationControl::getNavigationLink($module,"list");
}
//Assigned to iduser or group ?
if ($module_obj->iduser > 0) {
	$assigned_to = 'user_'.$module_obj->iduser;
} elseif ($module_obj->idgroup > 0) {
	$assigned_to = 'group_'.$module_obj->idgroup;
}
require_once('view/edit_view.php');
?>