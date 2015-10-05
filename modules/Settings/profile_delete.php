<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Profile delete page
* @author Abhik Chakraborty
*/  

$do_profile = new Profile();
$idprofile = (int)$_GET["idprofile"];
$do_role = new Roles();
$do_role_profile_rel = new RoleProfileRelation();

$allow_role_delete = false ;
?>
<div class="container-fluid">
	<div class="row-fluid">
    <?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list")?>"><?php echo _('Profile');?></a></h3>
				<p><?php echo _('Delete Profile')?></p> 
			</div>
			<div class="row-fluid">
				<div id="rd_js_errors" style="display:none;"></div>
				<div class="datadisplay-outer">
					<?php
					$do_role = new Roles();
					if ($idprofile == 1) {
						$msg = _('The profile you are trying to delete is not allowd !');
					} else {
						$do_profile->getId($idprofile);
						if ($do_profile->getNumRows() > 0) {
							$allow_role_delete = true;
						} else {
							$msg = _('The profile you are trying to delete does not exist !');
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
						$associated_roles = $do_role_profile_rel->get_roles_related_to_profile($idprofile);
						if ($associated_roles === false) {
							echo '<div class="alert alert-info">';
							echo _('This profile is not associated with any role, so you can delete the profile without having to transfer any data');
							echo '</div>';
						} else {
							echo '<div class="alert alert-info">';
							echo _('This profile is associated with the following roles, please assign a different profile to these roles before deleting it.');
							echo '<br />';
							foreach ($associated_roles as $associated_roles) {
								echo '- '.$associated_roles.'<br />';
							}
							echo '</div>';
						}
						$e_del = new Event("Roles->eventDeleteRole");
						$e_del->addParam("idrole",$idrole);
						$e_del->addParam("next_page",NavigationControl::getNavigationLink($module,"roles_list"));
						if ($users !== false) {
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
								<input type="submit" class="btn btn-primary" id="delete_with_transfer" value="<?php echo _('Delete');?>"/>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list");?>" class="btn btn-inverse">
								<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
							</div>
						<?php
						} else {
						?>
						<div class="form-actions">  
							<input type="submit" class="btn btn-primary" id="delete_without_transfer" value="<?php echo _('Delete');?>"/>
							<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list");?>" class="btn btn-inverse">
							<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
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