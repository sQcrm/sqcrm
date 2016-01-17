<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Cronjob to delete the old queue. 
* The queue listing page will list only the data which greater than equal to the day
* So no need to keep the older queue in the database.
* Usually it should run every day once.
* $GLOBALS['cfg_full_path'] is the path to the CRM root so that the cron job could be 
* set outside web access directory which is recomended
* @author Abhik Chakraborty
*/


$GLOBALS['cfg_full_path'] = '/var/www/sqcrm/';

include_once($GLOBALS['cfg_full_path'].'config.php');

$do_queue = new Queue();
$do_queue->delete_older_queue();
?>