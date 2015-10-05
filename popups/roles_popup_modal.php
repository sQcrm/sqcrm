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
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class=""><?php echo _('Select a role');?></span>
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
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
    </div>
</form>
<?php } else { ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING');?></span>
	</div>
	<div class="modal-body alert-error">
		<?php echo _('You do not have permission to perform this operation');?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
	</div>
<?php 
} ?>