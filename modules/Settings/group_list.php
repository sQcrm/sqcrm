<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Group listing page
* @author Abhik Chakraborty
*/  

$do_group = new Group();
$do_group->get_all_groups();
?>
<div class="container-fluid">
	<div class="row">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="col-md-9">
			<div class="box_content">
				<ol class="breadcrumb">
					<li class="active"><?php echo _('Settings')?></li>
					<li><a href="<?php echo NavigationControl::getNavigationLink($module,"group_list")?>"><?php echo _('Group');?></a></li>
				</ol>
				<p class="lead"><?php echo _('Manage Group and users in the group.')?></p> 
			</div>
			<div class="datadisplay-outer">
				<h2><small><?php echo _('Group List');?></small></h2>
				<a href="<?php echo NavigationControl::getNavigationLink($module,"group_add");?>" class="btn btn-primary">
				<i class="glyphicon glyphicon-plus"></i> <?php echo _('Add New');?></a>
				<br /><br />
				<div class="clear_float"></div>
				<table class="datadisplay">  
					<thead>  
						<tr>  
							<th>#</th>  
							<th><?php echo _('Group Name');?></th>  
							<th><?php echo _('Description');?></th>  
							<th><?php echo _('Action')?></th>  
						</tr>  
					</thead>        
					<tbody>  
						<?php 
						$cnt = 0;
						if ($do_group->getNumRows() > 0) {
							while ($do_group->next()) {
						?>
						<tr>  
							<td><?php echo ++$cnt;?></td>  
							<td>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"group_detail",$do_group->idgroup)?>">
								<?php echo $do_group->group_name;?>
								</a>
							</td>  
							<td><?php echo nl2br($do_group->description);?></td>  
							<td>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"group_edit",$do_group->idgroup)?>" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
								<a href="#" class="btn btn-primary btn-xs" 
								onclick="return_delete_group_confirm(<?php echo $do_group->idgroup;?>);">
								<i class="glyphicon glyphicon-trash"></i>
								</a>
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
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="delete_confirm_group">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to delete the records.');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Delete')?>"/>
			</div>
		</div>
	</div>
</div>

<script>
function return_delete_group_confirm(idgroup) {
	$("#delete_confirm_group").modal('show');
	$("#delete_confirm_group .btn-primary").off('click');
	$("#delete_confirm_group .btn-primary").click(function() {
		$("#delete_confirm_group").modal('hide');
		var href = '/popups/delete_group_modal?classname=Group&m=Settings&referrar=group_list&sqrecord='+idgroup;
		if (href.indexOf('#') == 0) {
			$(href).modal('open');
		} else {
			$.get(href, function(data) {
				//ugly heck to prevent the content getting append when opening the same modal multiple time
				$("#delete_group_transfer_data").html(''); 
				$("#delete_group_transfer_data").hide();
				$("#delete_group_transfer_data").attr("id","ugly_heck");
				$('<div class="modal fade" tabindex="-1" role="dialog" id="delete_group_transfer_data">' + data + '</div>').modal();
			}).success(function() { $('input:text:visible:first').focus(); });
		}
	});
}
</script>