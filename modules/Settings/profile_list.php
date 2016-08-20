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
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"profile_list")?>"><?php echo _('Profile');?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage Profile and access to different modules and fields')?></p> 
			</div>
			<div class="datadisplay-outer">
				<h2><small><?php echo _('Profile List');?></small></h2>
				<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_add");?>" class="btn btn-primary">
				<i class="glyphicon glyphicon-plus"></i> <?php echo _('Add New');?></a>
				<br /><br />
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
								<a href="<?php echo NavigationControl::getNavigationLink($module,"profile_permissions",$do_profile->idprofile)?>" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
								<a href="#" class="btn btn-primary btn-xs" 
									onclick="return_delete_profile_confirm(<?php echo $do_profile->idprofile;?>,'Profile','<?php echo $module;?>','profile_list');">
									<i class="glyphicon glyphicon-trash"></i>
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

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="delete_confirm">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to delete the profile.');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
			</div>
		</div>
	</div>
</div>