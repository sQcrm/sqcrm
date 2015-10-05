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
	<div class="row-fluid">
		<?php include_once("modules/Settings/settings_leftmenu.php");?>
		<div class="span9" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo _('Settings')?> > <a href="<?php echo NavigationControl::getNavigationLink($module,"group_list")?>"><?php echo _('Group');?></a></h3>
				<p><?php echo _('Manage Group and users in the group.')?></p> 
			</div>
			<div class="datadisplay-outer">
				<div class="left_300"><h4><?php echo _('Group List');?></h4></div>
				<div class="right_300">
					<a href="<?php echo NavigationControl::getNavigationLink($module,"group_add");?>" class="btn btn-primary">
					<i class="icon-white icon-plus"></i> Add New</a>
				</div>
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
								<a href="<?php echo NavigationControl::getNavigationLink($module,"group_edit",$do_group->idgroup)?>" class="btn btn-primary btn-mini"><i class="icon-white icon-edit"></i></a>
								<a href="#" class="btn btn-primary btn-mini bs-prompt" 
								onclick="return_delete_group_confirm(<?php echo $do_group->idgroup;?>);">
								<i class="icon-white icon-trash"></i>
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
<div class="modal hide" id="delete_confirm_group">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
	</div>
	<div class="modal-body">
		<?php echo _('Are you sure you want to delete the records.');?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Delete');?>"/>
	</div>
</div>
<script>
function return_delete_group_confirm(idgroup) {
	$("#delete_confirm_group").modal('show');
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
				$('<div class="modal hide" id="delete_group_transfer_data">' + data + '</div>').modal();
			}).success(function() { $('input:text:visible:first').focus(); });
		}
	});
}
</script>