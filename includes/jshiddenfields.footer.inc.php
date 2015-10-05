<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/** 
* Hidden fields used in JS which are predefined and mostly server side values
* Ex : user's date format which is needed for date comparison while used in calendar
* @author Abhik Chakraborty
*/
$logged_in = false ;
if (is_object($_SESSION["do_user"]) && $_SESSION["do_user"]->iduser > 0) $logged_in = true ;

if ($logged_in === true) {
	echo '<input type="hidden" name="js_user_date_format" id="js_user_date_format" value = "'.$_SESSION["do_user"]->date_view.'">';
}
?>