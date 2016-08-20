<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Popup modal for the roles
* @author Abhik Chakraborty
*/
include_once("config.php");
$allow = true;

if ($allow === true) {  
	$roles = new Roles();
	$field_name = $_REQUEST["fieldname"];
	$ignore = $_REQUEST["ignore"];
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Select a role');?></span></h3>
		</div>
		<div class="modal-body">
			<div class="datadisplay-outer">
				<div class="css-treeview">
				<?php 
				$depth_zero_role = $roles->get_depth_zero_role();
				$roles->render_role_hierarchy_popup_selection($field_name,0,$depth_zero_role,$ignore);
				?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
		</div>
	</div>
</div>
<?php
} else {
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
		</div>
		<div class="modal-body">
			<div class="alert alert-danger">
				<?php echo _('You do not have permission to perform this operation');?>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
		</div>
	</div>
</div>
<?php 
} ?>