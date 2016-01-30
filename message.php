<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
include_once("config.php") ;
if (isset($_REQUEST["clean_message"]) && (int)$_REQUEST["clean_message"] == 1) {
	$_SESSION["do_crm_messages"]->errase_message();
}

?>