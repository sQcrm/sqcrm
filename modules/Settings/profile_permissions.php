<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Profile listing page
* @author Abhik Chakraborty
*/  

$do_profile = new Profile();
$allow_operation = false ;
if (isset($_GET["sqrecord"]) && $_GET["sqrecord"]!='') {
	$idprofile = (int)$_GET["sqrecord"];
	$mode = 'edit';
	$do_profile->getId($idprofile);
	$profile_name = $do_profile->profilename ;
	$allow_operation = true ;
}
if ($_SESSION["profilename"]!= '') {
	$profile_name = $_SESSION["profilename"];
	$mode = 'add';
	$allow_operation = true ;
}
$do_module = new Module();
$do_module->get_all_active_module();
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings');?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list")?>"><?php echo _('Profile');?></a></h3>
				<p><?php echo _('Manage Profile and access to different modules and fields')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="left_600">
					<h3>
						<?php 
						echo _('Define Privileges for ');
                        echo '" '.$profile_name.' "'; 
						?>
					</h3>
					<p><?php echo _('Set privileges below.')?></p>
				</div>
				<?php 
				if ($mode=='add' && $allow_operation === true) { 
					$e_add_profile = new Event("Profile->eventAddNewProfile");
                    echo '<form class="form-horizontal" id="Profile__eventAddNewProfile" name="Profile__eventAddNewProfile" action="/eventcontroler.php" method="post">';
                    echo $e_add_profile->getFormEvent();
				?>
					<div class="right_200">
						<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list");?>" class="btn btn-inverse">
							<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" value="<?php echo _('Save'); ?>"/>
					</div>
					<div class="clear_float"></div>
					<div class="box_content_header"><h3><?php echo _('Set Global Privileges');?></h3>
						<hr class="form_hr">
						<label class="checkbox">
							<input type="checkbox" name="global_view_all" id="global_view_all" onclick="set_global_permission('global_view_all')"> 
							<b><?php echo _('View All');?></b> 
						</label>  
						<label class="checkbox"><?php echo _('Allow to view all information');?></label>
						<label class="checkbox">
							<input type="checkbox" name="global_addedit_all" id="global_addedit_all" onclick = "set_global_permission('global_addedit_all')"> 
							<b><?php echo _('Add/Edit/Delete All'); ?></b>
						</label>
						<label class="checkbox"><?php echo _('Allow to add/edit/delete all information');?> </label>
					</div>
					<div class="box_content_header"><h3><?php echo _('Set Privileges per module');?></h3>  
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
								while ($do_module->next()) {
								?>
								<tr>
									<td id="module_permission">
										<label class="checkbox">
											<input type="checkbox" name="mod_<?php echo $do_module->idmodule?>" id="mod_<?php echo $do_module->idmodule?>" onclick="set_standard_permission_by_module('<?php echo $do_module->idmodule;?>')" CHECKED> 
											<?php echo $do_module->module_label;?>
										</label>
									</td>
									<?php				
									$mod_standard_permission->get_module_standard_permissions($do_module->idmodule);
									if ($mod_standard_permission->getNumRows() > 0) {
										while ($mod_standard_permission->next()) {
											if (in_array($mod_standard_permission->idstandard_permission,$standard_permissions)) {
												if ($mod_standard_permission->idstandard_permission == 1) {
													$td_id = "add_permission";
												} elseif ($mod_standard_permission->idstandard_permission == 2) {
													$td_id = "view_permission";
												} elseif ($mod_standard_permission->idstandard_permission == 3) {
													$td_id = "delete_permission";
												}
												echo '<td id="'.$td_id.'">
												<input style="margin-left:10px;" type="checkbox" name= "m_'.$do_module->idmodule.'_'.$mod_standard_permission->idstandard_permission.'" id= "m_'.$do_module->idmodule.'_'.$mod_standard_permission->idstandard_permission.'" CHECKED onClick="set_standard_permission(\'m_'.$do_module->idmodule.'_'.$mod_standard_permission->idstandard_permission.'\',\''.$mod_standard_permission->idstandard_permission.'\')">
												</td>';
											} else {
												echo '<td>&nbsp;</td>';
											}
										}
										?>
									<?php 
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
					</div><br />
					<div class="right_200">
						<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list");?>" class="btn btn-inverse">
						<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" value="<?php echo _('Save'); ?>"/>
					</div>
					<div class="clear_float"></div>
				</form><!--/Profile Add form-->
				<?php 
				} 
				if ($mode == 'edit' && $allow_operation === true) {
					$mod_standard_permission = new ModuleStandardPermission();
					$profile_global_permissions = $do_profile->get_profile_global_permissions();
					$profile_standard_permission_rel = $do_profile->get_all_module_standard_permissions();
					$profile_module_rel = $do_profile->get_all_module_permissions();
					$e_add_profile = new Event("Profile->eventUpdateProfile");
					$e_add_profile->addParam("idprofile",$idprofile);
					echo '<form class="form-horizontal" id="Profile__eventUpdateProfile" name="Profile__eventUpdateProfile" action="/eventcontroler.php" method="post">';
					echo $e_add_profile->getFormEvent();
              ?> 
				<div class="right_200">
					<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list");?>" class="btn btn-inverse">
					<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
					<input type="submit" class="btn btn-primary" value="<?php echo _('Save'); ?>"/>
				</div>
				<div class="clear_float"></div>
				<div class="box_content_header"><h3><?php echo _('Set Global Privileges');?></h3>
					<hr class="form_hr">
					<label class="checkbox">
						<input type="checkbox" <?php echo ($profile_global_permissions[1] == 1 ? 'CHECKED':''); ?> name="global_view_all" id="global_view_all" onclick="set_global_permission('global_view_all')"> 
						<b><?php echo _('View All');?></b> 
					</label>  
					<label class="checkbox"><?php echo _('Allow to view all information');?></label>
					<label class="checkbox">
						<input type="checkbox" <?php echo ($profile_global_permissions[2] == 1 ? 'CHECKED':''); ?> name="global_addedit_all" id="global_addedit_all" onclick = "set_global_permission('global_addedit_all')"> 
						<b><?php echo _('Add/Edit/Delete All'); ?></b>
					</label>
					<label class="checkbox"><?php echo _('Allow to add/edit/delete all information');?> </label>
				</div>
				<div class="box_content_header"><h3><?php echo _('Set Privileges per module');?></h3>  
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
							$profile_standard_permission_rel = $do_profile->get_all_module_standard_permissions();
							$profile_module_rel = $do_profile->get_all_module_permissions();
							while ($do_module->next()) {
								$mod_permission_check_box = '';
								if (array_key_exists($profile_module_rel[$do_module->idmodule],$profile_module_rel)) {
									if($profile_module_rel[$do_module->idmodule] == 1) $mod_permission_check_box = 'CHECKED';
								}
							?>
							<tr>
								<td id="module_permission">
									<label class="checkbox">
										<input type="checkbox" name="mod_<?php echo $do_module->idmodule?>"
										id="mod_<?php echo $do_module->idmodule?>" onclick="set_standard_permission_by_module('<?php echo $do_module->idmodule;?>')" <?php echo $mod_permission_check_box?>> 
										<?php echo $do_module->module_label;?>
									</label>
								</td>
								<?php
								$mod_standard_permission->get_module_standard_permissions($do_module->idmodule);
								if ($mod_standard_permission->getNumRows() > 0) {
									while ($mod_standard_permission->next()) {
										if (in_array($mod_standard_permission->idstandard_permission,$standard_permissions)) {
											if ($mod_standard_permission->idstandard_permission == 1) {
												$td_id = "add_permission";
											} elseif ($mod_standard_permission->idstandard_permission == 2) {
												$td_id = "view_permission";
											} elseif ($mod_standard_permission->idstandard_permission == 3) {
												$td_id = "delete_permission";
											}
											$profile_standard_permission_checked = '';
											if (array_key_exists($profile_standard_permission_rel[$do_module->idmodule][$mod_standard_permission->idstandard_permission],$profile_standard_permission_rel)) {
												if ($profile_standard_permission_rel[$do_module->idmodule][$mod_standard_permission->idstandard_permission] == 1)
													$profile_standard_permission_checked = 'CHECKED';
											}
											echo '<td id="'.$td_id.'">
											<input style="margin-left:10px;" type="checkbox" 
											name= "m_'.$do_module->idmodule.'_'.$mod_standard_permission->idstandard_permission.'"
											id= "m_'.$do_module->idmodule.'_'.$mod_standard_permission->idstandard_permission.'"
											'.$profile_standard_permission_checked.' onClick="set_standard_permission(\'m_'.$do_module->idmodule.'_'.$mod_standard_permission->idstandard_permission.'\',\''.$mod_standard_permission->idstandard_permission.'\')"
											>
											</td>';
										} else {
											echo '<td>&nbsp;</td>';
										}
									}
									?>
								<?php 
								} else {
								?>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<?php 
								} ?>
							</tr>
							<?php 
							}
							?>
						</tbody>  
					</table>
				</div><br />
				<div class="right_200">
					<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list");?>" class="btn btn-inverse">
					<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
					<input type="submit" class="btn btn-primary" value="<?php echo _('Save'); ?>"/>
				</div>
				<div class="clear_float"></div>
				</form> <!--/ update form -->
				<?php 
				} 
				if ($allow_operation === false) {?>  
					<div class="modal-body alert-error">
					<?php echo _('You do not have permission to perform this operation');?>
					</div>
				<?php 
				}
				?>
			</div><!--/datadisplay-outer-->
		</div><!--/span9-->
	</div><!--/row-->
</div>