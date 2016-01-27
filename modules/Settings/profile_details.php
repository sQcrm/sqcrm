<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Profile listing page
* @author Abhik Chakraborty
*/  

$do_profile = new Profile();
$do_profile->getId((int)$_GET["sqrecord"]);
$do_module = new Module();
$do_module->get_all_active_module();
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list")?>">Profile</a></h3>
				<p><?php echo _('Manage Profile and access to different modules and fields')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="row-fluid">
					<div class="left_600">
						<h3>
						<?php 
						echo _('Define Privileges for ');
                        echo '" '.$do_profile->profilename.' "'; 
						?>
						</h3>
						<p><?php echo _('Set privileges below.')?></p>
					</div>
					<div class="right_200">
						<a class="btn btn-primary" data-toggle="modal" href="#rename_profile"><?php echo _('Rename')?></a>
						<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_permissions",$do_profile->idprofile)?>" class="btn btn-primary"><i class="icon-white icon-edit"></i> <?php echo _('Update');?></a>
					</div>
					<?php
					$profile_global_permissions = $do_profile->get_profile_global_permissions();
					?>
					<div class="clear_float"></div>
					<div class="box_content_header"><h3><?php echo _('Global Privileges');?></h3>
						<hr class="form_hr">
							<label class="">
								<?php
								if ($profile_global_permissions[1] == 1 ) echo '<a href="#" class="btn btn-success btn-mini-1"><i class="icon-white icon-ok"></i></a>';
								else echo '<a href="#" class="btn btn-inverse btn-mini-1"><i class="icon-white icon-remove"></i></a>';
								?>
								<b><?php echo _('View All');?></b>
							</label>
							<label class="checkbox"><?php echo _('Allow to view all information');?></label>
							<label class="">
								<?php
								if ($profile_global_permissions[2] == 1 ) echo '<a href="#" class="btn btn-success btn-mini-1"><i class="icon-white icon-ok"></i></a>';
								else echo '<a href="#" class="btn btn-inverse btn-mini-1"><i class="icon-white icon-remove"></i></a>';
								?>
								<b><?php echo _('Add/Edit/Delete All');?></b>
							</label>
							<label class="checkbox"><?php echo _('Allow to add/edit/delete all information');?></label>
					</div>
					<div class="box_content_header"><h3><?php echo _('Privileges per module');?></h3>
						<hr class="form_hr">
						<table class="datadisplay">  
							<thead>  
								<tr>
									<th><?php echo _('Module')?></th>
									<th><?php echo _('Add/Edit')?></th>
									<th><?php echo _('View')?></th>
									<th><?php echo _('Delete')?></th>
								</tr> 
							</thead>  
							<tbody>  
								<?php
								$mod_standard_permission = new ModuleStandardPermission();
								$profile_standard_permission_rel = $do_profile->get_all_module_standard_permissions();
								$profile_module_rel = $do_profile->get_all_module_permissions();
								while ($do_module->next()) {
								?>
								<tr>
									<td>
										<?php
										if (array_key_exists($profile_module_rel[$do_module->idmodule],$profile_module_rel)) {
											if ($profile_module_rel[$do_module->idmodule] == 1) {
												$profile_mod_permission_dis = '<a href="#" class="btn btn-success btn-mini-1"><i class="icon-white icon-ok"></i></a>';
											} else { $profile_mod_permission_dis = '<a href="#" class="btn btn-inverse btn-mini-1"><i class="icon-white icon-remove"></i></a>'; }
										} else { $profile_mod_permission_dis = '<a href="#" class="btn btn-inverse btn-mini-1"><i class="icon-white icon-remove"></i></a>'; }
										?>
										<?php echo $profile_mod_permission_dis.' '.$do_module->module_label;?>
									</td>
									<?php
									//$mod_standard_permission = $do_module->getChildModuleStandardPermission();
									$mod_standard_permission->get_module_standard_permissions($do_module->idmodule);
									if ($mod_standard_permission->getNumRows() > 0) {
										while ($mod_standard_permission->next()) {
											if (array_key_exists($profile_standard_permission_rel[$do_module->idmodule][$mod_standard_permission->idstandard_permission],$profile_standard_permission_rel)) {
												if ($profile_standard_permission_rel[$do_module->idmodule][$mod_standard_permission->idstandard_permission] == 1 )
													$profile_std_permission_display = '<a href="#" class="btn btn-success btn-mini-1"><i class="icon-white icon-ok"></i></a>';
												else
													$profile_std_permission_display = '<a href="#" class="btn btn-inverse btn-mini-1"><i class="icon-white icon-remove"></i></a>';
											} else {
												$profile_std_permission_display = '<a href="#" class="btn btn-inverse btn-mini-1"><i class="icon-white icon-remove"></i></a>';
											}
											echo '<td>'.$profile_std_permission_display.'</td>';
										}
									} else {
									?>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								<?php 
									} 
								?>
								</tr>
								<?php 
								} 
								?>
							</tbody>
						</table>
					</div>  
				</div>      
			</div><!--/datadisplay-outer-->
		</div><!--/span-->
	</div><!--/row-->
</div>

<div class="modal hide fade" id="rename_profile">
<?php 
// Here goes updating the name and description of the profile
$e_update = new Event("Profile->eventRenameProfile");
$e_update->addParam("id",$do_profile->idprofile);
$e_update->addParam("error_page",NavigationControl::getNavigationLink($module,"profile_detail",$do_profile->idprofile));
$e_update->addParam("next_page",NavigationControl::getNavigationLink($module,"profile_detail",$do_profile->idprofile));
echo '<form class="form-horizontal" id="Profile__eventRenameProfile" name="Profile__eventRenameProfile" action="/eventcontroler.php" method="post">';
echo $e_update->getFormEvent();
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3><?php echo _('Rename Profile')?></h3>
	</div>
	<div class="modal-body">
		<div class="control-group">  
			<label class="control-label" for="profilename"><?php echo _('Profile Name')?></label>  
			<div class="controls">  
				<input type="text" class="input-xlarge-100" id="profilename" name="profilename" value="<?php echo $do_profile->profilename;?>"> 
			</div>
		</div>
		<div class="control-group">  
			<label class="control-label" for="description"><?php echo _('Description');?></label>  
			<div class="controls">  
				<textarea class="input-xlarge" id="description" name="description" rows="3"><?php echo $do_profile->description;?></textarea>  
			</div>  
		</div>  
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Save Changes')?>"/>
	</div>
</form>
</div>    