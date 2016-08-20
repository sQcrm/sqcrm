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
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list")?>"><?php echo _('Roles'); ?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage Roles hierarchy for users')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="row">
					<div class="col-md-12">
						<?php
						$e_add = new Event("Roles->eventAddNewRole");
						$e_add->addParam("parentrole",$_GET["parentrole"]);
						$e_add->addParam("error_page",NavigationControl::getNavigationLink($module,"roles_add"));
						$e_add->addParam("next_page",NavigationControl::getNavigationLink($module,"roles_list"));
						echo '<form class="" id="Roles__eventAddNewRole" name="Roles__eventAddNewRole" action="/eventcontroler.php" method="post">';
						echo $e_add->getFormEvent();
						?>
						<div class="form-group">  
							<label class="control-label" for="rolename"><?php echo _('Role Name')?></label>  
							<div class="controls">  
								<input type="text" class="form-control input-sm" id="rolename" name="rolename"> 
							</div>
						</div>
						<div class="form-group">  
							<div class="controls">  
								<table>
									<tr>
										<td>
											<label class="control-label" for=""><?php echo _('Available Profiles')?></label><br />
											<select name="select_from" id="select_from" multiple size = "10" class="form-control">
												<?php
												while ($do_profile->next()) {
													echo '<option value="'.$do_profile->idprofile.'">'.$do_profile->profilename.'</option>';
												}
												?>
											</select>
										</td>
										<td width="50px;" align="center"><br />
											<a href="#" class="btn btn-success btn-xs" id="profile_add_select"><i class="glyphicon glyphicon-arrow-right"></i></a>
											<br /><br />
											<a href="#" class="btn btn-success btn-xs" id="profile_remove_select"><i class="glyphicon glyphicon-arrow-left"></i></a>
										</td>
										<td>
											<label class="control-label" for=""><?php echo _('Assigned Profiles')?></label><br />
											<select name="select_to[]" id="select_to" multiple size = "10" class="form-control">
											</select>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<hr class="form_hr">
						<a href="<?php echo NavigationControl::getNavigationLink($module,"roles_list");?>" class="btn btn-default active">
						<i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
					</div>
					</form>
				</div>
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>