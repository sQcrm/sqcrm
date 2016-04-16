<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Invoice detail 
* @author Abhik Chakraborty
*/  

$do_crmfields = new \CRMFields();
$do_block = new \Block();
$do_block->get_block_by_module($module_id);

$module_obj = new Invoice();
$module_obj->getId($sqcrm_record_id);

$do_lineitems = new \Lineitems();
$do_lineitems->get_line_items($module_id,$sqcrm_record_id);
$lineitems = array();
if ($do_lineitems->getNumRows() > 0) {
	while ($do_lineitems->next()) {
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
			"taxed_amount"=>$do_lineitems->taxed_amount,
			"total_after_discount"=>$do_lineitems->total_after_discount,
			"total_after_tax"=>$do_lineitems->total_after_tax,
			"net_total"=>$do_lineitems->net_total
		);
	}
}

//updates detail, just add and last updated
$do_crmentity = new \CRMEntity();
$update_history = $do_crmentity->get_last_updates($sqcrm_record_id,$module_id,$module_obj);

if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/detail_view_entry.php');
} else {
	require_once('view/detail_view.php');
}

?>