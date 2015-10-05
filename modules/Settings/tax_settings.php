<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Currency setting page
* @author Abhik Chakraborty
*/  
$do_tax_settings = new TaxSettings();
$product_service_tax = $do_tax_settings->product_service_tax();
$shipping_handling_tax = $do_tax_settings->shipping_handling_tax();
$currency_data = json_decode($currency,true);
require_once('view/tax_settings_view.php');
?>