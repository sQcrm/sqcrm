<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* Header file to include all the header information
* @author Abhik Chakraborty
*/
?>
<html>
	<head>
		<title><?php echo $pageTitle;?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="/themes/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="/themes/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
		<link href="/themes/custom-css/custom.css" rel="stylesheet">
		<link href="/themes/plugins/datepicker/datepicker.css" rel="stylesheet"> 
		<link rel="stylesheet" href="/themes/plugins/bootstrap-timepicker/css/timepicker.css" type="text/css"> 
		<link rel="shortcut icon" href="/themes/images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/themes/images/favicon.ico" type="image/x-icon">
		<meta name="author" content="<?php echo $Author; ?>">
		<meta name="keywords" content="<?php echo $Keywords; ?>">
		<meta name="description" content="<?php echo $Description; ?>">
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php 
		$d = dir(CPANEL_PATH."/includes/");
		while ($entry = $d->read()) {
			if (preg_match("/\.header\.inc\.php$/i", $entry) && !preg_match("/^\./", $entry)) {
				include_once($entry);
			}
		}
		$d->close();
		?>
  </head>
  <body>
