<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* homepagegraphs
* @author Abhik Chakraborty
*/  
 
$gid = (int)$_REQUEST["gid"];
$home_page_graph = new HomePageGraphs();

switch ($gid) {
	case 1 : break;
	case 2 : break ;
	case 3 :
		echo $home_page_graph->get_prospect_by_sales_stage_graph_data();
		break;
}
 
?>