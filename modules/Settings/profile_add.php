<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Profile listing page
* @author Abhik Chakraborty
*/  

$do_profile = new Profile();
    
?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="#"><?php echo _('Profile');?></a></h3>
				<p><?php echo _('Manage Profile and access to different modules and fields')?></p> 
			</div>
			<div class="row-fluid">
				<?php
				$e_add = new Event("Profile->eventAddNewProfileStep1");
				$e_add->addParam("error_page",NavigationControl::getNavigationLink($module,"profile_add"));
				$e_add->addParam("next_page",NavigationControl::getNavigationLink($module,"profile_permissions"));
				echo '<form class="form-horizontal" id="Profile__eventAddNewProfileStep1" name="Profile__eventAddNewProfileStep1" action="/eventcontroler.php" method="post">';
				echo $e_add->getFormEvent();
				?>
				<div class="control-group">  
					<label class="control-label" for="profilename"><?php echo _('Profile Name')?></label>  
					<div class="controls">  
						<input type="text" class="input-xlarge-100" id="profilename" name="profilename"> 
					</div>
				</div>
				<div class="control-group">  
					<label class="control-label" for="description"><?php echo _('Description');?></label>  
					<div class="controls">  
						<textarea class="input-xlarge" id="description" name="description" rows="3"></textarea>  
					</div>  
				</div>  
				<div class="form-actions">  
					<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list");?>" class="btn btn-inverse">
					<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
					<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
				</div>
				</form>	
			</div><!--/row-->
		</div><!--/span-->
	</div><!--/row-->
</div>