<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Vendor add 
* @author Abhik Chakraborty
*/ 

$do_crmfields = new CRMFields();
$do_block = new Block();
$do_block->get_block_by_module($module_id);
require_once('view/add_view.php');
?>