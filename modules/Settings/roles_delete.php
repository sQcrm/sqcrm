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
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list")?>"><?php echo _('Roles'); ?></a></li>
				</ol>
				<p class="lead"><?php echo _('Delete Role')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div id="rd_js_errors" style="display:none;"></div>
				<div class="row">
					<div class="col-md-12">
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
							echo '<div class="alert alert-danger">';
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
							echo '<form class="" id="Roles__eventDeleteRole" name="Roles__eventDeleteRole" action="/eventcontroler.php" method="post">';
							echo $e_del->getFormEvent();
							echo '<br />';
							if ($users !== false) {
								FieldType103::display_field("idrole_transfer",'','',$idrole);
								echo '<br />';
							?>
								<hr class="form_hr">
								<a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list");?>" class="btn btn-default active">
								<i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Cancel');?></a>  
								<input type="submit" class="btn btn-primary" id="delete_with_transfer" value="<?php echo _('Delete');?>"/>
							<?php
							} else {
							?>
								<hr class="form_hr">
								<a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list");?>" class="btn btn-default active">
								<i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Cancel');?></a>  
								<input type="submit" class="btn btn-primary" id="delete_without_transfer" value="<?php echo _('Delete');?>"/>
							<?php
							}
						}
						?>
					</div>
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