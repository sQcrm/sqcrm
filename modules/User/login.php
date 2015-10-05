<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Login page
* @author Abhik Chakraborty
*/
if (is_object($_SESSION["do_user"]) && $_SESSION["do_user"]->iduser > 0) {
	header("Location: ".NavigationControl::getNavigationLink("Home","index"));
	exit;
} else {
	require_once('view/login_view.php');
}
?>