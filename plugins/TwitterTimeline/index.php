<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
$idmodule = (int)$_GET["idmodule"] ;
$sqcrm_record_id = (int)$_GET["sqrecord"] ;

$module_name = $modules_info[$idmodule]['name'] ;
$do_module_object = new $module_name();
$pk = $do_module_object->getPrimaryKey();

$do_twitter_timeline = new TwitterTimeline();
$twitter_handlers = $do_twitter_timeline->get_twitter_handler($sqcrm_record_id,$idmodule,$pk);

include_once('view/plugin_view.php');
?>