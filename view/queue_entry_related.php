<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Queue view for entity detail page
* @author Abhik Chakraborty
*/  
?>
<?php
if (count($queue_data)> 0 && array_key_exists('idqueue',$queue_data)) {
?>
<?php
if ($queue_data['day'] == 'today') {
	$msg = 'In-queue for the day' ;
} elseif ($queue_data['day'] == 'tomorrow') {
	$msg = 'In-queue for tomorrow' ;
} elseif ($queue_data['day'] == 'later') {
	$msg = 'In-queue for later' ;
}
echo $msg ;
?>
	<?php
	if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',18) === true) {
	?>
	&nbsp;&nbsp;&nbsp;
	<a href="#" class="btn btn-primary edit-entity-queue" id="<?php echo $queue_data['idqueue'];?>">
		<i class="icon-white icon-edit"></i><?php echo _('change');?>
	</a>
<?php
	}
	if ($_SESSION["do_crm_action_permission"]->action_permitted('delete',18) === true) { 
	?>
	&nbsp;&nbsp;&nbsp;
	<a href="#" class="btn btn-primary delete-entity-queue" id="<?php echo $queue_data['idqueue'];?>">
		<i class="icon-white icon-trash"></i><?php echo _('remove');?>
	</a>
	<?php
	}
} else {
	if ($_SESSION["do_crm_action_permission"]->action_permitted('edit',18) === true) {
	?>
	<br />
	<a href="#" class="btn btn-primary add-entity-queue" id="<?php echo $queue_data['idqueue'];?>">
		<i class="icon-white icon-plus"></i><?php echo _('add to queue');?>
	</a>
	<?php
	}
?>
<?php
}
?>
<div class="modal hide datadisplay-outer" id="add_queue_entity">
	<div class="modal-body datadisplay-outer" id="queue-add-modal">
	<?php
	echo _('Queue date :: ') ;
	echo '<br />'.FieldType9::display_field('entity_queue_date');	 
	?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="submit" class="btn btn-primary add_queue_entity_submit" id="" value="<?php echo _('add')?>"/>
	</div>
</div>
<div class="modal hide datadisplay-outer" id="edit_queue_entity">
	<div class="modal-body datadisplay-outer" id="queue-edit-modal"></div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="submit" class="btn btn-primary update_queue_entity_submit" id="" value="<?php echo _('change')?>"/>
	</div>
</div>
<div class="modal hide" id="delete_queue_entity">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
	</div>
	<div class="modal-body">
		<?php echo _('Are you sure you want to remove the queue.');?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="submit" class="btn btn-primary delete_queue_entity_submit" id="" value="<?php echo _('remove')?>"/>
	</div>
</div>
<script>
$(document).ready(function() {
	// show the queue add modal
	$("#queue_section").on('click','.add-entity-queue', function(e) {
		$("#add_queue_entity").modal('show');
	});
	
	// add the entity to queue
	$("#queue_section").on('click','.add_queue_entity_submit', function(e) {
		var queue_date = $('#entity_queue_date').val() ;
		if (queue_date.trim() == '') {
			display_js_error(SELECT_QUEUE_DATE_BEFORE_SAVE,'js_errors');
			return false ;
		} else {
			$.ajax({
				type: "POST",
				<?php
				$e_event = new Event("Queue->eventAjaxAddQueue");
				$e_event->setEventControler("/ajax_evctl.php");
				$e_event->setSecure(false);
				?>
				url: "<?php echo $e_event->getUrl(); ?>",
				data: "date="+queue_date+"&related_module_id=<?php echo $related_module_id;?>&related_record_id=<?php echo $related_record_id;?>",
				success: function(result) { 
					if (result.trim() == '1') {
						display_js_success(QUEUE_ADDED_SUCCESSFULLY,'js_errors') ;
					} else {
						display_js_error(result.trim(),'js_errors') ;
					}
					$.ajax({
						type: "GET",
						url: "/modules/Queue/list",
						data : "ajaxreq="+true+"&module=Queue&rand="+generateRandonString(10)+"&related="+true+"&related_module_id=<?php echo $related_module_id;?>&related_record_id=<?php echo $related_record_id;?>",
						success: function(result) { 
							$('#queue_section').html(result) ;
						},
						beforeSend: function() {
							$('#queue_section').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
						}
					});
				},
				beforeSend: function() {
					$('#queue_section').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
				}
			});
			$("#add_queue_entity").modal('hide');
			return false ;
		}
	});
	
	// display the edit queue modal for the entity
	$("#queue_section").on('click','.edit-entity-queue', function(e) {
		var id = this.id ; 
		$("#edit_queue_entity").modal('show');
		$.ajax({
			type: "GET",
			<?php
			$e_event = new Event("Queue->eventAjaxGetQueueEditOptions");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>&id="+id,
			success: function(result) { 
				$('#queue-edit-modal').html(result) ;
				$("div#queue_section .update_queue_entity_submit").attr('id',id) ;
				return false ;
			},
			beforeSend: function() {
				$('#queue-edit-modal').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
			}
		}) ;	
	});
	
	// update the queue
	$("#queue_section").on('click','.update_queue_entity_submit', function(e) {
		var id = this.id ; 
		var queue_date = $('#queue_date').val() ;
		if (queue_date.trim() == '') {
			display_js_error(SELECT_QUEUE_DATE_BEFORE_SAVE,'js_errors');
			return false ;
		} else {
			$.ajax({
				type: "POST",
				<?php
				$e_event = new Event("Queue->eventAjaxUpdateQueue");
				$e_event->setEventControler("/ajax_evctl.php");
				$e_event->setSecure(false);
				?>
				url: "<?php echo $e_event->getUrl(); ?>",
				data: "date="+queue_date+"&id="+id,
				success: function(result) { 
					if (result.trim() == 'today' || result.trim() == 'tomorrow' || result.trim() == 'later') {
						display_js_success(QUEUE_UPDATED_SUCCESSFULLY,'js_errors') ;
						$.ajax({
							type: "GET",
							url: "/modules/Queue/list",
							data : "ajaxreq="+true+"&module=Queue&rand="+generateRandonString(10)+"&related="+true+"&related_module_id=<?php echo $related_module_id;?>&related_record_id=<?php echo $related_record_id;?>",
							success: function(result) { 
								$('#queue_section').html(result) ;
							},
							beforeSend: function() {
								$('#queue_section').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
							}
						});
					} else {
						display_js_error(result.trim(),'js_errors');
					}
				}
			}) ;
		}
		$('#edit_queue_entity').modal('hide');
		return false ;
	}) ;
	
	// show the delete queue modal
	$('#queue_section').on('click','.delete-entity-queue', function(e) {
		var id = this.id ; 
		$('#delete_queue_entity').modal('show');
		$('div#queue_section .delete_queue_entity_submit').attr('id',id) ;
	});
	
	// delete the queue
	$("#queue_section").on('click','.delete_queue_entity_submit', function(e) {
		var id = this.id ; 
		$.ajax({
			type: "POST",
			<?php
			$e_event = new Event("Queue->eventAjaxDeleteQueue");
			$e_event->setEventControler("/ajax_evctl.php");
			$e_event->setSecure(false);
			?>
			url: "<?php echo $e_event->getUrl(); ?>",
			data: "id="+id,
			success: function(result) { 
				if (result.trim() == '1') {
					display_js_success(QUEUE_DELETED_SUCCESSFULLY,'js_errors') ;
					$.ajax({
						type: "GET",
						url: "/modules/Queue/list",
						data : "ajaxreq="+true+"&module=Queue&rand="+generateRandonString(10)+"&related="+true+"&related_module_id=<?php echo $related_module_id;?>&related_record_id=<?php echo $related_record_id;?>",
						success: function(result) { 
							$('#queue_section').html(result) ;
						},
						beforeSend: function() {
							$('#queue_section').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
						}
					});
				} else {
					display_js_error(result.trim(),'js_errors') ;
				}
			}
		}) ;
		$('#delete_queue_entity').modal('hide');
		return false ;
	});
	
});
</script>