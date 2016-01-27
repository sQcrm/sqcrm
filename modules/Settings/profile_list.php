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
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list")?>"><?php echo _('Profile');?></a></h3>
				<p><?php echo _('Manage Profile and access to different modules and fields')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Profile List');?></h4></div>
				<div class="right_300">
					<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_add");?>" class="btn btn-primary">
					<i class="icon-white icon-plus"></i> Add New</a>
				</div>
				<div class="clear_float"></div>
				<table class="datadisplay">  
					<thead>  
						<tr>  
							<th>#</th>  
							<th><?php echo _('Profile Name');?></th>  
							<th><?php echo _('Description');?></th>  
							<th><?php echo _('Action')?></th>  
						</tr>  
					</thead>        
					<tbody>  
						<?php 
						$cnt = 0;
						if ($do_profile->getNumRows() > 0) {
							while ($do_profile->next()) {
							?>
						<tr>  
							<td><?php echo ++$cnt;?></td>  
							<td>
								<?php if ($do_profile->editable == 1) { ?>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_details",$do_profile->idprofile)?>">
								<?php echo $do_profile->profilename;?>
								</a>
								<?php } else { echo $do_profile->profilename; } ?>
							</td>  
							<td><?php echo nl2br($do_profile->description);?></td>  
							<td>
								<?php if ($do_profile->editable == 1) { ?>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_permissions",$do_profile->idprofile)?>" class="btn btn-primary btn-mini"><i class="icon-white icon-edit"></i></a>
								<a href="#" class="btn btn-primary btn-mini bs-prompt" 
									onclick="return_delete_profile_confirm(<?php echo $do_profile->idprofile;?>,'Profile','<?php echo $module;?>','profile_list');">
									<i class="icon-white icon-trash"></i>
								</a>
								<?php } else { echo '&nbsp;' ;} ?>
							</td>  
						</tr>  
						<?php
							}
						}
						?>  
					</tbody>  
				</table>  
			</div>
		</div><!--/span-->
	</div><!--/row-->
</div>
<div class="modal hide fade" id="delete_confirm">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
	</div>
	<div class="modal-body">
		<?php echo _('Are you sure you want to delete the profile.');?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Delete');?>"/>
	</div>
</div>