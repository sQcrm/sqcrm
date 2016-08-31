<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Profile listing page
* @author Abhik Chakraborty
*/  

$do_profile = new Profile();
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
						<?php
						$e_add = new Event("Profile->eventAddNewProfileStep1");
						$e_add->addParam("error_page",NavigationControl::getNavigationLink($module,"profile_add"));
						$e_add->addParam("next_page",NavigationControl::getNavigationLink($module,"profile_permissions"));
						echo '<form class="" id="Profile__eventAddNewProfileStep1" name="Profile__eventAddNewProfileStep1" action="/eventcontroler.php" method="post">';
						echo $e_add->getFormEvent();
						?>
						<div class="form-group">  
							<label class="control-label" for="profilename"><?php echo _('Profile Name')?></label>  
							<div class="controls">  
								<input type="text" class="form-control input-sm" id="profilename" name="profilename"> 
							</div>
						</div>
						<div class="form-group">  
							<label class="control-label" for="description"><?php echo _('Description');?></label>  
							<div class="controls">  
								<textarea class="form-control input-sm" id="description" name="description" rows="3"></textarea>  
							</div>  
						</div> 
						<hr class="form_hr">
						<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list");?>" class="btn btn-default active">
						<i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Cancel');?></a>  
						<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
						</div>
						</form>	
					</div><!--/row-->
				</div>
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>