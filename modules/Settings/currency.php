<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Currency setting page
* @author Abhik Chakraborty
*/  
$amount_example = 1000000.000 ;
$crm_global_settings = new CRMGlobalSettings();
$currency = $crm_global_settings->get_setting_data_by_name('currency_setting');
$currency_data = json_decode($currency,true);
require_once('view/currency_settings_view.php');
?>