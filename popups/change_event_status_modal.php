<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* event status change modal
* Checks if the delete is permitted
* @author Abhik Chakraborty
*/
include_once("config.php");
$module = $_GET["m"];
$return_page = $_GET["referrar"];
$allow_status_change = false;
$module_id = $_SESSION["do_module"]->get_idmodule_by_name($module,$_SESSION["do_module"]);        
$ids = $_GET["chk"];

if (is_array($ids) && count($ids)>0) {
	// check if record against the module to validate if the user has permission to do a edit
	foreach ($ids as $record_id) {
		$allow_status_change = $_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id,$record_id);
		if ($allow_status_change === false ) break;
	}
	$msg = _('You are trying to change event status for a record, which you are not authorized to.');
} else {
	$msg = _('Please select atleast one record to perform this operation.');
}
  
if ($allow_status_change === true) {
		
	$do_fields = new CRMFields();
	$qry = "
	select cv.* 
	from combo_values cv 
	join fields f on f.idfields = cv.idfields 
	where 
	f.field_name='event_status' 
	and f.table_name='events'
	order by cv.sequence
	";
	$do_fields->query($qry);
	
	$e_change = new Event("Calendar->eventChangeEventStatus");
	$e_change->addParam("ids",$ids);
	$e_change->addParam("module",$module);
	$e_change->addParam("module_id",$module_id);
	$e_change->addParam("next_page",NavigationControl::getNavigationLink($obj,$return_page));
	echo '<form class="form-horizontal" id="Calendar__eventChangeEventStatus" name="Calendar__eventChangeEventStatus" action="/eventcontroler.php" method="post">';
	echo $e_change->getFormEvent();
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Change Event Status');?></span></h3>
		</div>
		<div class="modal-body">
			<?php echo _('Please select a status');?><br />
			<select name="event_status" id="event_status" class="form-control input-sm">
			<?php
			while ($do_fields->next()) {
				echo '<option value="'.$do_fields->combo_value.'">'.$do_fields->combo_option.'</option>';
			}
			?>
			</select>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
			<input type="submit" class="btn btn-primary" value="<?php echo _('Change')?>"/>
		</div>
		</form>
    </div>
</div>
<?php
} else {
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-warning"><?php echo _('WARNING');?></span></h3>
		</div>
		<div class="modal-body alert-error">
			<h3><span class="label label-danger"><?php echo $msg?></span></h3>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
		</div>
	</div>
</div>
<?php 
} ?>