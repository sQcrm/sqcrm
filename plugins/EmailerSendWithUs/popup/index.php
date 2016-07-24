<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* @author Abhik Chakraborty
*/
use sendwithus\API;
require_once(BASE_PATH.'/plugins/EmailerSendWithUs/libs/sendwithus/vendor/autoload.php');
$emailer = new EmailerSendWithUs();
$api_key = $emailer->get_api_key();

$entity_selected = false ;
$templates_found = false;
$groups_found = false;
$api = new API($api_key);

// get the templates
$templates = $api->emails();
$templates_array = array();
if (is_array($templates) && count($templates) > 0) { 
	foreach ($templates as $key=>$templateObj) {
		if (property_exists($templateObj,'name') && property_exists($templateObj,'id')) {
			$templates_array[$templateObj->id] = $templateObj->name;
		}
	}
	$templates_found = true;
}

//get the groups, sending email to a selected group feature is not added yet
/*$groups = $api->list_groups();
$groups_array = array();
if (is_array($groups->groups) && count($groups->groups) > 0) { 
	foreach ($groups->groups as $key=>$groupsObj) {
		$groups_array[$groupsObj->id] = $groupsObj->name;
	}
	$groups_found = true;
}
*/
$ids = array();
if ($_REQUEST['chk']) {
	$ids = $_REQUEST['chk'];
}



if (count($ids) > 0) {
	$entity_selected = true;
}


?>
<div id="message"></div>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<h3><?php echo _('sendwithus emailer');?></h3>
</div>
<div class="modal-body">
	<div class="box_content">
		<input type="hidden" id="ids" name="ids" value="<?php echo (true === $entity_selected ? implode(',',$ids) : '');?>">
		<?php
		if (true === $entity_selected) {
		?>
		<!--<div class="alert alert-info">
		<?php
		//echo _('Create a group and save the selected contacts in to the group');
		?>
		</div>
		<div class="controls">
			<label class="control-label" for="group_name_create"><?php echo _('Group Name');?></label>
			<input type="text" name="group_name_create" id="group_name_create" class="input-xlarge-100">
		</div>
		<input type="submit" class="btn btn-primary" id="create-group-and-save-entity" value="<?php echo _('save')?>"/>
		<hr class="form_hr">-->
		<?php
		} 
		if (true === $entity_selected && true === $groups_found) {
		?>
		<div class="alert alert-info">
		<?php
		echo _('Select an existing group and save the selected contacts in to the group');
		?>
		</div>
		<div class="controls">
			<label class="control-label" for="group_name_select"><?php echo _('Select an existing group');?></label>
			<select name="group_name_select" id="group_name_select">
			<?php
			echo '<option value="0">'._('Pick One').'</option>';
			foreach ($groups_array as $key=>$val) {
				echo '<option value="'.$key.'">'.$val.'</option>';
			}
			?>
			</select>
		</div>
		<input type="submit" class="btn btn-primary" id="select-group-and-save-entity" value="<?php echo _('save')?>"/>
		<hr class="form_hr">
		<?php
		}
		if (true === $entity_selected && true === $templates_found) {
		?>
		<div class="alert alert-info">
		<?php
		echo _('Select an email template and send email to the selected contacts');
		echo '<br />';
		echo _('The supported template placeholders are first_name,last_name so make sure if you have some placeholders in the template you should have the supported one');
		?>
		</div>
		<div class="controls">
			<label class="control-label" for="template_name_select"><?php echo _('Select a template');?></label>
			<select name="template_name_select" id="template_name_select">
			<?php
			echo '<option value="0">'._('Pick One').'</option>';
			foreach ($templates_array as $key=>$val) {
				echo '<option value="'.$key.'">'.$val.'</option>';
			}
			?>
			</select>
		</div>
		<input type="submit" class="btn btn-primary" id="select-template-send-email" value="<?php echo _('send')?>"/>
		<hr class="form_hr">
		<?php
		}
		
		if (true === $groups_found && true === $templates_found) {
		?>
		<div class="alert alert-info">
		<?php
		echo _('Select an email template and a group and send the email to that group members');
		?>
		</div>
		<div class="controls">
			<label class="control-label" for="template_name_select_with_group"><?php echo _('Select a template');?></label>
			<select name="template_name_select_with_group" id="template_name_select_with_group">
			<?php
			echo '<option value="0">'._('Pick One').'</option>';
			foreach ($templates_array as $key=>$val) {
				echo '<option value="'.$key.'">'.$val.'</option>';
			}
			?>
			</select>
			<label class="control-label" for="group_name_select_with_template"><?php echo _('Select a group');?></label>
			<select name="group_name_select_with_template" id="group_name_select_with_template">
			<?php
			echo '<option value="0">'._('Pick One').'</option>';
			foreach ($groups_array as $key=>$val) {
				echo '<option value="'.$key.'">'.$val.'</option>';
			}
			?>
			</select>
		</div>
		<input type="submit" class="btn btn-primary" id="select-group-template-send-email" value="<?php echo _('send')?>"/>
		<hr class="form_hr">
		<?php
		}
		?>
		
	</div>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>
</div>
<script src="/plugins/EmailerSendWithUs/asset/i18n_message.js"></script>
<script>
$(document).ready(function() {
	
	/**
	* create group and save selected contacts to this group
	*/
	$('#create-group-and-save-entity').click( function() {
		var groupNameToCreate = $('#group_name_create').val();
		if (groupNameToCreate == '') {
			display_js_error(PLUGIN_SWU_ADD_GROUP_NAME,'message');
			return false ;
		} 
		
		var special_characters = "!@#$%^&*()+=[]\\\';,{}|\";<>?";
		var cnt = 0 ;
		
		for (var i=0 ;i<groupNameToCreate.length;i++) {
			if (special_characters.indexOf(groupNameToCreate.charAt(i)) != -1) {
				cnt++;
			}
		}
		
		if (cnt > 0) {
			display_js_error(PLUGIN_SWU_SPECIAL_CHAR_NOT_ALLOWED_GROUP_NAME,'message');
			return false ;
		}
		
		var ids = $('#ids').val();
		var formData = {
			"ids":ids,
			"groupName":groupNameToCreate
		};
		console.log(formData);
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("EmailerSendWithUs->eventCreateGroupAndSaveContacts") ;
			$e_event->setEventControler("/ajax_evctl.php") ;
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: formData,
			beforeSubmit: function() {
			
			},
			success: function(res) {
				if (res.trim() == '1') {
					display_js_success(PLUGIN_SWU_GROUP_CREATED_CNT_SAVED,'message');
				} else {
					display_js_error(res,'message');
				}
			}
		});
	});
	
	/**
	* select an existing group and save the selected contacts into the group
	* @scope future
	*/
	$('#select-group-and-save-entity').click( function() {
		var groupId = $('#group_name_select').val();
		if (groupId == '0') {
			display_js_error(PLUGIN_SWU_SELECT_A_GROUP,'message');
			return false;
		}
		
		var ids = $('#ids').val();
		var formData = {
			"ids":ids,
			"groupId":groupId
		};
		
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("EmailerSendWithUs->evenSaveContactsToExistingGroup") ;
			$e_event->setEventControler("/ajax_evctl.php") ;
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: formData,
			beforeSubmit: function() {
			
			},
			success: function(res) {
				if (res.trim() == '1') {
					display_js_success(PLUGIN_SWU_CNT_SAVED_IN_GROUP,'message');
				} else {
					display_js_error(res,'message');
				}
			}
		});
	});
	
	/**
	* select an existing template and send email to the selected contacts
	*/
	$('#select-template-send-email').click( function() {
		var templateId = $('#template_name_select').val();
		var ids = $('#ids').val();
		
		if (templateId == '0') {
			display_js_error(PLUGIN_SWU_SELECT_TEMPLATE_BEFORE_SEND,'message');
			return false;
		}
		
		var formData = {
			"ids":ids,
			"templateId":templateId
		};
		
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("EmailerSendWithUs->evenSendEmailWithTemplate") ;
			$e_event->setEventControler("/ajax_evctl.php") ;
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: formData,
			beforeSubmit: function() {
			
			},
			success: function(res) {
				if (res.trim() == '1') {
					display_js_success(PLUGIN_SWU_EMAIL_SENT_TO_SELECTED_CNT,'message');
				} else if(res.trim() == '2') {
				} else {
					display_js_error(res,'message');
				}
			}
		});
	});
	
	/**
	* select a group and send email to the group contacts by selecting a template
	* @scope future
	*/
	$('#select-group-template-send-email').click( function() {
		var templateId = $('#template_name_select_with_group').val();
		var groupId = $('#group_name_select_with_template').val();
		
		if (templateId == '0') {
			display_js_error(PLUGIN_SWU_SELECT_TEMPLATE_BEFORE_SEND,'message');
			return false;
		}
		
		if (groupId == '0') {
			display_js_error(PLUGIN_SWU_SELECT_A_GROUP,'message');
			return false;
		}
		
		var formData = {
			"templateId":templateId,
			"groupId":groupId
		};
		
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("EmailerSendWithUs->eventSendEmailToGroupWithTemplate") ;
			$e_event->setEventControler("/ajax_evctl.php") ;
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: formData,
			beforeSubmit: function() {
			
			},
			success: function(res) {
				if (res.trim() == '1') {
					display_js_success(PLUGIN_SWU_EMAIL_SENT_TO_GROUP_WITH_TEMPLATE,'message');
				} else {
					display_js_error(res,'message');
				}
			}
		});
	});
});
</script>