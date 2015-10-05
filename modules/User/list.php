<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* User Module index file 
* @author Abhik Chakraborty
*/  
if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	require_once('view/listview_entry.php');
} else {
	require_once('view/listview.php');
}
?>