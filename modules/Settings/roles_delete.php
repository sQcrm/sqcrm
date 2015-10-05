<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Roles delete page
* @author Abhik Chakraborty
*/  

$do_profile = new Profile();
$do_profile->getAll();

$idrole = $_GET["idrole"];
$do_role = new Roles();
$role_detail = $do_role->get_role_detail($idrole);

$allow_role_delete = false ;
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list")?>">Roles</a></h3>
				<p><?php echo _('Delete Role')?></p> 
			</div>
			<div class="row-fluid">
				<div id="rd_js_errors" style="display:none;"></div>
				<div class="datadisplay-outer">
					<?php
					$do_role = new Roles();
					if ($idrole == 'N1' || $idrole == 'N2') {
						$msg = _('The role you are trying to delete is not allowd !');
					} else {
						$role_detail = $do_role->get_role_detail($idrole);
						if (count($role_detail) > 0) {
							$allow_role_delete = true;
						} else {
							$msg = _('The role you are trying to delete does not exist !');
						}
					}
					if ($allow_role_delete === false) {
						echo '<div class="alert alert-error alert-block" style="height:100px;margin-top:20px;margin-left:200px;margin-right:200px;">';
						echo '<h4>';
						echo _('Delete not allowed !');
						echo '</h4>';
						echo $msg ;
						echo '</div>';
					} elseif ($allow_role_delete === true) {
						$users = $do_role->get_users_with_idrole($idrole);
						if ($users === false) {
							echo '<div class="alert alert-info">';
							echo _('There is no user associated with this role, so you can delete the role without having to transfer any data');
							echo '</div>';
						} else {
							echo '<div class="alert alert-info">';
							echo _('Following users are associated with the role you are trying to delete, please assign a different role to these users before delting the role.');
							echo '<br />';
							foreach ($users as $users) {
								echo '- '.$users["user_name"]. '( '.$users["full_name"].' )<br />';
							}
							echo '</div>';
						}
						$e_del = new Event("Roles->eventDeleteRole");
						$e_del->addParam("idrole",$idrole);
						$e_del->addParam("next_page",NavigationControl::getNavigationLink($module,"roles_list"));
						if( $users !== false) {
							$e_del->addParam("role_transfer","yes");
						} else {
							$e_del->addParam("role_transfer","no");
						}
						echo '<form class="form-horizontal" id="Roles__eventDeleteRole" name="Roles__eventDeleteRole" action="/eventcontroler.php" method="post">';
						echo $e_del->getFormEvent();
						echo '<br />';
						if ($users !== false) {
							FieldType103::display_field("idrole_transfer",'','',$idrole);
							echo '<br />';
						?>
							<div class="form-actions">  
								<a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list");?>" class="btn btn-inverse">
								<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
								<input type="submit" class="btn btn-primary" id="delete_with_transfer" value="<?php echo _('Delete');?>"/>
							</div>
						<?php
						} else {
						?>
							<div class="form-actions">  
								<a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list");?>" class="btn btn-inverse">
								<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
								<input type="submit" class="btn btn-primary" id="delete_without_transfer" value="<?php echo _('Delete');?>"/>
							</div>
						<?php
						}
					}
					?>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>
<script>
$(document).ready(function() {  
	$("#delete_with_transfer").click(function() {
		if ($("#idrole_transfer").val() == '') {
			display_js_error('Please select a role to transfer the users.','rd_js_errors');
			return false ;
		}
	});
});
</script>