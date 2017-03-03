<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* task list view
* @author Abhik Chakraborty
*/ 
?>
<link href="/js/plugins/DataTables/datatables.min.css" rel="stylesheet">
<link href="/js/plugins/magicsuggest/magicsuggest.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/magicsuggest/magicsuggest.js"></script>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<div class="datadisplay-outer">
						<ol class="breadcrumb">
							<li>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"list")?>"><?php echo _('Project')?></a>
							</li>
							<li>
								<a href="<?php echo NavigationControl::getNavigationLink($module,"detail",$sqcrm_record_id)?>"><?php echo $project_name;?></a>
							</li>
						</ol>
						<hr class="form_hr">
						<form>
						<strong><?php echo _('Filter');?></strong>
						<hr class="form_hr">
						<div class="row">
							<div class="col-md-3">
								<?php echo _('Status'); ?>
								<select name="status" id="status" class="form-control input-sm">
									<option value="1" <?php echo ($status == 1 ? 'SELECTED' : '');?>><?php echo _('Open');?></option>
									<option value="2" <?php echo ($status == 2 ? 'SELECTED' : '');?>><?php echo _('Closed');?></option>
								</select>
							</div>
							<div class="col-md-3">
								<?php echo _('Label'); ?>
								<div id="labels" name="labels"></div>
							</div>
							
							<div class="col-md-3">
								<?php echo _('Assignee'); ?>
								<div id="assignee" name="assignee"></div>
							</div>
							
							<div class="col-md-3">
								<?php echo _('Priority'); ?>
								<div id="priority" name="priority"></div>
							</div>
							
						</div>
						<hr class="form_hr">
						<input type="submit" class="btn btn-primary" id="search-tasks-submit" value="<?php echo _('search');?>"/>
						<?php
						if (true === $search_on) { ?>
						<a class="btn btn-default" href="/modules/Project/<?php echo $sqcrm_record_id;?>/task/list" role="button"><?php echo _('clear search');?></a>
						<?php
						}
						?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<div class="datadisplay-outer">
						<?php
						if ($status == 1) {
						?>
						<span><a href="#" class="btn btn-primary btn-xs" id= "task-close-button"><i class="glyphicon glyphicon-off"></i> <?php echo _('close')?></a></span>
						<span><a href="#" class="btn btn-primary btn-xs" id= "task-priority-change-button"> <?php echo _('change priority')?></a></span>
						<span><a href="#" class="btn btn-primary btn-xs" id= "task-due-date-change-button"><i class="glyphicon glyphicon-calendar"></i> <?php echo _('change due date')?></a></span>
						<?php
						} else {
						?>
						<span><a href="#" class="btn btn-primary btn-xs" id= "task-re-open-button"><i class="glyphicon glyphicon-open-file"></i> <?php echo _('re-open')?></a></span>
						<?php
						}
						?>
						<span id="task-add-icon-block"><a href="/modules/Project/<?php echo $sqcrm_record_id;?>/task/add" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-tasks"></i> <?php echo _('new task')?></a></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<div class="datadisplay-outer">
						<table class="datadisplay dt-responsive dt-body-right" id="task-list" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th width="2%"><input type="checkbox" name="sel_all_list_data" id="sel_all_list_data"></th>
									<th><?php echo _('Title');?></th>
									<th><?php echo _('Labels');?></th>
									<th><?php echo _('Priority');?></th>
									<th><?php echo _('Assignne');?></th>
									<th><?php echo _('Due Date');?></th>
								</tr>
							</thead>
						</table>
						<?php
							echo $paginator; 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="confirm-task-close">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<div id="confirm-task-close-loading" style="display:none;">
					<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />
				</div>
				<div id="confirm-task-close-message">
					<?php echo _('Are you sure you want to close the selected tasks ?');?>
				</div>
			</div>
			<div class="modal-footer" id="confirm-task-close-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
				<input type="button" class="btn btn-primary" value="<?php echo _('Yes')?>"/>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="confirm-task-reopen">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
			</div>
			<div class="modal-body">
				<div id="confirm-task-reopen-loading" style="display:none;">
					<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />
				</div>
				<div id="confirm-task-reopen-message">
					<?php echo _('Are you sure you want to re-open the selected tasks ?');?>
				</div>
			</div>
			<div class="modal-footer" id="confirm-task-reopen-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
				<input type="button" class="btn btn-primary" value="<?php echo _('Yes')?>"/>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="confirm-task-priority-change">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php
				echo _('Change priority of selected tasks');
				?>
			</div>
			<div class="modal-body">
				<div id="confirm-task-priority-change-loading" style="display:none;">
					<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />
				</div>
				<div id="confirm-task-priority-change-html">
					<select name="change-priority-value" id="change-priority-value" class="form-control input-sm">
					<?php
					foreach ($task_priorities as $key=>$val) {
						echo '<option value="'.$val['id'].'">'.$val['priority'].'</option>';
					}
					?>
					</select>
				</div>
			</div>
			<div class="modal-footer" id="confirm-task-priority-change-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
				<input type="button" class="btn btn-primary" value="<?php echo _('Yes')?>"/>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="confirm-task-change-due-date">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php
				echo _('Change due date of selected tasks');
				?>
			</div>
			<div class="modal-body">
				<div id="confirm-task-change-due-date-loading" style="display:none;">
					<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />
				</div>
				<div id="confirm-task-change-due-date-html">
					<?php
						echo FieldType9::display_field('change_due_date');
					?>
				</div>
			</div>
			<div class="modal-footer" id="confirm-task-change-due-date-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>
				<input type="button" class="btn btn-primary" value="<?php echo _('Yes')?>"/>
			</div>
		</div>
	</div>
</div>

<script>
var taskOpenClose;

$(document).ready(function() {
	// init the magicSuggest for label selector
	var labelSelector = $('#labels').magicSuggest({
		allowFreeEntries: false,
		allowDuplicates: false,
		data: <?php echo json_encode($task_labels_selector);?>,
		name: 'labels',
		value : <?php echo json_encode($lables_searched_on);?>
	});
	
	var assigneeSelector = $('#assignee').magicSuggest({
		allowFreeEntries: false,
		allowDuplicates: false,
		data: <?php echo json_encode($project_members_selector);?>,
		name: 'assignee',
		value : <?php echo json_encode($assignee_searched_on);?>
	});
	
	var prioritySelector = $('#priority').magicSuggest({
		allowFreeEntries: false,
		allowDuplicates: false,
		data: <?php echo json_encode($task_priorities_selector);?>,
		name: 'priority',
		value : <?php echo json_encode($priority_searched_on);?>
	});
	
	oTable = $('#task-list').dataTable({
		"paging":   false,
        "info":     false,
        "bFilter" : false,
        "ordering": false,
        dom: 'Bfrtip',
        "aaData": <?php echo json_encode($records);?>
	});   
	
	$('#sel_all_list_data').click(function() {
		if ($(this).is(":checked")) {
			$('.sel_record').prop("checked",true);
		} else {
			$('.sel_record').prop("checked",false);
		}
	});
	
	$('#task-close-button').click(function() {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			display_js_error(SELECT_ONE_TASK_BEFORE_CLOSE,'js_errors');
		} else {
			taskOpenClose(sData, 1, 'confirm-task-close');
		}
		return false;
	});
	
	$('#task-re-open-button').click(function() {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			display_js_error(SELECT_ONE_TASK_BEFORE_REOPEN,'js_errors');
		} else {
			taskOpenClose(sData, 2, 'confirm-task-reopen');
		}
		return false;
	});
	
	taskOpenClose = function(sData, actionType, confirmModelId) {
		$('#'+confirmModelId).modal('show');
		$('#'+confirmModelId+' .btn-primary').off('click');
		$('#'+confirmModelId+' .btn-primary').click(function() {
			$('#'+confirmModelId+'-message').hide();
			$('#'+confirmModelId+'-loading').show();
			$('#'+confirmModelId+'-footer').hide();
			
			$.ajax({
				type: 'POST',
				data : {type:actionType,idproject:<?php echo $sqcrm_record_id;?>},
				<?php
				$e_change_task_status = new Event("Tasks->eventCloseReopenMultipleTask");
				$e_change_task_status->setEventControler("/ajax_evctl.php");
				$e_change_task_status->setSecure(false);
				?>
				url: "<?php echo $e_change_task_status->getUrl(); ?>&"+sData+"&rand="+generateRandonString(10),
				success: function(data) {
					var result = JSON.parse(data),
						redirect = true;
						
					if (result.status === 'ok') {
						display_js_success(result.message,'js_errors');
					} else if (result.status === 'partial') {
						display_js_success(result.message,'js_errors');
					} else if (result.status === 'fail') {
						display_js_error(result.message,'js_errors');
						redirect = false;
					}
					
					if (true === redirect) {
						setTimeout(function () {
							$('#'+confirmModelId).modal('hide');
							window.location.href = '/modules/Project/<?php echo $sqcrm_record_id;?>/task/list?status=<?php echo $status;?>';
						}, 1500);
					} else {
						$('#'+confirmModelId+'-message').show();
						$('#'+confirmModelId+'-loading').hide();
						$('#'+confirmModelId+'-footer').show();
						$('#'+confirmModelId).modal('hide');
					}
				}
			});
		});
	}
	
	$('#task-priority-change-button').click(function() {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			display_js_error(SELECT_ONE_TASK_BEFORE_PRIORITY_CHANGE,'js_errors');
		} else {
			$('#confirm-task-priority-change').modal('show');
			$('#confirm-task-priority-change .btn-primary').off('click');
			$('#confirm-task-priority-change .btn-primary').click(function() {
				var priority = $('#change-priority-value').val();
				$('#confirm-task-priority-change-html').hide();
				$('#confirm-task-priority-change-loading').show();
				$('#confirm-task-priority-change-footer').hide();
				
				$.ajax({
					type: 'POST',
					data : {priority:priority,idproject:<?php echo $sqcrm_record_id;?>},
					<?php
					$e_change_task_priority = new Event("Tasks->eventChangePriorityMultipleTask");
					$e_change_task_priority->setEventControler("/ajax_evctl.php");
					$e_change_task_priority->setSecure(false);
					?>
					url: "<?php echo $e_change_task_priority->getUrl(); ?>&"+sData+"&rand="+generateRandonString(10),
					success: function(data) {
						var result = JSON.parse(data),
						redirect = true;
						
						if (result.status === 'ok') {
							display_js_success(result.message,'js_errors');
						} else if (result.status === 'partial') {
							display_js_success(result.message,'js_errors');
						} else if (result.status === 'fail') {
							display_js_error(result.message,'js_errors');
							redirect = false;
						}
						
						if (true === redirect) {
							setTimeout(function () {
								$("#confirm-task-priority-change").modal('hide');
								window.location.href = '/modules/Project/<?php echo $sqcrm_record_id;?>/task/list';
							}, 1500);
						} else {
							$('#confirm-task-priority-change-html').show();
							$('#confirm-task-priority-change-loading').hide();
							$('#confirm-task-priority-change-footer').show();
							$("#confirm-task-priority-change").modal('hide');
						}
					}
				});
			});
		}
		return false;
	});
	
	$('#task-due-date-change-button').click(function() {
		var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			display_js_error(SELECT_ONE_TASK_BEFORE_DUEDATE_CHANGE,'js_errors');
		} else {
			$("#confirm-task-change-due-date").modal('show');
			$("#confirm-task-change-due-date .btn-primary").off('click');
			$("#confirm-task-change-due-date .btn-primary").click(function() {
				var due_date = $('#change_due_date').val();
				if (due_date == '') {
					display_js_error(SELECT_DUE_DATE_BEFORE_SAVE,'js_errors');
				} else { 
					$('#confirm-task-change-due-date-html').hide();
					$('#confirm-task-change-due-date-loading').show();
					$('#confirm-task-change-due-date-footer').hide();
					
					$.ajax({
						type: 'POST',
						data : {due_date:due_date,idproject:<?php echo $sqcrm_record_id;?>},
						<?php
						$e_change_duedate = new Event("Tasks->eventChangeDuedateMultipleTask");
						$e_change_duedate->setEventControler("/ajax_evctl.php");
						$e_change_duedate->setSecure(false);
						?>
						url: "<?php echo $e_change_duedate->getUrl(); ?>&"+sData+"&rand="+generateRandonString(10),
						success: function(data) {
							var result = JSON.parse(data),
							redirect = true;
							
							if (result.status === 'ok') {
								display_js_success(result.message,'js_errors');
							} else if (result.status === 'partial') {
								display_js_success(result.message,'js_errors');
							} else if (result.status === 'fail') {
								display_js_error(result.message,'js_errors');
								redirect = false;
							}
							
							if (true === redirect) {
								setTimeout(function () {
									$("#confirm-task-change-due-date").modal('hide');
									window.location.href = '/modules/Project/<?php echo $sqcrm_record_id;?>/task/list';
								}, 1500);
							} else {
								$('#confirm-task-change-due-date-html').show();
								$('#confirm-task-change-due-date-loading').hide();
								$('#confirm-task-change-due-date-footer').show();
								$("#confirm-task-change-due-date").modal('hide');
							}
						}
					});
				}
			});
		}
		return false;
	});
});
</script>