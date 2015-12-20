<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Profile listing page
* @author Abhik Chakraborty
*/  

$do_profile = new Profile();
$do_profile->get_all_profiles();
    
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list")?>"><?php echo _('Roles');?></a></h3>
				<p><?php echo _('Manage Roles hierarchy for users')?></p> 
			</div>
			<div class="row-fluid">
				<div class="datadisplay-outer">
					<?php
					$e_add = new Event("Roles->eventAddNewRole");
					$e_add->addParam("parentrole",$_GET["parentrole"]);
					$e_add->addParam("error_page",NavigationControl::getNavigationLink($module,"roles_add"));
					$e_add->addParam("next_page",NavigationControl::getNavigationLink($module,"roles_list"));
					echo '<form class="form-horizontal" id="Roles__eventAddNewRole" name="Roles__eventAddNewRole" action="/eventcontroler.php" method="post">';
					echo $e_add->getFormEvent();
					?>
					<div class="control-group">  
						<label class="control-label" for="rolename"><?php echo _('Role Name')?></label>  
						<div class="controls">  
							<input type="text" class="input-xlarge-100" id="rolename" name="rolename"> 
						</div>
					</div>
					<div class="control-group">  
						<div class="controls">  
							<table>
								<tr>
									<td>
										<label class="control-label" for=""><?php echo _('Available Profiles')?></label><br />
										<select name="select_from" id="select_from" multiple size = "5">
											<?php
											while ($do_profile->next()) {
												echo '<option value="'.$do_profile->idprofile.'">'.$do_profile->profilename.'</option>';
											}
											?>
										</select>
									</td>
									<td width="50px;" align="center"><br />
										<a href="#" class="btn btn-success btn-mini-1" id="profile_add_select"><i class="icon-white icon-arrow-right"></i></a>
										<br /><br />
										<a href="#" class="btn btn-success btn-mini-1" id="profile_remove_select"><i class="icon-white icon-arrow-left"></i></a>
									</td>
									<td>
										<label class="control-label" for=""><?php echo _('Assigned Profiles')?></label><br />
										<select name="select_to[]" id="select_to" multiple size = "5">
										</select>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="form-actions">  
						<a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list");?>" class="btn btn-inverse">
						<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
					</div>
				</form>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>