<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* project members
* @author Abhik Chakraborty
*/  

$allow_edit = false ;
if (isset($_REQUEST['ajaxreq']) && $_REQUEST['ajaxreq'] == true) {
	$idtask = (int)$_REQUEST['idtasks'];
	if ($idtask == 0) { echo '<div class="alert alert-danger">'._('Invalid task id').'</div>'; exit(); }
	if ((int)$sqcrm_record_id == 0) { echo '<div class="alert alert-danger">'._('Invalid project id').'</div>'; exit(); }
	if ((int)$_REQUEST['activity_id'] == 0) { echo '<div class="alert alert-danger">'._('Invalid activity').'</div>'; exit(); }
	
	$do_project = new Project();
	$do_project->getId($sqcrm_record_id);
	$project_members = $do_project->get_project_members($do_project);
	
	$signed_in_user = $_SESSION["do_user"]->iduser;
	$mention_members_but_me = array();
	$mention_member_json = '';
	
	// generate the json for the mention @user in note, ignore the current user in the list
	foreach ($project_members['assigned_to'] as $key=>$val) {
		if ($key == $signed_in_user) continue;
		
		$mention_members_but_me[] = $val["user_name"].'('.$val["firstname"].' '.$val["lastname"].')';
	}
	
	if (count($project_members['other_assignee']) > 0) {
		foreach ($project_members['other_assignee'] as $key=>$val) {
			if ($key == $signed_in_user) continue;
		
			$mention_members_but_me[] = $val["user_name"].'('.$val["firstname"].' '.$val["lastname"].')';
		}
	}
	
	$mention_member_json = json_encode($mention_members_but_me);
	$note_owner = false;
	
	$do_task = new Tasks();
	$do_task->query("select * from task_activity where idtask_activity = ".(int)$_REQUEST['activity_id']);
	if ($do_task->getNumRows() > 0) {
		$do_task->next();
		
		if ($do_task->activity_type != 1) {
			echo '<div class="alert alert-danger">'._('Invalid activity').'</div>'; 
			exit();
		}
		$description = $do_task->description;
		
		if ($do_task->iduser == $signed_in_user || $do_task->allow_note_edit == 1) {
			$allow_edit = true;
		}
		
		if ($do_task->iduser == $signed_in_user) {
			$note_owner = true;
		}
	} else {
		echo '<div class="alert alert-danger">'._('Invalid activity').'</div>'; exit();
	}
} 

if (true === $allow_edit) {
?>
<style>
.task-note-edit-ajax .CodeMirror {
    height: 400px;
}
</style>
<?php
$e_update_notes = new Event("Tasks->eventUpdateTaskNote");
$e_update_notes->addParam("idtasks",$idtask);
$e_update_notes->addParam("activity_id",(int)$_REQUEST['activity_id']);
$e_update_notes->addParam("sqrecord",$sqcrm_record_id);
echo '<form class="" id="Tasks__eventUpdateTaskNote" name="Tasks__eventUpdateTaskNote"  method="post" enctype="multipart/form-data">';
echo $e_update_notes->getFormEvent();
?>
<div class="task-note-edit-ajax">
<textarea name="task_note_edit" id="task_note_edit"><?php echo $description;?></textarea>
<br />
<?php
if (true === $note_owner) {
?>
<?php echo _('Hours Worked');?><br />
<input type="text" style="width: 100px;" class="form-control input-sm" id="time_log" name="time_log" value="<?php echo ($do_task->time_log > 0 ? $do_task->time_log: 0)?>">
<br />
<?php
} else {
?>
<input type="hidden" name="time_log" id="time_log" value="<?php echo ($do_task->time_log > 0 ? $do_task->time_log: 0)?>">
<?php
}

if (true === $note_owner) {
?>
<?php echo _('Allow edit this note by other members');?><br />
<input type="checkbox" id="allow_note_edit" name="allow_note_edit" <?php echo ($do_task->allow_note_edit == 1) ? 'CHECKED': '';?>>
<?php
} else {
?>
<input type="hidden" name="allow_note_edit" id="allow_note_edit" value="<?php echo ($do_task->allow_note_edit == 1 ? 'on' : '')?>">
<?php
}
?>
<hr class="form_hr">
<?php 
echo _('Add files');
echo '<br />';
FieldType21::display_field('note_files_edit');	
?>
<hr class="form_hr">
<a href="#" class="btn btn-default active cancel-note-edit" id="<?php echo (int)$_REQUEST['activity_id'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>  
<input type="submit" class="btn btn-primary" id="edit-task-note" value="<?php echo _('update')?>"/>
</form>
</div>
<script>
$(document).ready(function() {
	var simplemdeEdit = new SimpleMDE({
		element: document.getElementById("task_note_edit"),
		hideIcons: ["guide", "side-by-side","fullscreen"],
		showIcons: ["code", "table","horizontal-rule"],
		spellChecker: false
	});
	
	var mentionUsers = '<?php echo $mention_member_json;?>';
	var textcompleteEd =$('.CodeMirror textarea').textcomplete([
		{ // emoji strategy
			match: /\B:([\-+\w]*)$/,
			search: function (term, callback) {
				callback($.map(emojies, function (emoji) {
					return emoji.indexOf(term) === 0 ? emoji : null;
				}));
			},
			template: function (value) {
				return '<img width="20" height="20" src="/themes/images/emoji-pngs/' + value + '.png"></img>' + value;
			},
			replace: function (value) {
				return ':' + value + ': ';
			},
			index: 1
		},
		{ // mentions strategy
			mentions : $.parseJSON(mentionUsers),
			match: /\B@(\w*)$/,
			search: function (term, callback) {
				callback($.map(this.mentions, function (mention) {
					return mention.indexOf(term) === 0 ? mention : null;
				}));
			},
			index: 1,
			replace: function (mention) {
				var mentionedUserName = mention.split('(') ;
				return '@' + mentionedUserName[0] + ' ';
			}
		}
	]).data('textComplete'); 
	
	simplemdeEdit.codemirror.addKeyMap({
		Enter: function Enter(cm) {
			if (textcompleteEd.dropdown.shown) 
				textcompleteEd.dropdown._enter(new KeyboardEvent('fake enter', { key: 'Enter' }));
			else 
				return cm.constructor.Pass;
		}
	});
	
	$('#note-activity-section').on('click', '.fa-eye', function () {
		var render = simplemdeEdit.value();
		var parsedData = parseEmojiMentions(render);
		$('#note-activity-section .editor-preview').html(simplemdeEdit.options.previewRender(parsedData));
	})
});
</script>
<?php
} else {
	echo '<div class="alert alert-danger">'._('You are not authorized to perform this operation').'</div>'; exit();
}
?>