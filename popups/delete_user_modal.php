<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Delete modal for the user
* Checks if the delete is permitted
* @author Abhik Chakraborty
*/
include_once("config.php");
$obj =  $_REQUEST["classname"];
$module = $_REQUEST["m"];
$return_page = $_REQUEST["referrar"];
$delete_single = false ;
$delete_mul = false ;

if (isset($_REQUEST["sqrecord"]) && (int)$_REQUEST["sqrecord"] > 0) {
	$id = (int)$_REQUEST["sqrecord"] ;
	$delete_single = true ;
}

if (isset($_REQUEST["chk"])) {
	$mul_records = $_REQUEST["chk"] ;
	if (is_array($mul_records) && count($mul_records) > 0) {
		$delete_mul = true ;
	}
}
$do_user = new User();
$do_user->get_all_users();
  
if ($delete_single === true) {
	$e_del = new Event($obj."->eventDeleteRecord");
	$e_del->addParam("id",$id);
	$e_del->addParam("next_page",NavigationControl::getNavigationLink($obj,$return_page));
	echo '<form class="form-horizontal" id="'.$obj.'__eventDeleteRecord" name="'.$obj.'__eventDeleteRecord" action="/eventcontroler.php" method="post">';
	echo $e_del->getFormEvent();
} elseif ($delete_mul === true) { 
	$e_del = new Event($obj."->eventDeleteRecordMul");
	$e_del->addParam("ids",$mul_records);
	$e_del->addParam("next_page",NavigationControl::getNavigationLink($obj,$return_page));
	echo '<form class="form-horizontal" id="'.$obj.'__eventDeleteRecordMul" name="'.$obj.'__eventDeleteRecordMul" action="/eventcontroler.php" method="post">';
	echo $e_del->getFormEvent();
}
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<h3><?php echo _('Delete user data transfer');?></h3>
</div>
<div class="modal-body">
<?php echo _('Before deleting the user please select an user to trasfer the existing user\'s data.');?>
<br />
	<div id="user_selector_block">
		<select name="user_selector" id="user_selector">
		<?php
		while ($do_user->next()) {
			if (in_array($do_user->iduser,$mul_records) && $delete_mul === true) continue ;
				if ($do_user->iduser == $sqrecord && $delete_single === true ) continue ;
				$user_dis = $do_user->firstname.' '.$do_user->lastname.' ('.$do_user->user_name.' )';
		?>
			<option value="<?php echo $do_user->iduser;?>"><?php echo $user_dis;?></option>
		<?php
		}
		?>
        </select>
	</div>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
	<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
</div>
</form>