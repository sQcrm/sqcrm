<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Queue view
* @author Abhik Chakraborty
*/  
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid" id="queue_data"></div>
		</div>
	</div>
</div>

<div class="modal hide datadisplay-outer" id="entity">
	<div class="modal-body datadisplay-outer" id="entity-detail-modal"></div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
	</div>
</div>

<div class="modal hide datadisplay-outer" id="edit_queue">
	<div class="modal-body datadisplay-outer" id="queue-edit-modal"></div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="submit" class="btn btn-primary update_queue_submit" id="" value="<?php echo _('update')?>"/>
	</div>
</div>

<div class="modal hide" id="delete_queue">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
	</div>
	<div class="modal-body">
		<?php echo _('Are you sure you want to delete the queue.');?>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="submit" class="btn btn-primary delete_queue_submit" id="" value="<?php echo _('delete')?>"/>
	</div>
</div>

<script>
$(document).ready(function() {    
	// load the queue content on body load
	$.ajax({
		type: "GET",
		url: "list",
		data : "ajaxreq="+true+"&module=<?php echo $module;?>&rand="+generateRandonString(10),
		success: function(result) { 
			$('#queue_data').html(result) ;
		},
		beforeSend: function() {
			$('#queue_data').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
    });

    // display the entity detail on clicking the entity from queue list
	$(".container-fluid").on('click','.entity-detail', function(e) {
		var arr = this.id.split('_');
		$("#entity").modal('show');
		$.ajax({
			type: "GET",
			url: '/modules/'+arr[0]+'/detail',
			data : "sqrecord="+arr[1]+"&ajaxreq="+true+"&onlyData="+true,
			success: function(result) { 
				$('#entity-detail-modal').html(result) ;
				return false ;
			},
			beforeSend: function() {
				$('#entity-detail-modal').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
			}
		}) ;
	}) ;	
	
	// display the edit queue modal
	$(".container-fluid").on('click','.edit-queue', function(e) {
		var id = this.id ;
		$("#edit_queue").modal('show');
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
				$('.update_queue_submit').attr('id',id);
				return false ;
			},
			beforeSend: function() {
				$('#queue-edit-modal').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
			}
		}) ;
	}) ;
	
	// update the queue data
	$('.update_queue_submit').click(function() {
		var queue_date = $('#queue_date').val() ;
		// idqueue retrieved from element
		var id = this.id ;
		// current table id - today,tomorrow,later
		var current_table_id = $('#'+id).closest('table').attr('id') ;
		// queue details within the current table id
		var current_tr_content = $('div#queue_data table#'+current_table_id+' tr#'+id).html() ;
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
						// hold the return day after editing the queue
						var changed_day = result.trim() ; 
						if (current_table_id != changed_day) {
							var section_markup = '';
							//remove the queue from the current table
							$('div#queue_data table#'+current_table_id+' tr#'+id).remove() ;
							// if the changed day block is empty then construct the table before copying the changed queue
							if ($('#'+changed_day).length == 0) {
								section_markup = '<table class="datadisplay" id="'+changed_day+'">';
								section_markup += '<tr id="'+id+'">'+current_tr_content+'</tr>' ;
								section_markup += '</table>' ;
								$('div#queue_data div#content_'+changed_day+' #msg').remove() ;
								$('div#queue_data div#content_'+changed_day).append(section_markup) ;
							} else {
								// changed day block is already has some data so just prepend the copied queue
								$('div#queue_data table#'+changed_day).prepend('<tr id="'+id+'">'+current_tr_content+'</tr>') ;
							}
							// if after removing the queue there is no more data then display no queue data message
							if ($('div#queue_data table#'+current_table_id+' tr').length == 0) {
								$('div#queue_data table#'+current_table_id).remove() ;
								var msg = '<strong id="msg">No queue data found !</strong>';
								$('div#queue_data div#content_'+current_table_id).append(msg) ;
							}
							$("#edit_queue").modal('hide');
						}
					} else {
						display_js_error(result.trim(),'js_errors');
					}
					return false ;
				}
			}) ;
		}
	}) ;
	
	// display the delete queue modal and on submit delete the queue
	$(".container-fluid").on('click','.delete-queue', function(e) {
		var id = this.id ;
		$('.delete_queue_submit').attr('id',id);
		$("#delete_queue").modal('show');
		$('.delete_queue_submit').click(function() {
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
						var current_table_id = $('#'+id).closest('table').attr('id') ;
						$('div#queue_data table#'+current_table_id+' tr#'+id).remove() ;
						if ($('div#queue_data table#'+current_table_id+' tr').length == 0) {
							$('div#queue_data table#'+current_table_id).remove() ;
							var msg = '<strong id="msg">No queue data found !</strong>';
							$('div#queue_data div#content_'+current_table_id).append(msg) ;
						}
						display_js_success(QUEUE_DELETED_SUCCESSFULLY,'js_errors') ;
					} else {
						display_js_error(result.trim(),'js_errors') ;
					}
				},
				beforeSend: function() {
					$('#delete_queue').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
				}
			}) ;
			return false ;
		}) ;
	}) ;
}) ;
</script>