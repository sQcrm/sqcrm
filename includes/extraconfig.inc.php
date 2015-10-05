<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

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