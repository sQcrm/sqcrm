<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
include_once("config.php") ;
print_r($_SESSION["do_cpanel_messages"]) ;
if (isset($_REQUEST["clean_message"]) && (int)$_REQUEST["clean_message"] == 1) {
	$_SESSION["do_cpanel_messages"]->errase_message();
}

?>