<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* project members entry view
* @author Abhik Chakraborty
*/  
?>
<?php 
if (is_array($members) && count($members) >0 && count($members['member']) > 0) {
?>
<li class="list-group-item" id="project-existing-members">
	<strong><?php echo _('Project members');?></strong>
	<br />
	<hr class="form_hr">
	<div class="row">
		<?php
		foreach ($members['member'] as $key=>$val) { ?>
		<div class="col-xs-3" style="margin-top:14px;" id="existing-member-<?php echo $val['iduser'];?>">
			<?php
			$existing_members[] = $val['iduser'];
			if ($val['user_avatar'] != '') {
				$remove_opt = '';
				$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
				if ($val['assigned_to'] == 0) {
					$remove_opt = '<a href="#" onclick="removeProjectMember(\''.$sqcrm_record_id.'\',\''.$val['iduser'].'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a>';
				}
				echo '<div class="circular_35" title= "'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')" style="float:left;background-image: url(\''.$avatar.'\')">'.$remove_opt.'</div>';
			} else {
				$remove_opt = '';
				if ($val['assigned_to'] == 0) {
					$remove_opt = '<a href="#" onclick="removeProjectMember(\''.$sqcrm_record_id.'\',\''.$val['iduser'].'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a>';
				}
				$color = CommonUtils::get_random_color();
				$initials = strtoupper($val['firstname'][0].$val['lastname'][0]);
				echo '<div style= "float:left;background-color:'.$color.'" class="circular_35" data-profile-initials="'.$initials.'" title= "'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')">'.$remove_opt.'</div>';
			}
			?>
		</div>
		<?php 
		}
		?>
	</div>
	<br />
</li>
<?php } ?>

<?php 
if (is_array($members) && count($members) >0 && count($members['not_assigned']) > 0) {
?>
<li class="list-group-item" id="project-add-members">
	<strong><?php echo _('Add members to this project');?></strong>
	<br />
	<hr class="form_hr">
	<div class="row">
		<div class="col-xs-12">
			<div class="col-xs-8">
				<select name="project-member-selector" id="project-member-selector" class="form-control input-sm">
				<?php
				foreach ($members['not_assigned'] as $key=>$val) { 
					$color = '-';
					if ($val['user_avatar'] != '') {
						$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
					} else {
						$avatar = '-';
						$color = CommonUtils::get_random_color();
					}
					$opt_value = $sqcrm_record_id.'::'.$val['iduser'].'::'.$val['user_name'].'::'.$val['firstname'].'::'.$val['lastname'].'::'.$val['email'].'::'.$avatar.'::'.$color;
				?>
					<option value="<?php echo $opt_value;?>"><?php echo $val['firstname'].' '.$val['lastname'];?></option>
				<?php
				}
				?>
				</select>
			</div>
			<div class="col-xs-3" id="add-project-members-button">
				<input type="button" class="btn btn-primary" id="add-project-members" value="<?php echo _('add')?>"/>
			</div>
		</div>
	</div>
</li>
<?php } ?>

<li class="list-group-item" id="project-pending-requests">
<?php 
$show_pending_members = false;
$pending_req_title = 'display:none;';
if (is_array($members) && count($members) >0 && count($members['req_sent']) > 0) {
	$pending_req_title = 'display:block;';
	$show_pending_members = true;
}
?>
	<div id="project-pending-requests-title" style="<?php echo $pending_req_title;?>">
		<strong><?php echo _('Pending requests');?></strong>
		<hr class="form_hr">
	</div>
	<div class="row" id="project-pending-requests-row">
		<?php
		if (true === $show_pending_members) {
			foreach ($members['req_sent'] as $key=>$val) { ?>
			<div class="col-xs-3" style="margin-top:14px;" id="pending-member-<?php echo $val['iduser'];?>">
				<?php
				if ($val['user_avatar'] != '') {
					$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
					echo '<div class="circular_35" title= "'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')" style="float:left;background-image: url(\''.$avatar.'\')"><a href="#" onclick="removePendingRequest(\''.$sqcrm_record_id.'\',\''.$val['iduser'].'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a></div>';
				} else {
					$color = CommonUtils::get_random_color();
					$initials = strtoupper($val['firstname'][0].$val['lastname'][0]);
					echo '<div style= "float:left;background-color:'.$color.'" class="circular_35" data-profile-initials="'.$initials.'" title= "'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')"><a href="#" onclick="removePendingRequest(\''.$sqcrm_record_id.'\',\''.$val['iduser'].'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a></div>';
				}
				?>
			</div>
			<?php 
			}
		}
		?>
	</div>
	<br />
</li>

<?php 
if (is_array($members) && count($members) >0 && count($members['req_rejected']) > 0) {
?>
<li class="list-group-item" id="project-req-rejected-members">
	<strong><?php echo _('Request rejected');?></strong>
	<br />
	<hr class="form_hr">
	<div class="row">
		<?php
		foreach ($members['req_rejected'] as $key=>$val) { ?>
		<div class="col-xs-3" style="margin-top:14px;" id="rejected-member-<?php echo $val['iduser'];?>">
			<?php
			if ($val['user_avatar'] != '') {
				$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
				echo '<div class="circular_35" title= "'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')" style="float:left;background-image: url(\''.$avatar.'\')"><a href="#" onclick="removeRejectedRequest(\''.$sqcrm_record_id.'\',\''.$val['iduser'].'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a></div>';
			} else {
				$color = CommonUtils::get_random_color();
				$initials = strtoupper($val['firstname'][0].$val['lastname'][0]);
				echo '<div style= "float:left;background-color:'.$color.'" class="circular_35" data-profile-initials="'.$initials.'" title= "'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')"><a href="#" onclick="removeRejectedRequest(\''.$sqcrm_record_id.'\',\''.$val['iduser'].'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a></div>';
			}
			?>
		</div>
		<?php 
		}
		?>
	</div>
	<br />
</li>
<?php } ?>