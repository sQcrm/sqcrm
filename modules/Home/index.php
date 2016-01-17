<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Home page landing page
* @author Abhik Chakraborty
*/  

$do_dash_board = new DashboardWidgetProcessor() ;
$widgets = $do_dash_board->get_user_widgets() ;
require_once("view/homepage_view.php");
?>