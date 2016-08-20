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
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list")?>"><?php echo _('Profile');?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage Profile and access to different modules and fields')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="row">
					<div class="col-md-12">
						<h2><small>
						<?php 
						echo _('Define Privileges for ');
						echo '" '.$do_profile->profilename.' "'; 
						?>
						</small></h2>
						<p class="lead"><?php echo _('Set privileges below.')?></p>
						<a class="btn btn-primary" data-toggle="modal" data-target="#rename_profile"><?php echo _('Rename')?></a>
						<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_permissions",$do_profile->idprofile)?>" class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i> <?php echo _('Update');?></a>
						<br /><br />
						<?php
						$profile_global_permissions = $do_profile->get_profile_global_permissions();
						?>
						<div class="clear_float"></div>
						<div class="box_content_header"><h2><small class="text-muted"><?php echo _('Global Privileges');?></small></h2>
							<hr class="form_hr">
								<label class="">
									<?php
									if ($profile_global_permissions[1] == 1 ) echo '<a href="#" class="btn btn-success btn-xs"><i class="glyphicon glyphicon--ok"></i></a>';
									else echo '<a href="#" class="btn btn-default btn-xs active"><i class="glyphicon glyphicon-remove"></i></a>';
									?>
									<b><?php echo _('View All');?></b>
								</label>
								<label class="checkbox"><?php echo _('Allow to view all information');?></label>
								<label class="">
									<?php
									if ($profile_global_permissions[2] == 1 ) echo '<a href="#" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-ok"></i></a>';
									else echo '<a href="#" class="btn btn-default btn-xs active"><i class="glyphicon glyphicon-remove"></i></a>';
									?>
									<b><?php echo _('Add/Edit/Delete All');?></b>
								</label>
								<label class="checkbox"><?php echo _('Allow to add/edit/delete all information');?></label>
						</div>
						<div class="box_content_header"><h2><small class="text-muted"><?php echo _('Privileges per module');?></small></h2>
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
													$profile_mod_permission_dis = '<a href="#" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-ok"></i></a>';
												} else { $profile_mod_permission_dis = '<a href="#" class="btn btn-default btn-xs active"><i class="glyphicon glyphicon-remove"></i></a>'; }
											} else { $profile_mod_permission_dis = '<a href="#" class="btn btn-default btn-xs active"><i class="glyphicon glyphicon-remove"></i></a>'; }
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
														$profile_std_permission_display = '<a href="#" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-ok"></i></a>';
													else
														$profile_std_permission_display = '<a href="#" class="btn btn-default btn-xs active"><i class="glyphicon glyphicon-remove"></i></a>';
												} else {
													$profile_std_permission_display = '<a href="#" class="btn btn-default btn-xs active"><i class="glyphicon glyphicon-remove"></i></a>';
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
				</div>
			</div><!--/datadisplay-outer-->
		</div><!--/span-->
	</div><!--/row-->
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="rename_profile">
	<?php 
	// Here goes updating the name and description of the profile
	$e_update = new Event("Profile->eventRenameProfile");
	$e_update->addParam("id",$do_profile->idprofile);
	$e_update->addParam("error_page",NavigationControl::getNavigationLink($module,"profile_detail",$do_profile->idprofile));
	$e_update->addParam("next_page",NavigationControl::getNavigationLink($module,"profile_detail",$do_profile->idprofile));
	echo '<form class="" id="Profile__eventRenameProfile" name="Profile__eventRenameProfile" action="/eventcontroler.php" method="post">';
	echo $e_update->getFormEvent();
	?>
	<div class="modal-dialog bs-example-modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-info"><?php echo _('Rename Profile')?></span></h3>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label" for="profilename"><?php echo _('Profile Name')?></label>  
					<input type="text" class="form-control input-sm" id="profilename" name="profilename" value="<?php echo $do_profile->profilename;?>"> 
				</div>
				<div class="form-group">
					<label class="control-label" for="description"><?php echo _('Description');?></label>  
					<textarea class="form-control input-sm" id="description" name="description" rows="3"><?php echo $do_profile->description;?></textarea>  
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Save Changes')?>"/>
			</div>
		</div>
	</div>
</form>
</div>