<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
$do_user = new User();
$field_name = $_GET["field"];
$field_val = $_GET[$field_name];
$action = $_GET["action"];
$sqrecord = '';
if (isset($_GET["sqrecord"]) && $_GET["sqrecord"] != 0) { $sqrecord = (int)$_GET["sqrecord"] ; }
$ret = $do_user->check_unique($field_name,$field_val,$action,$sqrecord) ;
echo json_encode($ret);
?>
