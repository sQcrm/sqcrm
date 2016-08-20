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

$id = (int)$_REQUEST["sqrecord"] ;

$do_user = new User();
$do_user->get_all_users();

$group_transfer = false ;
  
$do_group = new Group();
$do_group->get_all_groups();

//if there is only one group in the system and we choose to delete it then there is no group to trasfer data
if ($do_group->getNumRows() > 1) {
	$group_transfer = true ;
}
  
$e_del = new Event($obj."->eventDeleteRecord");
$e_del->addParam("id",$id);
$e_del->addParam("next_page",NavigationControl::getNavigationLink($obj,$return_page));
  
if ($group_transfer === true) {
	$e_del->addParam("group_transfer_opt","yes");
} else {
	$e_del->addParam("group_transfer_opt","no");
}
echo '<form class="form-horizontal" id="'.$obj.'__eventDeleteRecord" name="'.$obj.'__eventDeleteRecord" action="/eventcontroler.php" method="post">';
echo $e_del->getFormEvent();
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Delete group data transfer');?></span></h3>
		</div>
		<div class="modal-body">
		<?php echo _('Before deleting the group please select an user or group to trasfer the existing group\'s data.');?>
		<br /><br />
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
					if ($do_group->idgroup == $id) continue;
					?>
					<option value="<?php echo $do_group->idgroup ;?>"><?php echo $do_group->group_name; ?></option>
					<?php
					}
					?>
				</select>
			</div>
			<?php 
			} else { ?>
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
		<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> <?php echo _('Close');?></a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
	</div>
</form>
</div>
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