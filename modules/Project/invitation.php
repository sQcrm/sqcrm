<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Project invitation 
* @author Abhik Chakraborty
*/  
$do_peoject = new Project();
$idinvite = $_REQUEST['idinvite'];
$allow_action = false;
$idproject = $do_peoject->check_valid_invitation($idinvite);

if (false !== $idproject) {
	$allow_action = true;
	$do_peoject->getId($idproject);
	$project_name = $do_peoject->project_name;
}
require_once('view/project_invitation.php');

?>