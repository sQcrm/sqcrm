<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* project members view page
* @author Abhik Chakraborty
*/  
?>
<br /><br />
<div id="project_permission" class="row">
	<div class="col-md-12">
		<strong>
		<?php
		echo _('Who can create task');
		?>
		</strong>
		<br />
		<input type="radio" id="task_create" name="task_create" value="1" <?php echo ($permissions['task_create'] == '1' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('Only project owner');?>
		<br />
		<input type="radio" id="task_create" name="task_create" value="2" <?php echo ($permissions['task_create'] == '2' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('All project members');?>
		<hr class="form_hr">
		
		<strong>
		<?php
		echo _('Who can edit task');
		?>
		</strong>
		<br />
		<input type="radio" id="task_edit" name="task_edit" value="1" <?php echo ($permissions['task_edit'] == '1' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('Only project owner');?>
		<br />
		<input type="radio" id="task_edit" name="task_edit" value="2" <?php echo ($permissions['task_edit'] == '2' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('Only the task owner');?>
		<br />
		<input type="radio" id="task_edit" name="task_edit" value="3" <?php echo ($permissions['task_edit'] == '3' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('All members of the project');?>
		<hr class="form_hr">
		
		<strong>
		<?php
		echo _('Who can close task');
		?>
		</strong>
		<br />
		<input type="radio" id="task_close" name="task_close" value="1" <?php echo ($permissions['task_close'] == '1' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('Only project owner');?>
		<br />
		<input type="radio" id="task_close" name="task_close" value="2" <?php echo ($permissions['task_close'] == '2' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('Only the task owner');?>
		<br />
		<input type="radio" id="task_close" name="task_close" value="3" <?php echo ($permissions['task_close'] == '3' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('All members of the project');?>
		<hr class="form_hr">
		
		<strong>
		<?php
		echo _('Who can assign members to task');
		?>
		</strong>
		<br />
		<input type="radio" id="task_assignees" name="task_assignees" value="1" <?php echo ($permissions['task_assignees'] == '1' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('Only project owner');?>
		<br />
		<input type="radio" id="task_assignees" name="task_assignees" value="2" <?php echo ($permissions['task_assignees'] == '2' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('Only the task owner');?>
		<br />
		<input type="radio" id="task_assignees" name="task_assignees" value="3" <?php echo ($permissions['task_assignees'] == '3' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('All members of the project');?>
		<hr class="form_hr">
		
		<strong>
		<?php
		echo _('Who can add members to project');
		?>
		</strong>
		<br />
		<input type="radio" id="project_members" name="project_members" value="1" <?php echo ($permissions['project_members'] == '1' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('Only project owner');?>
		<br />
		<input type="radio" id="project_members" name="project_members" value="2" <?php echo ($permissions['project_members'] == '2' ? 'CHECKED':'');?>>&nbsp;&nbsp;<?php echo _('All members of the project');?>
		<hr class="form_hr">
		
		<strong>
		<?php
		echo _('Who can change permission for this project (By default project assigned to user\'s can change)');
		?>
		</strong>

		<div class="row" id="permission-changer-selector-block">
			<div class="col-xs-12">
				<div class="col-xs-4">
					<?php
					if (count($permission_changer_data['not_assigned']) > 0) {
						echo '<br /><br />';
						echo '<select name="permission-changer-selector" id="permission-changer-selector" class="form-control input-sm">';
						echo '<option value="">'._('select user').'</option>';
						foreach ($permission_changer_data['not_assigned'] as $key=>$val) {
							$color = '-';
							if ($val['user_avatar'] != '') {
								$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
							} else {
								$avatar = '-';
								$color = CommonUtils::get_random_color();
							}
							$opt_value = $sqcrm_record_id.'::'.$val['iduser'].'::'.$val['user_name'].'::'.$val['firstname'].'::'.$val['lastname'].'::'.$val['email'].'::'.$avatar.'::'.$color;
							echo '<option value="'.$opt_value.'">'.$val['firstname'].' '.$val['lastname'].'</option>';
						}
						echo '</select>';
						echo '<br /><br />';
					}
					?>
				</div>
			</div>
		</div>
		<?php
		echo '<div class="row"><div class="col-xs-12"><div class="col-xs-9" id="existing-permission-changer">';
		if (is_array($permission_changer_data['assigned']) && count($permission_changer_data['assigned']) > 0) {
			foreach($permission_changer_data['assigned'] as $key=>$val) {
				echo '<div class="col-xs-3" style="margin-top:14px;" id="existing-permission-changer-'.$val['iduser'].'" >';
				if ($val['user_avatar'] != '') {
					$remove_opt = '';
					$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
					$remove_opt = '<a href="#" onclick="removeProjectPermissionAdder(\''.$sqcrm_record_id.'\',\''.$val['iduser'].'\'); return false;"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a>';
					echo '<div class="circular_35" title= "'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')" style="float:left;background-image: url(\''.$avatar.'\')">'.$remove_opt.'</div>';
				} else {
					$remove_opt = '';
					$remove_opt = '<a href="#" onclick="removeProjectPermissionAdder(\''.$sqcrm_record_id.'\',\''.$val['iduser'].'\'); return false;"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a>';
					$color = CommonUtils::get_random_color();
					$initials = strtoupper($val['firstname'][0].$val['lastname'][0]);
					echo '<div style= "float:left;background-color:'.$color.'" class="circular_35" data-profile-initials="'.$initials.'" title= "'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')">'.$remove_opt.'</div>';
				}
				echo '</div>';
			}
		}
		echo '</div></div></div>';
		?>
		<br />
		<hr class="form_hr">
		<?php
		$existing_permission_changer = (count($permission_changer) > 0 ? implode(',',$permission_changer) : '');
		?>
		<input type="hidden" name="permission_changer" id="permission_changer" value="<?php echo $existing_permission_changer;?>">
		<input type="hidden" name="idpermission" id="idpermission" value="<?php echo $permissions['id'];?>">
		<div class="col-xs-3" id="add-project-permissions-button">
			<input type="button" class="btn btn-primary" id="add-project-permissions" value="<?php echo _('save')?>"/>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="permission-changer-remove-confirm">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to perform this operation ? If yes make sure to click on save after removing the user');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Yes')?>"/>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
	
		$('#detail_view_section').on('change', '#permission-changer-selector', function(){
			var optVal = $('#detail_view_section #permission-changer-selector').val() ;
			if (optVal) {
				var memberData = optVal.split('::');
				var memberHtml = '<div class="col-xs-3" style="margin-top:14px;" id="existing-permission-changer-'+memberData[1]+'">';
				
				if (memberData[6] == '-') {
					var initials = memberData[3].charAt(0)+''+memberData[4].charAt(0);
					memberHtml += '<div style="float:left;background-color:'+memberData[7]+'" data-profile-initials= "'+initials.toUpperCase()+'" class="circular_35" title="'+memberData[3]+' '+memberData[4]+'('+memberData[2]+')">';
					memberHtml += '<a href="#" onclick="removeProjectPermissionAdder(\''+memberData[0]+'\',\''+memberData[1]+'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a>';
				} else {
					memberHtml += '<div class="circular_35" title="'+memberData[3]+' '+memberData[4]+'('+memberData[2]+')" style="float:left;background-image:url(\''+memberData[6]+'\')">';
					memberHtml += '<a href="#" onclick="removeProjectPermissionAdder(\''+memberData[0]+'\',\''+memberData[1]+'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a>';
				}
				
				memberHtml += '</div>';
				memberHtml += '</div>';
				
				var permissionChanger = $('#detail_view_section #permission_changer').val();
				permissionChanger += permissionChanger ? ','+memberData[1] : memberData[1];
				$('#detail_view_section #permission_changer').val(permissionChanger);
				
				$('#detail_view_section #existing-permission-changer').append(memberHtml);
				$('#detail_view_section #permission-changer-selector option:selected').remove();
			}
		});
		
		$('#detail_view_section').on('click', '#add-project-permissions', function(e) {
			var task_create = $('input[name=task_create]:checked', '#detail_view_section').val(),
				task_edit = $('input[name=task_edit]:checked', '#detail_view_section').val(),
				task_close = $('input[name=task_close]:checked', '#detail_view_section').val(),
				task_assignees = $('input[name=task_assignees]:checked', '#detail_view_section').val(),
				project_members = $('input[name=project_members]:checked', '#detail_view_section').val(),
				permission_changer = $('#detail_view_section #permission_changer').val(),
				id = $('#detail_view_section #idpermission').val(),
				formData = {
					task_create: task_create,
					task_edit: task_edit,
					task_close: task_close,
					task_assignees: task_assignees,
					project_members: project_members,
					permission_changer: permission_changer,
					id: id
				};
			
			$('#detail_view_section #add-project-permissions-button').html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			
			var qry_string = '&idproject=<?php echo $sqcrm_record_id;?>';
			$.ajax({
				type: 'POST',
				data : formData,
				<?php
				$e_add_per = new Event("Project->eventAddProjectPermission");
				$e_add_per->setEventControler("/ajax_evctl.php");
				$e_add_per->setSecure(false);
				?>
				url: "<?php echo $e_add_per->getUrl(); ?>"+qry_string,
				success: function(data) {
					var result = JSON.parse(data);
					$('#detail_view_section #add-project-permissions-button').html('<input type="button" class="btn btn-primary" id="add-project-permissions" value="'+SAVE_LW+'"/>');
					
					if (result.status === 'ok') {
						$('#detail_view_section #idpermission').val(result.id);
						display_js_success(result.message,'js_errors');
					} else {
						display_js_error(result.message,'js_errors');
					}
				}
			});
		});
	});
	
	function removeProjectPermissionAdder(idproject,iduser) {
		$("#detail_view_section #permission-changer-remove-confirm").modal('show');
		$("#detail_view_section #permission-changer-remove-confirm .btn-primary").off('click');
		$("#detail_view_section #permission-changer-remove-confirm .btn-primary").click(function() {
			$("#detail_view_section #permission-changer-remove-confirm").modal('hide');
			var permissionChanger = $('#detail_view_section #permission_changer').val().split(',');
			permissionChanger = $.grep(permissionChanger, function(n,i) {
				return n != iduser;
			});
			
			var elementToBeRemoved = 'existing-permission-changer-'+iduser;
			$('#detail_view_section #'+elementToBeRemoved).remove();
			
			if (permissionChanger) {
				$('#detail_view_section #permission_changer').val(permissionChanger.join(','));
			} else {
				$('#detail_view_section #permission_changer').val('');
			}
		});
	}
	
</script>