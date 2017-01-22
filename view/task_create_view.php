<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Entity add view 
* @author Abhik Chakraborty
*/ 
?>
<link href="/js/plugins/simplemde/simplemde.min.css" rel="stylesheet"> 
<link href="/js/plugins/magicsuggest/magicsuggest.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/simplemde/simplemde.min.js"></script>
<script type="text/javascript" src="/js/plugins/magicsuggest/magicsuggest.js"></script>
<style>
.controls .CodeMirror {
    height: 160px;
}
</style>
<div class="container-fluid">
	<?php
	$e_add = new Event("Tasks->eventAddRecord");
	$e_add->addParam('idproject',$sqcrm_record_id);
	echo '<form class="" id="Tasks__eventAddRecord" name="Tasks__eventAddRecord" action="/eventcontroler.php" method="post">';
	echo $e_add->getFormEvent();
	?>
	<div class="row">
		<div class="col-md-7">
			<div class="row">
				<div class="col-md-12">
					<div class="box_content">
						<ol class="breadcrumb">
							<li>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"list")?>"><?php echo _('Project')?></a>
							</li>
							<li>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"detail",$sqcrm_record_id)?>"><?php echo $project_name;?></a>
							</li>
							<li class="active">
								<?php echo _('Add Task')?></a>
							</li>
						</ol>
						<p class="lead"><?php echo _('Add a task for this project')?></p> 
					</div>
					
					<div class="datadisplay-outer">
						<div class="row">
							<div style="display:none;" id="formatted-task-note-hidden">
								<textarea name="task_note_formatted" id="task_note_formatted"></textarea>
							</div>
							<div class="col-md-12">
								<div class="form-group">  
									<label class="control-label" for="task_title"><?php echo _('Title')?></label>  
									<div class="controls">  
										<input type="text" class="form-control input-sm" id="task_title" name="task_title"> 
									</div>
								</div>
								
								<div class="form-group">  
									<label class="control-label" for="task_title"><?php echo _('Priority')?></label>  
									<div class="controls">
										<select name="priority" id="priority" class="form-control input-sm">
										<?php
										foreach ($priorities as $key=>$val) {
											echo '<option value="'.$val['id'].'">'.$val['priority'].'</option>';
										}
										?>
										</select>
									</div>
								</div>
								
								<div class="form-group"> 
									<label class="control-label" for="task_note"><?php echo _('Note')?></label>  
									<div class="controls" id="note-area">  
										<textarea name="task_note" id="task_note"></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label" for="time_log"><?php echo _('Hours Worked')?></label>
									<div class="controls">  
										<input type="text" style="width: 100px;" class="form-control input-sm" id="time_log" name="time_log"> 
									</div>
								</div>

								<div class="checkbox">
									<label>
										<input type="checkbox" id="allow_note_edit" name="allow_note_edit" >&nbsp;
										<?php echo _('Allow edit this note by other members')?> 
									</label>
								</div>
							</div>
						</div>
						<hr class="form_hr">
						<input type="button" id= "save-new-task" class="btn btn-primary" value="<?php echo _('Save');?>"/>
					</div>
				</div>
			</div><!--/row-->
		</div><!--/span-->
		<div class="col-md-5">
			<div class="row">
				<div class="col-md-12">
					<div class="box_content">
						<ul class="list-group" id="task-related">
						
							<li class="list-group-item" id="task-labels-section">
								<strong><?php echo _('Labels');?></strong><br />
								<hr class="form_hr">
								<p class="lead">
									<?php echo _('If auto-suggest does not show what you want then please type the label and hit enter');?>
								</p> 
								<div id="task_labels" name="task_labels">
							</li>
							
							<li class="list-group-item" id="task-due-date-section">
								<strong><?php echo _('Due date');?></strong><br />
								<hr class="form_hr">
								<?php echo FieldType9::display_field('due_date');?>
							</li>
							
							<?php
							if (true === $allow_task_assignees) {
							?>
							<li class="list-group-item" id="task-assignee-section">
								<strong><?php echo _('Assignees');?></strong><br />
								<hr class="form_hr">
								<select name="task-assignee-selector" id="task-assignee-selector" class="form-control input-sm">
								<option value="0"><?php echo _('select assignee');?></option>
								<?php
								foreach ($project_members['assigned_to'] as $key=>$val) {
									$display = '';
									$color = '-';
									
									if ($key == $signed_in_user) {
										$display = _('Me');
									} else {
										$display = $val['firstname'].' '.$val['lastname'];
									}
									
									if ($val['user_avatar'] != '') {
										$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
									} else {
										$avatar = '-';
										$color = CommonUtils::get_random_color();
									}
									$opt_value = $sqcrm_record_id.'::'.$val['iduser'].'::'.$val['user_name'].'::'.$val['firstname'].'::'.$val['lastname'].'::'.$val['email'].'::'.$avatar.'::'.$color;
									echo '<option value="'.$opt_value.'">'.$display.'</option>';
								}
									
								if (count($project_members['other_assignee']) > 0) {
									foreach ($project_members['other_assignee'] as $key=>$val) {
										$display = '';
									$color = '-';
									
									if ($key == $signed_in_user) {
										$display = _('Me');
									} else {
										$display = $val['firstname'].' '.$val['lastname'];
									}
									
									if ($val['user_avatar'] != '') {
										$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
									} else {
										$avatar = '-';
										$color = CommonUtils::get_random_color();
									}
									$opt_value = $sqcrm_record_id.'::'.$val['iduser'].'::'.$val['user_name'].'::'.$val['firstname'].'::'.$val['lastname'].'::'.$val['email'].'::'.$avatar.'::'.$color;
									echo '<option value="'.$opt_value.'">'.$display.'</option>';
									}
								}
								?>
								</select>
								<input type="hidden" name="task_assignee_users" id="task_assignee_users">
								<br />
								<div class="row">
									<div class="col-xs-12">
										<div class="col-xs-9" id="task-assignee-selected">
											
										</div>
									</div>
								</div>
								<br />
							</li>
							<?php
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div><!--/row-->
	</form>
</div>
<script>
$(document).ready(function() {
	var simplemde = new SimpleMDE({
		element: document.getElementById("task_note"),
		hideIcons: ["guide", "side-by-side","fullscreen"],
		showIcons: ["code", "table","horizontal-rule"],
		spellChecker: false
	});
	
	var mentionUsers = '<?php echo $mention_member_json;?>';
	var textcomplete = $('.CodeMirror textarea').textcomplete([
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
	
	simplemde.codemirror.addKeyMap({
		Enter: function Enter(cm) {
			if (textcomplete.dropdown.shown) 
				textcomplete.dropdown._enter(new KeyboardEvent('fake enter', { key: 'Enter' }));
			else 
				return cm.constructor.Pass;
		}
	});
	
	$('#note-area').on('click', '.fa-eye', function () {
		var render = simplemde.value();
		var parsedData = parseEmojiMentions(render);
		$('#note-area .editor-preview').html(simplemde.options.previewRender(parsedData));
	})
	
	$('#task_labels').magicSuggest({
		allowFreeEntries: true,
		allowDuplicates: false,
		data: <?php echo $labels;?>,
		name: 'task_labels',
		minChars: 2
	});
	
	$('#task-assignee-selector').on('change', function() {
		var optVal = $('#task-assignee-selector').val() ;
		if (optVal) {
			var memberData = optVal.split('::');
			var memberHtml = '<div class="col-xs-3" style="margin-top:14px;" id="task-assignee-'+memberData[1]+'">';
				
			if (memberData[6] == '-') {
				var initials = memberData[3].charAt(0)+''+memberData[4].charAt(0);
				memberHtml += '<div style="float:left;background-color:'+memberData[7]+'" data-profile-initials= "'+initials.toUpperCase()+'" class="circular_35" title="'+memberData[3]+' '+memberData[4]+'('+memberData[2]+')">';
				memberHtml += '<a href="#" onclick="removeTaskAssignee(\''+memberData[1]+'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a>';
			} else {
				memberHtml += '<div class="circular_35" title="'+memberData[3]+' '+memberData[4]+'('+memberData[2]+')" style="float:left;background-image:url(\''+memberData[6]+'\')">';
				memberHtml += '<a href="#" onclick="removeTaskAssignee(\''+memberData[1]+'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a>';
			}
				
			memberHtml += '</div>';
			memberHtml += '</div>';
			
			var taskAssignee = $('#task_assignee_users').val();
			taskAssignee += taskAssignee ? ','+memberData[1] : memberData[1];
			$('#task_assignee_users').val(taskAssignee);
			
			$('#task-assignee-selected').append(memberHtml);
			$('#task-assignee-selector option:selected').remove();
		}
	});
	
	$('#save-new-task').click( function() {
		$('#task_note_formatted').val(simplemde.options.previewRender(simplemde.value()));
		$('#Tasks__eventAddRecord').submit();
	})
});

function removeTaskAssignee(iduser) {
	var taskAssignee = $('#task_assignee_users').val().split(',');
	taskAssignee = $.grep(taskAssignee, function(n,i) {
		return n != iduser;
	});
	
	var elementToBeRemoved = 'task-assignee-'+iduser;
	$('#'+elementToBeRemoved).remove();
	
	if (elementToBeRemoved) {
		$('#task_assignee_users').val(elementToBeRemoved.join(','));
	} else {
		$('#task_assignee_users').val('');
	}
}

function parseEmojiMentions(plainText) {
	var parsedData;
	$.ajax({
		type: 'POST',
		data : {note:plainText},
		async: false,
		<?php
		$e_add_per = new Event("Tasks->eventParseTaskNote");
		$e_add_per->setEventControler("/ajax_evctl.php");
		$e_add_per->setSecure(false);
		?>
		url: "<?php echo $e_add_per->getUrl(); ?>",
		success: function(data) {
			console.log('success');
			parsedData = data;
		}
	});
	
	return parsedData;
}
</script>