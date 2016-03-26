<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* root index.php file and redirect to the Home index page 
* @author Abhik Chakraborty
*/

include_once('config.php');
 
$do_user = new \cpanel_user\User() ;

$e_login = new Event("\cpanel_user\User->eventLogin");
echo '<form class="form-horizontal" id="\cpanel_user\User__eventLogin" name="\cpanel_user\User__eventLogin" action="eventcontroler.php" method="post">';
echo $e_login->getFormEvent();
echo '<input type="submit" name="login_submit" class="btn btn-primary" value="login">';
echo '</form>';
?>