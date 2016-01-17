<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* List view data 
* @author Abhik Chakraborty
*/  
$queue_found = false ;
$related = false ;
$do_queue = new Queue() ;
if (isset($_GET['related']) && true == $_GET['related']) {
	$related = true ;
	$related_module_id = (int)$_GET['related_module_id'] ;
	$related_record_id = (int)$_GET['related_record_id'] ;
	$queue_data  = $do_queue->get_queue_detail($related_record_id,$related_module_id);
} else {
	$queue_data  = $do_queue->get_all_queue();
}
if (count($queue_data) > 0) $queue_found = true ;
if (isset($_GET['ajaxreq']) && $_GET['ajaxreq'] == true) {
	if (true === $related) {
		require_once('view/queue_entry_related.php');
	} else {
		require_once('view/queue_entry_view.php');
	}
} else {
	require_once('view/queue_view.php');
}
?>