<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Delete modal for the user
* Checks if the delete is permitted
* @author Abhik Chakraborty
*/
include_once("config.php");
$module = $_GET["m"];
$return_page = $_GET["referrar"];
$allow_transfer = false;
$module_id = $_SESSION["do_module"]->get_idmodule_by_name($module,$_SESSION["do_module"]);        
$ids = $_GET["chk"];

// make sure group option is not shown when the module datashare permission is "Only Me" @v-0.9
$hide_group = false ;
$module_data_share_permissions = $_SESSION["do_user"]->get_module_data_share_permissions();
if ($module_data_share_permissions[$module_id] == 5) {
	$hide_group = true ;
}
if (is_array($ids) && count($ids)>0) {
	// check if record against the module to validate if the user has permission to do a edit
	foreach ($ids as $record_id) {
		$allow_transfer = $_SESSION["do_crm_action_permission"]->action_permitted('edit',$module_id,$record_id);
		if ($allow_transfer === false ) break;
	}
	$msg = _('You are trying to change assigned to for a record, which you are not authorized to.');
} else {
	$msg = _('Please select atleast one record to perform this operation.');
}
  
if ($allow_transfer === true) {
	$do_user = new User();
	$do_user->get_all_users();

	$group_transfer = false ;

	$do_group = new Group();
	$do_group->get_all_groups();
      
    //if there is no group then there is no group to trasfer data
	if ($do_group->getNumRows() > 0) {
		$group_transfer = true ;
	}
	
	if (true === $hide_group) $group_transfer = false ;
	
	$do_fields = new CRMFields();
	$do_fields->query("select `idfields` from `fields` where `field_name` = 'assigned_to' AND `idmodule` = ?",array($module_id));
	$fieldid = 0 ;
	if ($do_fields->getNumRows() > 0) {
		$do_fields->next();
		$fieldid = $do_fields->idfields ;
	}
	$e_change = new Event("CRMEntity->eventChangeAssignedToEntity");
	$e_change->addParam("ids",$ids);
	$e_change->addParam("module",$module);
	$e_change->addParam("module_id",$module_id);
	$e_change->addParam("fieldid",$fieldid);
	$e_change->addParam("next_page",NavigationControl::getNavigationLink($obj,$return_page));
	if ($group_transfer === true) {
		$e_change->addParam("group_transfer_opt","yes");
	} else {
		$e_change->addParam("group_transfer_opt","no");
	}
	echo '<form class="form-horizontal" id="CRMEntity__eventChangeAssignedToEntity" name="CRMEntity__eventChangeAssignedToEntity" action="/eventcontroler.php" method="post">';
	echo $e_change->getFormEvent();
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Change Assigned to');?></span></h3>
		</div>
		<div class="modal-body">
			<?php echo _('Please select an user or group to change the assigned to');?><br /><br />
			<?php
			if ($group_transfer === true) {
			?>
			<label class="checkbox-inline"><input type = "radio" name="assigned_to_selector" value="user" CHECKED><?php echo _('User');?></label>
			<label class="checkbox-inline"><input type = "radio" name="assigned_to_selector" value="group"><?php echo _('Group');?></label>
			<br /><br />
			<div id="user_selector_block">
				<select name="user_selector" id="user_selector" class="form-control input-sm">
					<?php
					while ($do_user->next()) {
						$user_dis = $do_user->firstname.' '.$do_user->lastname.' ('.$do_user->user_name.' )';
					?>
					<option value="<?php echo $do_user->iduser;?>"><?php echo $user_dis;?></option>
					<?php
					}
					?>
				</select>
			</div>
			<div id="group_selector_block" style="display:none;">
				<select name="group_selector" id="group_selector" class="form-control input-sm">
					<?php
					while ($do_group->next()) {
					?>
					<option value="<?php echo $do_group->idgroup ;?>"><?php echo $do_group->group_name; ?></option>
					<?php
					}
					?>
				</select>
			</div>
			<?php } else { ?>
			<br /><br />
			<div id="user_selector_block">
				<select name="user_selector" id="user_selector" class="form-control input-sm">
				<?php
				while ($do_user->next()) {
					$user_dis = $do_user->firstname.' '.$do_user->lastname.' ('.$do_user->user_name.' )';
				?>
				<option value="<?php echo $do_user->iduser;?>"><?php echo $user_dis;?></option>
				<?php
				}
				?>
				</select>
			</div>
			<?php 
			} ?>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
			<input type="submit" class="btn btn-primary" value="<?php echo _('Update')?>"/>
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
		<div class="modal-body">
			<h3><span class="label label-danger"><?php echo $msg?></span></h3>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
		</div>

<?php 
} ?>
<script>
$(document).ready(function() {
	$("input[name='assigned_to_selector']").bind("click",assigned_to_selector_changed);
    
    function assigned_to_selector_changed() {
		if ($(this).val() == 'group') {
			$("#user_selector_block").hide();
			$("#group_selector_block").show();
		}
		if ($(this).val() == 'user') {
			$("#user_selector_block").show();
			$("#group_selector_block").hide();
		}
	}
});
</script>