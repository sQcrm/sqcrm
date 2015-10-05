<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Delete modal for list data
* cheks if the delete operation is permitted before deleting the item
* @author Abhik Chakraborty
*/
include_once("config.php");

$module = $_GET["m"];
$return_page = $_GET["referrar"];
$allow_del = false;
$module_id = $_SESSION["do_module"]->get_idmodule_by_name($module,$_SESSION["do_module"]);        
$ids = $_GET["chk"];
  
if (is_array($ids) && count($ids)>0) {
	// check if record against the module to validate if the user has permission to do a delete
	foreach ($ids as $record_id) {
		$allow_del = $_SESSION["do_crm_action_permission"]->action_permitted('delete',$module_id,$record_id);
		if ($allow_del === false ) break;
	}
	$msg = _('You are trying to delete a record, which you are not authorized to.');
} else {
	$msg = _('Please select atleast one record to perform this operation.');
}
  
if ($allow_del === true) {
	$e_del = new Event($module."->eventDeleteRecord");
	$e_del->addParam("ids",$ids);
	$e_del->addParam("next_page",NavigationControl::getNavigationLink($module,$return_page));
	echo '<form class="form-horizontal" id="'.$module.'__eventDeleteRecord" name="'.$module.'__eventDeleteRecord" action="/eventcontroler.php" method="post">';
	echo $e_del->getFormEvent();
?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
	</div>
	<div class="modal-body">
		<?php echo _('Are you sure you want to delete the records.');?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
	</div>
</form>
<?php } else {?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING');?></span>
	</div>
    <div class="modal-body alert-error">
		<?php echo $msg?>
    </div>
    <div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
    </div>
<?php 
} ?>