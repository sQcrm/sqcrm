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
.new-note .CodeMirror {
    height: 400px;
}
</style>
<div class="container-fluid">
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
						</ol>
					</div>
					
					<div class="box_content" id="task-detail-section">
						<div class="row">
							<div class="col-xs-8" style="margin-top:-17px;" id="task-title-section">
								<h2>
								<?php echo $do_task->task_title; ?>
								<span style="color :#777777;">#<?php echo $do_task->idtasks; ?></span>
								</h2>
							</div>
							<div class="col-xs-4" id="task-edit-button-block">
								<div class="text-right" style="margin-top:10px;">
								<?php
								if (true === $allow_task_edit) {
								?>
								<span id="task-edit-icon-block"><a href="#" class="btn btn-primary btn-xs" id = "task-edit-icon"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> <?php echo _('edit');?></a></span>
								<?php
								}
								if (true === $allow_task_create) {
								?>
								<span id="task-add-icon-block"><a href="/modules/Project/<?php echo $sqcrm_record_id;?>/task/add" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-tasks"></i> <?php echo _('new task')?></a></span>
								<?php
								}
								if (true === $allow_task_close) {
									if ($do_task->task_status == 1 || $do_task->task_status == 3) {
								?>
										<span id="task-close-reopen-icon-block"><a href="#" class="btn btn-primary btn-xs" id= "task-close-icon"><i class="glyphicon glyphicon-off"></i> <?php echo _('close')?></a></span>
								<?php
									} elseif ($do_task->task_status == 2) {
								?>
										<span id="task-reopen-reopen-icon-block"><a href="#" class="btn btn-primary btn-xs" id= "task-reopen-icon"><i class="glyphicon glyphicon-open-file"></i> <?php echo _('reopen')?></a></span>
								<?php
									}
								}
								?>
								</div>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-xs-12">
								<?php 
									echo '<span id="task-status-block">'.$status.'</span>';
									echo '&nbsp;&nbsp;';
									echo '<a href="#"><strong>'.$do_task->firstname. ' '.$do_task->lastname.'</strong></a>';
									echo ' '._('has created the task on');
								?>
								<span class="notes_date_added">
								<?php
									echo i18nDate::i18n_long_date($do_task->date_created,true);
								?>
								</span>
								&nbsp;
								<span id="task-priority-section">
									<?php echo $priority;?>
								</span>
							</div>
						</div>
						<br />
						<div class="row" id="task-edit-section" style="display:none;">
							<div class="col-xs-12">
								<div class="form-group">  
									<label class="control-label" for="edit_task_title"><?php echo _('Title')?></label>  
									<div class="controls">  
										<input type="text" class="form-control input-sm" id="edit_task_title" name="edit_task_title" value="<?php echo $do_task->task_title;?>"> 
									</div>
								</div>
								<div class="form-group">  
									<label class="control-label" for="edit_priority"><?php echo _('Priority')?></label>  
									<div class="controls">
										<select name="edit_priority" id="edit_priority" class="form-control input-sm">
										<?php
										foreach ($priorities as $key=>$val) {
											$selected = '';
											if ($val['id'] == $do_task->priority) $selected = 'SELECTED';
											echo '<option value="'.$val['id'].'" '.$selected.'>'.$val['priority'].'</option>';
										}
										?>
										</select>
									</div>
								</div>
								<hr class="form_hr">
								<div id="task-edit-ajax-loader"></div>
								<div id="task-edit-footer">
									<input type="hidden" name="idtasks_hidden" id="idtasks_hidden" value="<?php echo $idtask;?>">
									<a href="#" class="btn btn-default active cancel-task-edit"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>  
									<input type="button" class="btn btn-primary" id= "edit-task-button" value="<?php echo _('Save');?>"/>
								</div>
							</div>
						</div>
					</div>
					<div id="no_show" style="display:none;"></div>
					<div id="note-activity-section">
						
					</div>
					
					<hr class="form_hr">
					<div class="datadisplay-outer new-note">
						<?php
						$e_add_new_note = new Event("Tasks->eventAddTaskNote");
						$e_add_new_note->addParam("idtasks",$idtask);
						$e_add_new_note->addParam("sqrecord",$sqcrm_record_id);
						echo '<form class="" id="Tasks__eventAddTaskNote" name="Tasks__eventAddTaskNote"  method="post" enctype="multipart/form-data">';
						echo $e_add_new_note->getFormEvent();
						?>
						<?php echo _('Add a new note');?><br />
						<textarea name="new_task_note" id="new_task_note"></textarea>
						<br />
						<?php echo _('Hours Worked');?><br />
						<input type="text" style="width: 100px;" class="form-control input-sm" id="new_time_log" name="new_time_log" value="">
						<br />
						<?php echo _('Allow edit this note by other members');?><br />
						<input type="checkbox" id="allow_note_edit_new" name="allow_note_edit_new">
						<hr class="form_hr">
						<?php 
						echo _('Add files');
						echo '<br />';
						FieldType21::display_field('note_files_add');	
						?>
						<div style="display:none;" id="formatted-task-note-hidden">
							<textarea name="new_task_note_formatted" id="new_task_note_formatted"></textarea>
						</div>
						<hr class="form_hr">
						<input type="submit" class="btn btn-primary" id="new-note-entry-submit" value="<?php echo _('Save');?>"/>
						</form>
					</div>
				</div>
			</div><!--/row-->
		</div><!--/span-->
		<div class="col-md-5">
			<div class="row">
				<div class="col-md-12">
					<div class="box_content">
						<ul class="list-group" id="task-related">
							<li class="list-group-item" id="task-due-date-section">
								<strong><?php echo _('Due date');?></strong><br />
								<hr class="form_hr">
								<?php 
								if (false === $allow_task_edit) {
									echo FieldType9::display_value($do_task->due_date);
								} else {
									echo FieldType9::display_field('due_date',$do_task->due_date);
								?>
								<br />
								<a href="#" class="btn btn-primary btn-xs" id="change-due-date">
									<?php echo _('change');?>
								</a>
								<?php
								}
								?>
							</li>
							<li class="list-group-item" id="task-labels-section">
								<strong><?php echo _('Labels');?></strong><br />
								<hr class="form_hr">
								<?php
								if (true === $allow_task_edit) {
								?>
								<p class="lead">
									<?php echo _('If auto-suggest does not show what you want then please type the label and hit enter');?>
								</p> 
								<div id="task_labels" name="task_labels">
								<?php
								} else {
									if (count($attached_task_labels) > 0) {
										foreach ($attached_task_labels as $key=>$val) {
											echo '<span class="label" style="background-color:grey;font-size: 14px;"">'.$val['name'].'</span>&nbsp;';
										}
									} else {
										echo _('No label attached');
									}
									echo '<div id="task_labels" name="task_labels" style="display:none;">';
								}
								?>
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
									if (in_array($key, $task_assignees_ids)) continue;
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
										if (in_array($key, $task_assignees_ids)) continue;
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
								<input type="hidden" name="task_assignee_users" id="task_assignee_users" value="<?php echo (count($task_assignees_ids) > 0 ? implode(',',$task_assignees_ids): '');?>">
								<br />
								<div class="row">
									<div class="col-xs-12">
										<div class="col-xs-9" id="task-assignee-selected">
										<?php
										if (count($task_assignees_ids) > 0) {
											foreach ($task_assignees as $key=>$val) {
												$avatar = '';
												$html = '<div class="col-xs-3" style="margin-top:14px;" id="task-assignee-'.$val['iduser'].'">';
												if ($val['user_avatar'] != '') {
													$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
													$html .= '<div class="circular_35" title="'.$val['firstname'].' '.$val['lastname'].'('.$val['user_name'].')" style="float:left;background-image:url(\''.$avatar.'\')">';
													$html .= '<a href="#" onclick="removeTaskAssignee(\''.$val['iduser'].'\',\''.$do_task->idtasks.'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a>';
												} else {
													$initials = strtoupper($val['firstname'][0].$val['lastname'][0]);
													$html .= '<div style="float:left;background-color:'.CommonUtils::get_random_color().'" data-profile-initials= "'.$initials.'" class="circular_35" title="'.$val['firstname'].' '.$val['lastname'].'('.$val['user_name'].')">';
													$html .= '<a href="#" onclick="removeTaskAssignee(\''.$val['iduser'].'\',\''.$do_task->idtasks.'\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a>';
												}
												$html .= '</div></div>';
												echo $html;
											}
										}
										?>
										</div>
									</div>
								</div>
								<br />
							</li>
							<?php
							} else { ?>
							<li class="list-group-item" id="task-assignee-section">
								<strong><?php echo _('Assignees');?></strong><br />
								<hr class="form_hr">
								<div class="row">
									<div class="col-xs-12">
										<div class="col-xs-9" id="task-assignee-selected">
										<?php
										if (count($task_assignees_ids) > 0) {
											foreach ($task_assignees as $key=>$val) {
												$avatar = '';
												$html = '<div class="col-xs-3" style="margin-top:14px;" id="task-assignee-'.$val['iduser'].'">';
												if ($val['user_avatar'] != '') {
													$avatar = FieldType12::get_file_name_with_path($val['user_avatar'],'s');
													$html .= '<div class="circular_35" title="'.$val['firstname'].' '.$val['lastname'].'('.$val['user_name'].')" style="float:left;background-image:url(\''.$avatar.'\')">';
												} else {
													$initials = strtoupper($val['firstname'][0].$val['lastname'][0]);
													$html .= '<div style="float:left;background-color:'.CommonUtils::get_random_color().'" data-profile-initials= "'.$initials.'" class="circular_35" title="'.$val['firstname'].' '.$val['lastname'].'('.$val['user_name'].')">';
												}
												$html .= '</div></div>';
												echo $html;
											}
										} else {
											echo _('No one has been assigned to this task yet');
										}
										?>
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
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="remove_confirm">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to perform this operation ?');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
				<input type="submit" class="btn btn-primary" value="<?php echo _('Yes')?>"/>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="update_note_modal" tabindex="-1" role="dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><?php echo _('Update task')?></h3>
		</div>
		<div class="modal-body">
			<div class="datadisplay-outer">
				
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" id="close-avatar-popup" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
			<input type="submit" class="btn btn-primary" value="<?php echo _('Update')?>"/>
		</div>
	</div>
</div>

<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<script>
var lastActivityId = 0 ,
	removeTaskAssignee;

$(document).ready(function() {
	// init the SimpleMDE
	var simplemde = new SimpleMDE({
		element: document.getElementById("new_task_note"),
		hideIcons: ["guide", "side-by-side","fullscreen"],
		showIcons: ["code", "table","horizontal-rule"],
		spellChecker: false
	});
	
	// preload the mentioned user info for usage inside the text editor using @
	var mentionUsers = '<?php echo $mention_member_json;?>';
	
	// set the textarea to textcomplete plugin for emoji and mentions
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
	
	/**
	* patch for textcomplete with simplemde to fix issue of selecting emoji or mentions with ENTER key
	* @see https://github.com/yuku-t/jquery-textcomplete/issues/255
	*/ 
	simplemde.codemirror.addKeyMap({
		Enter: function Enter(cm) {
			if (textcomplete.dropdown.shown) 
				textcomplete.dropdown._enter(new KeyboardEvent('fake enter', { key: 'Enter' }));
			else 
				return cm.constructor.Pass;
		}
	});
	
	// on edit view of existing note once the preview is clicked call parseEmojiMentions before rendering the preview
	$('#note-area').on('click', '.fa-eye', function () {
		var render = simplemde.value();
		var parsedData = parseEmojiMentions(render);
		$('#note-area .editor-preview').html(simplemde.options.previewRender(parsedData));
	});
	
	// while adding a note once the preview is clicked call parseEmojiMentions before rendering the preview
	$('.new-note').on('click', '.fa-eye', function () {
		var render = simplemde.value();
		var parsedData = parseEmojiMentions(render);
		$('.new-note .editor-preview').html(simplemde.options.previewRender(parsedData));
	});
	
	// init the magicSuggest for label selector
	var labelSelector = $('#task_labels').magicSuggest({
		allowFreeEntries: true,
		allowDuplicates: false,
		data: <?php echo $labels;?>,
		name: 'task_labels',
		minChars: 2,
		value : <?php echo json_encode($attached_task_label_ids);?>
	});
	
	// when the edit icon is clicked for an existing note 
	$('#note-activity-section').on('click', '.task-note-edit-link', function(e) {
		var id = this.id,
			toHide = 'task-note-content-'+id,
			toShow = 'task-note-edit-'+id,
			editDelSection = 'note-edit-del-section-'+id;
			
		$('#'+toShow).html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');	
		$('#'+toShow).show();
		
		$.ajax({
			type: "GET",
			async: true,
			url: "/modules/Project/task_note_edit",
			data : "ajaxreq="+true+"&rand="+generateRandonString(10)+"&sqrecord=<?php echo $sqcrm_record_id;?>&idtasks=<?php echo $idtask;?>&activity_id="+id,
			success: function(data) { 
				if (data.trim() != '0' && data.trim() != '') {
					$('#note-activity-section .task-note-edit-block').each( function() {
						var currentIdStr = this.id;
							parts = currentIdStr.split('-'),
							currentId = parts.pop();
						
						$('#task-note-edit-'+currentId).html('');
						$('#task-note-edit-'+currentId).hide();
						
						$('#task-note-content-'+currentId).show();
						$('#note-edit-del-section-'+currentId).show();
						$('#note-edit-cancel-'+currentId).hide();
					});
					
					$('#'+toShow).html(data);
					$('#'+toHide).hide();
					$('#'+editDelSection).hide();
					$('#'+toShow).show();
					$('#note-edit-cancel-'+id).show();
				} 
			}
		});
		return false;	
	});
    
    // init the note edit option for ajaxSubmit plugin
    var noteEditOpt = {
		target: '#no_show',
		url:'/ajax_evctl.php',
		beforeSubmit: function() {
		},
		success:  function(data) {
			var result = JSON.parse(data),
				id = result.id;
			if (result.status.trim() === 'ok') {
				$('#task-note-edit-'+id).html('');
				$('#task-note-edit-'+id).hide();
				$('#note-edit-cancel-'+id).hide();
				$('#task-note-content-'+id).html(simplemde.options.previewRender(result.data));
				$('#task-note-content-'+id).show();
				$('#note-edit-del-section-'+id).show();
			} else {
				display_js_error(result.status.err,'js_errors');
			}
		}
    };
    
    // ajax submit of the not edit section
    $(document).on('submit', '#Tasks__eventUpdateTaskNote', function() {
		$(this).ajaxSubmit(noteEditOpt);
		return false;
    });
    
    // init the note add option for ajaxSubmit plugin
    var noteAddOpts = {
		target: '#no_show',
		url:'/ajax_evctl.php',
		beforeSubmit: function() {
		},
		success:  function(data) {
			$('#new_task_note_formatted').val('');
			if (data.trim() === '1') {
				simplemde.value('');
				$('#new_time_log').val('');
				$('#allow_note_edit_new').prop('checked', false);
				loadActivityAfterAction();
			} else {
				display_js_error(data,'js_errors');
			}
		}
    };
    
    // ajax submit of new note
    $('#Tasks__eventAddTaskNote').submit(function() {
		//send a formatted version of the text for sending emails
		$('#new_task_note_formatted').val(simplemde.options.previewRender($('#new_task_note').val()));
		$(this).ajaxSubmit(noteAddOpts);
        return false;
    });
    
	// if cancel is clicked while editing a note
	$('#note-activity-section').on('click', '.cancel-note-edit', function(e) {
		var id = this.id;
		$('#note-edit-cancel-'+id).hide();
		$('#note-edit-del-section-'+id).show();
		$('#task-note-edit-'+id).html('');
		$('#task-note-edit-'+id).hide();
		$('#task-note-content-'+id).show();
		return false;
	});
	
	// when a task note delete is clicked
	$('#note-activity-section').on('click', '.task-note-delete-link', function(e) {
		var id = this.id;
		$("#remove_confirm").modal('show');
		$("#remove_confirm .btn-primary").off('click');
		$("#remove_confirm .btn-primary").click(function() {
			$("#remove_confirm").modal('hide');
			$.ajax({
				type: 'POST',
				data : {idtasks:<?php echo $idtask;?>,sqrecord:<?php echo $sqcrm_record_id;?>,activity_id:id,type:'note'},
				<?php
				$e_del_note = new Event("Tasks->eventDeleteTaskNote");
				$e_del_note->setEventControler("/ajax_evctl.php");
				$e_del_note->setSecure(false);
				?>
				url: "<?php echo $e_del_note->getUrl(); ?>",
				success: function(data) {
					if (data.trim() === '1') {
						$('#note-activity-section #activity-'+id).remove();
					} else {
						display_js_error(data,'js_errors');
					}
				}
			});
		});
		
		return false;
	});
	
	// when task note file delete icon is clicked
	$('#note-activity-section').on('click', '.task-note-file-delete', function(e) {
		var fileId = this.id,
			activityId = $('.note-file-'+fileId).prop('id');
		
		$("#remove_confirm").modal('show');
		$("#remove_confirm .btn-primary").off('click');
		$("#remove_confirm .btn-primary").click(function() {
			$("#remove_confirm").modal('hide');
			$.ajax({
				type: 'POST',
				data : {idtasks:<?php echo $idtask;?>,sqrecord:<?php echo $sqcrm_record_id;?>,activity_id:activityId,type:'file',flid:fileId},
				<?php
				$e_del_note = new Event("Tasks->eventDeleteTaskNote");
				$e_del_note->setEventControler("/ajax_evctl.php");
				$e_del_note->setSecure(false);
				?>
				url: "<?php echo $e_del_note->getUrl(); ?>",
				success: function(data) {
					if (data.trim() === '1') {
						$('#note-activity-section .note-file-'+fileId).remove();
					} else {
						display_js_error(data,'js_errors');
					}
				}
			});
		});
		
		return false;
	});
	
	// label selector on value change
	$(labelSelector).on('selectionchange', function(e, m) {
		var changedValues = this.getValue();
		$.ajax({
			type: 'POST',
			data : {labels:changedValues,idtasks:<?php echo $idtask;?>,idproject:<?php echo $sqcrm_record_id;?>},
			<?php
			$e_upd_labels = new Event("Tasks->eventUpdateLabels");
			$e_upd_labels->setEventControler("/ajax_evctl.php");
			$e_upd_labels->setSecure(false);
			?>
			url: "<?php echo $e_upd_labels->getUrl(); ?>",
			success: function(data) {
				if (data.trim() === '1') {
					loadActivityAfterAction();
				} else {
					display_js_error(data,'js_errors');
				}
			}
		});
	});
	
	// task assignee combo on change
	$('#task-assignee-selector').on('change', function() {
		var optVal = $('#task-assignee-selector').val() ;
		if (optVal) {
			var memberData = optVal.split('::');
			
			$.ajax({
				type: 'POST',
				data : {iduser:memberData[1],idtasks:<?php echo $idtask;?>,idproject:<?php echo $sqcrm_record_id;?>},
				<?php
				$e_add_task_assignee = new Event("Tasks->eventAddTaskAssignee");
				$e_add_task_assignee->setEventControler("/ajax_evctl.php");
				$e_add_task_assignee->setSecure(false);
				?>
				url: "<?php echo $e_add_task_assignee->getUrl(); ?>",
				success: function(data) {
					if (data.trim() === '1') {
						var memberHtml = '<div class="col-xs-3" style="margin-top:14px;" id="task-assignee-'+memberData[1]+'">';
				
						if (memberData[6] == '-') {
							var initials = memberData[3].charAt(0)+''+memberData[4].charAt(0);
							memberHtml += '<div style="float:left;background-color:'+memberData[7]+'" data-profile-initials= "'+initials.toUpperCase()+'" class="circular_35" title="'+memberData[3]+' '+memberData[4]+'('+memberData[2]+')">';
							memberHtml += '<a href="#" onclick="removeTaskAssignee(\''+memberData[1]+'\',\'<?php echo $idtask;?>\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-bottom:5px;"></span></a>';
						} else {
							memberHtml += '<div class="circular_35" title="'+memberData[3]+' '+memberData[4]+'('+memberData[2]+')" style="float:left;background-image:url(\''+memberData[6]+'\')">';
							memberHtml += '<a href="#" onclick="removeTaskAssignee(\''+memberData[1]+'\',\'<?php echo $idtask;?>\');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="float:right;margin-top:32px;"></span></a>';
						}
				
						memberHtml += '</div>';
						memberHtml += '</div>';
			
						$('#task-assignee-selected').append(memberHtml);
						$('#task-assignee-selector option:selected').remove();
						loadActivityAfterAction();
					} else {
						display_js_error(data,'js_errors');
					}
				}
			});
		}
	});
	
	// trigger body on load and have the ajax loader icon on note activity section
	$('#note-activity-section').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
	
	// body on load, get the acitivity via ajax and display them
	$.ajax({
		type: "GET",
		url: "/modules/Project/task_activity",
		data : "ajaxreq="+true+"&rand="+generateRandonString(10)+"&sqrecord=<?php echo $sqcrm_record_id;?>&idtasks=<?php echo $idtask;?>",
		success: function(data) { 
			$('#note-activity-section').html('');
			if (data.trim() == '0' || data.trim() == '') {
			} else {
				var result = JSON.parse(data);
				$('#note-activity-section').html('<div class="datadisplay-outer"></div>');
				generateActivityView(result);
			}
		},
		complete: function(data) {
			var hash = location.hash.replace('#','');
			if (hash != '') {
				$('html,body').animate({scrollTop: $("#"+hash).offset().top - 50},850);
			}
		}
	});
	
	// load the activity after an event occurs on the page like new note add, assign/remove user, label add/edit etc
	function loadActivityAfterAction() {
		if (lastActivityId ===0) $('#note-activity-section').html('<div class="datadisplay-outer"></div>');
		$.ajax({
			type: "GET",
			url: "/modules/Project/task_activity",
			data : "ajaxreq="+true+"&rand="+generateRandonString(10)+"&sqrecord=<?php echo $sqcrm_record_id;?>&idtasks=<?php echo $idtask;?>&start_after="+lastActivityId,
			success: function(data) { 
				if (data.trim() != '0' && data.trim() != '') {
					var result = JSON.parse(data);
					generateActivityView(result);
				} 
			}
		});
	}
	
	// activity view generation function
	function generateActivityView(result) {
		for (var key in result) {
			var obj = result[key],
				html='';
			
			lastActivityId = obj.id;
			
			if (obj.activity_type === '1') {
				html += '<div class="qa-message-list">';
				html += 	'<div class="message-item" id="">';
				html += 		'<div class="message-inner">';
				html += 			'<div class="message-head clearfix">';
				html += 				'<div class="avatar pull-left"><a href="#" id="activity-'+obj.id+'" onclick="return false;"><img src="'+obj.user_avatar+'"></a></div>';
				html += 				'<div class="user-detail">';
				html += 					'<h5 class="handle">'+obj.firstname+' '+obj.lastname+'</h5>';
				html += 					'<div class="post-meta">';
				html += 						'<div class="asker-meta">';
				html += 							'<span class="qa-message-what"></span>';
				html += 							'<span class="qa-message-when">';
				html += 								'<span class="qa-message-when-data notes_date_added">'+obj.date_added+'</span>';
				html += 							'</span>';
				html += 						'</div>';
				html +=						'</div>';
				html +=					'</div>'
				html += 				'<div class="text-right" style="margin-top:-34px;display:none;" id="note-edit-cancel-'+obj.id+'">';
				html +=						'<button type="button" class="close cancel-note-edit" aria-label="Close" id="'+obj.id+'" title="'+CANCEL_LW+'"><span aria-hidden="true">&times;</span></button>';
				html += 				'</div>';
				
				if (obj.allow_note_edit == 1) {
				
				html += 				'<div class="text-right" style="margin-top:-34px;" id="note-edit-del-section-'+obj.id+'">';
				html += 					'<a href="#" id = "'+obj.id+'" title="'+EDIT_LW+'" class="task-note-edit-link"><span class="glyphicon glyphicon-edit" aria-hidden="true"></a>';
				html += 					'&nbsp;<a href="#" id = "'+obj.id+'" title="'+DELETE_LW+'" class="task-note-delete-link"><span class="glyphicon glyphicon-trash" aria-hidden="true"></a>';
				html += 				'</div>';
				
				}
				
				html +=				'</div>';
				html +=				'<div class="qa-message-content" id="task-note-content-'+obj.id+'">';
				html +=					simplemde.options.previewRender(obj.description);
				html +=				'</div>';
				html +=				'<div id="task-note-edit-'+obj.id+'" style="display:none;" class="task-note-edit-block">';
				html +=				'</div>'
				
				if (obj.files.trim() != '') {
					html +=			'<div>';
					html +=			'<hr class="form_hr">';
					html +=			'<strong>'+FILES+'</strong>';
					html +=			'<hr class="form_hr">';
					html +=			obj.files;
					html +=			'</div>';
				}
				
				html += 		'</div>';
				html +=		'</div>';
				html +='</div>';
				$('#note-activity-section .datadisplay-outer').append(html);
			} else {
				html += '<div class="qa-message-list" id="wallmessages">';
				html += '<div class="message-item" id="">';
				html += '<div class="message-inner-no-comment">';
				html += obj.description;
				//html += '<br /><div class="form_hr"></div>';
				html += '&nbsp;&nbsp;<span class="notes_date_added">';
				html += '<a href="#" onclick="return false;" style="color :#777777;">'+obj.date_added+'</a>';
				html += '</span>';
				html += '</div></div></div>';
				$('#note-activity-section .datadisplay-outer').append(html);
			}
		}
	}
	
	// task assignee remove function
	removeTaskAssignee = function (iduser,idtasks) {
		if (iduser && idtasks) {
			$("#remove_confirm").modal('show');
			$("#remove_confirm .btn-primary").off('click');
			$("#remove_confirm .btn-primary").click(function() {
				$("#remove_confirm").modal('hide');
				$.ajax({
					type: 'POST',
					data : {iduser:iduser, idtasks:idtasks,idproject:<?php echo $sqcrm_record_id;?>},
					<?php
					$e_remove_assignee = new Event("Tasks->eventRemoveTaskAssignee");
					$e_remove_assignee->setEventControler("/ajax_evctl.php");
					$e_remove_assignee->setSecure(false);
					?>
					url: "<?php echo $e_remove_assignee->getUrl(); ?>",
					success: function(data) {
						if (data.trim() === '1') {
							var elementToBeRemoved = 'task-assignee-'+iduser;
							$('#'+elementToBeRemoved).remove();
							loadActivityAfterAction();
						} else {
							display_js_error(data,'js_errors');
						}
					}
				});
			});
		}
	}
	
	// on clicking the task edit button
	$('#task-edit-icon').click( function() {
		$('#task-edit-section').show();
		return false;
	});
	
	// on clicking the cancel button for task edit section
	$('.cancel-task-edit').click( function() {
		$('#task-edit-section').hide();
		return false;
	});
	
	// on clicking the edit button for the task edit form
	$('#task-edit-section').on('click','#edit-task-button', function() {
		$('#task-edit-footer').hide();
		$('#task-edit-ajax-loader').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		$('#task-edit-ajax-loader').show();
		var taskTitle = $('#edit_task_title').val(),
			taskPriority = $('#edit_priority').val(),
			idTasks = $('#idtasks_hidden').val(),
			taskPriorityText = $("#edit_priority option:selected").text();

		$.ajax({
			type: 'POST',
			data: {idtasks:idTasks, idproject:<?php echo $sqcrm_record_id;?>, edit_task_title:taskTitle, edit_priority:taskPriority},
			<?php
			$e_edit_task = new Event("Tasks->eventUpdateRecord");
			$e_edit_task->setEventControler("/ajax_evctl.php");
			$e_edit_task->setSecure(false);
			?>
			url: "<?php echo $e_edit_task->getUrl(); ?>",
			success: function(data) {
				$('#task-edit-ajax-loader').html('');
				$('#task-edit-footer').show();
				
				if (data.trim() === '1') {
					var titleHtml = '',
						priorityHtml= '';
						
					titleHtml += '<h2>'+taskTitle;
					titleHtml += '<span style="color :#777777;">#'+idTasks+'</span>';
					titleHtml += '</h2>';
					
					$('#task-title-section').html(titleHtml);
					
					$.ajax({
						type: "GET",
						data: "id="+taskPriority+"&priority="+taskPriorityText,
						<?php
						$e_get_priority_text = new Event("Tasks->eventRenderTaskPriorityDisplay");
						$e_get_priority_text->setEventControler("/ajax_evctl.php");
						$e_get_priority_text->setSecure(false);
						?>
						url: "<?php echo $e_get_priority_text->getUrl(); ?>",
						success: function(priorityHtml) {
							$('#task-priority-section').html(priorityHtml);
						}
					});
					
					display_js_success(UPDATED_SUCCESSFULLY,'js_errors');
					$('#task-edit-section').hide();
					loadActivityAfterAction();	
				} else {
					display_js_error(data,'js_errors');
				}
			}
		});
	})
	
	// change the task due date
	$('#change-due-date').click( function() {
		var due_date = $('#due_date').val();
		if (due_date == '') {
			display_js_error(SELECT_DUE_DATE_BEFORE_SAVE,'js_errors');
		} else {
			$.ajax({
				type: 'POST',
				data: {due_date:due_date, idtasks:<?php echo $idtask;?>, idproject:<?php echo $sqcrm_record_id;?>},
				<?php
				$e_change_due_date = new Event("Tasks->eventChangeTaskDueDate");
				$e_change_due_date->setEventControler("/ajax_evctl.php");
				$e_change_due_date->setSecure(false);
				?>
				url: "<?php echo $e_change_due_date->getUrl(); ?>",
				success: function(data) {
					if (data.trim() === '1') {
						loadActivityAfterAction();
					} else {
						display_js_error(data,'js_errors');
					}
				}
			});
		}
	});
	
	// task close 
	$('#task-detail-section').on('click', '#task-close-icon', function() {
		$("#remove_confirm").modal('show');
		$("#remove_confirm .btn-primary").off('click');
		$("#remove_confirm .btn-primary").click(function() {
			$("#remove_confirm").modal('hide');
			$.ajax({
				type: 'POST',
				data: {idtasks:<?php echo $idtask;?>, idproject:<?php echo $sqcrm_record_id;?>,type:1},
				<?php
				$e_close_task = new Event("Tasks->eventCloseReopenTask");
				$e_close_task->setEventControler("/ajax_evctl.php");
				$e_close_task->setSecure(false);
				?>
				url: "<?php echo $e_close_task->getUrl(); ?>",
				success: function(data) {
					if (data.trim() == '1') {
						var reopenHtml = '<a href="#" class="btn btn-primary btn-xs" id= "task-reopen-icon">';
							reopenHtml += '<i class="glyphicon glyphicon-open-file"></i> ';
							reopenHtml += REOPEN_LW;
							reopenHtml += '</a></span>';

						$('#task-detail-section #task-close-reopen-icon-block').html(reopenHtml);
						$('#task-status-block').html('<span class="label label-danger" style="font-size: 16px;">'+CLOSED+'</span>');
						loadActivityAfterAction();
					} else {
						display_js_error(data,'js_errors');
					}
				}
			});
		});
	});
	
	// task re-open
	$('#task-detail-section').on('click', '#task-reopen-icon', function() {
		$("#remove_confirm").modal('show');
		$("#remove_confirm .btn-primary").off('click');
		$("#remove_confirm .btn-primary").click(function() {
			$("#remove_confirm").modal('hide');
			$.ajax({
				type: 'POST',
				data: {idtasks:<?php echo $idtask;?>, idproject:<?php echo $sqcrm_record_id;?>, type:2},
				<?php
				$e_close_task = new Event("Tasks->eventCloseReopenTask");
				$e_close_task->setEventControler("/ajax_evctl.php");
				$e_close_task->setSecure(false);
				?>
				url: "<?php echo $e_close_task->getUrl(); ?>",
				success: function(data) {
					if (data.trim() == '1') {
						var openHtml = '<a href="#" class="btn btn-primary btn-xs" id= "task-close-icon">';
							openHtml += '<i class="glyphicon glyphicon-off"></i> ';
							openHtml += CLOSE_LW;
							openHtml += '</a></span>';

						$('#task-detail-section #task-close-reopen-icon-block').html(openHtml);
						$('#task-status-block').html('<span class="label label-success" style="font-size: 16px;">'+OPEN+'</span>');
						
						loadActivityAfterAction();
						
					} else {
						display_js_error(data,'js_errors');
					}
				}
			});
		});
	});
});

// custom parse function for SimpleMDE to support emoji and mentions
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
			parsedData = data;
		}
	});
	
	return parsedData;
}
</script>