<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* data sharing across modules
* @author Abhik Chakraborty
*/  

$mod_datashare_rel = new ModuleToDatashareRelation();
$datashare_permission = new DatasharePermission();
$datashare_permission->getAll();
$ds_permission_array = array();
while ($datashare_permission->next()) {
	$ds_permission_array[$datashare_permission->iddatashare_standard_permission] = $datashare_permission->permission_name;
}
    
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"datashare_details");?>"><?php echo _('Sharing Rules');?></a></h3>
				<p><?php echo _('Manage data sharing across different modules');?></p> 
			</div>
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<div class="left_600">
						<h3><?php echo _('Sharing rules for modules ');?></h3>
					</div>
					<!--Display section -->
					<div class="right_200" id="ds_display">
						<a href="#" class="btn btn-primary" id="update_datashare"><i class="icon-white icon-edit"></i> <?php echo _('Update');?></a>
					</div>
					<div class="clear_float"></div>
						<table class="datadisplay" id="datashare_disp">  
							<tbody>  
							<?php
							$mod_datashare_rel->get_module_datashare_permissions();
							while ($mod_datashare_rel->next()) {
							?>
								<tr>
									<td><?php echo $mod_datashare_rel->module_label ;?></td>
									<td><?php echo $mod_datashare_rel->permission_name ;?></td>
									<td><?php echo $mod_datashare_rel->description.' '.$mod_datashare_rel->module_label ;?></td>
								</tr>   
							<?php } ?>   
							</tbody>
						</table>
						<!--/Display section -->

						<!--Edit section -->
						<?php
						$e_update = new Event("ModuleToDatashareRelation->eventUpdateModuleDataShareRel");
						echo '<form class="form-horizontal" id="ModuleToDatashareRelation__eventUpdateModuleDataShareRel" name="ModuleToDatashareRelation__eventUpdateModuleDataShareRel" action="/eventcontroler.php" method="post">';
						$e_update->addParam("next_page",NavigationControl::getNavigationLink($module,"datashare_details"));
						echo $e_update->getFormEvent();
						$mod_datashare_rel->get_module_datashare_permissions();
						?>
						<div class="right_200" id="ds_edit_top" style="display:none;">
							<a href="<?php echo NavigationControl::getNavigationLink($module,"datashare_details");?>" class="btn btn-inverse">
							<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
							<input type="submit" class="btn btn-primary" value="<?php echo _('Save'); ?>"/>
						</div>
						<div class="clear_float"></div><br />
						<table class="datadisplay" style="display:none;" id="datashare_edit">  
						<?php
						while ($mod_datashare_rel->next()) {
						?>
							<tr>
								<td><?php echo $mod_datashare_rel->module_label ;?></td>
								<td colspan=2>
								<?php
								echo '<select name="mod_'.$mod_datashare_rel->idmodule.'">';
								foreach ($ds_permission_array as $key=>$val) {
									$selected = '';
									if ($key == $mod_datashare_rel->iddatashare_standard_permission) $selected = 'SELECTED' ;
									echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
								}
								echo '</select>';
								?>
								</td>
							</tr>   
						<?php } ?>
						</table><br />
						<div class="right_200" id="ds_edit_bottom" style="display:none;">
							<a href="<?php echo NavigationControl::getNavigationLink($module,"datashare_details");?>" class="btn btn-inverse">
								<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?>
							</a>  
							<input type="submit" class="btn btn-primary" value="<?php echo _('Save'); ?>"/>
						</div>
					</form> 
					<br />
					<!--/Edit section --> 
				</div>
			</div><!--/datadisplay-outer-->
		</div><!--/span-->
	</div><!--/row-->
</div>    