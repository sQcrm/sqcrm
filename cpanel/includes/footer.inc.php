<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/** 
* HTML and include Footer
* @author Abhik Chakraborty
*/
$d = dir(CPANEL_PATH."/includes/");
while($entry = $d->read()) {
	if (preg_match("/\.footer\.inc\.php$/i", $entry) && !preg_match("/^\./", $entry)) {
		include_once($entry);
	}
}
$d->close();
?>
</body>
</html>
