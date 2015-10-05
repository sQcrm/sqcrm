<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Home page landing page
* @author Abhik Chakraborty
*/  
$user_homepage_components = new UserHomepageComponents();
$user_homepage_components_data = $user_homepage_components->get_user_homepage_components($_SESSION["do_user"]->iduser);

$curreny = $_SESSION["do_global_settings"]->get_setting_data_by_name('currency_setting');
$currency_data = array();
if (false !== $curreny)
	$currency_data = json_decode($curreny,true);
	
if (is_array($user_homepage_components_data) && count($user_homepage_components_data) > 0) {
	$left_block = array();
	foreach ($user_homepage_components_data as $key=>$val) {
		if ($val["position"] == "left") {
			$left_block[$val["sequence"]] = array("component_name"=>$val["component_name"],"id"=>$val["id"]);
		}
	}
  
	$right_block = array();
	foreach ($user_homepage_components_data as $key=>$val) {
		if ($val["position"] == "right") {
			$right_block[$val["sequence"]] = array("component_name"=>$val["component_name"],"id"=>$val["id"]);
		}
	}
	$home_page_graph = new HomePageGraphs();
	require_once("view/homepage_view.php");
} else {
	require_once("view/homepage_without_component_view.php");
}
?>