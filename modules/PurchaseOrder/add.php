<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* PurchaseOrder add 
* @author Abhik Chakraborty
*/ 

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);

$do_global_settings = new CRMGlobalSettings();
$inv_terms_cond = $do_global_settings->get_inventory_terms_condition();
$tems_condition = $inv_terms_cond["purchaseorder_terms_condition"];

require_once('view/add_view.php');
?>