<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
$idmodule = (int)$_GET["idmodule"] ;
$sqcrm_record_id = (int)$_GET["sqrecord"] ;

$module_name = $modules_info[$idmodule]['name'] ;
$do_module_object = new $module_name();

$do_invoice_payments = new InvoicePayments();

$payments = $do_invoice_payments->get_invoice_payments($sqcrm_record_id);
$due_amount = FieldType30::display_value($do_invoice_payments->get_due_amount($sqcrm_record_id));
$payment_modes = $do_invoice_payments->get_payment_modes();
include_once('view/plugin_view.php');
?>