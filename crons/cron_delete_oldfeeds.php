<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Cronjob to delete the old feeds. With some moderate number of users within the system there will be
* a lot of feeds and over a period of time the table size will increase exponentially. So its better to 
* clean the the feed table on regular interval. By default in includes/sqcrm.conf.inc.php the days to keep is 90 days.
* We can change the value as we want.
* Usually it should run every day once.
* $GLOBALS['cfg_full_path'] is the path to the CRM root so that the cron job could be 
* set outside web access directory which is recomended
* @author Abhik Chakraborty
*/


$GLOBALS['cfg_full_path'] = '/var/www/sqcrm/';

include_once($GLOBALS['cfg_full_path'].'config.php');
$q = new sqlQuery($GLOBALS['conx']);

$do_live_feed_queue = new LiveFeedQueue();
$do_live_feed_queue->get_feeds_to_be_deleted();
if ($do_live_feed_queue->getNumRows() > 0) {
	while ($do_live_feed_queue->next()) {
		$do_live_feed_queue->delete_feed_queue($do_live_feed_queue->idfeed_queue);
	}
}
echo "Total number of old feeds deleted ".$do_live_feed_queue->getNumRows();
?>