<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Home Page Components
* @author Abhik Chakraborty
*/  

$home_page_components = new HomepageComponents();
$home_page_components->getAll();
$home_page_components_data = array();
while ($home_page_components->next()) {
	$data = array(
		"id"=>$home_page_components->idhomepage_component,
		"component_name"=>$home_page_components->component_name,
		"position"=>$home_page_components->position,
		"sequence"=>$home_page_components->sequence
	);
	$home_page_components_data[] = $data ;
}
$user_homepage_components = new UserHomepageComponents();
$user_homepage_components_data = $user_homepage_components->get_user_homepage_components($sqcrm_record_id);

if ($user_homepage_components_data === false)
	require_once('view/user_homepage_components_add_view.php');
else
	require_once('view/user_homepage_components_edit_view.php');

?>