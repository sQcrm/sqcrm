<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

//  Log errors in the pas_errro.log file:
define("RADRIA_LOG_ERROR", false);
//  Display errors in generated web pages:
define("RADRIA_DISPLAY_ERROR", false);
//  Log general message/debug log in the pas_run.log:
define("RADRIA_LOG_RUNLOG", false);
//  Display message/debug log in generated web pages:
define("RADRIA_DISPLAY_RUNLOG", false);

/**
* loading the auto-load config stuff 
*/
$d = dir($cfg_project_directory."includes/");
while($entry = $d->read()) {
	if (preg_match("/\.conf\.inc\.php$/i", $entry) && !preg_match("/^\./", $entry)) {
		$config_files[] = $entry;
	}
}
$d->close();

if (is_array($config_files)) {
	sort($config_files) ;
	foreach($config_files as $config_file) {
		include_once($config_file);
	}    
}

// Get all the config files from modules
$it = new RecursiveDirectoryIterator($cfg_project_directory.'modules/');
foreach(new RecursiveIteratorIterator($it) as $file) {
	if (preg_match("/\.conf\.inc\.php$/i", $file) && !preg_match("/^\./", $file)) {
		include_once($file);
	}
}
?>