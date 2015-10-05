<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* Recently viewed entity as Breadcrumb 
* @author Abhik Chakraborty
*/
if ($GLOBALS['RECENT_VIEW_TAB'] === true) {
	if ((int)$sqcrm_record_id > 0) {
		// record to breadcrumb
		$do_crmentity_recentviewed = new CRMEntityRecentlyViewed();
		$do_crmentity_recentviewed->add_recently_viewed((int)$sqcrm_record_id,(int)$module_id);
		$do_crmentity_recentviewed->free();
	}
	require_once("view/breadcrumb_view.php");
}
?>