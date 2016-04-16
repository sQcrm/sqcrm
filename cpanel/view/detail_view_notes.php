<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 

/**
* Detail view right section NOTES 
* @author Abhik Chakraborty
*/  
$do_notes = new Notes();
?>
<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<div class="box_content">
	<div id="no_show" style="display:none;"></div>
	<div id="message"></div>
	<div id="entiry_notes">
		<div id="add_note">
			<?php
			echo _('Add Note (use : for loading the emoji and @ for users)');
			?> <br />
			<?php
			$e_add_notes = new Event("\cpanel_notes\Notes->eventAddNotes");
			$e_add_notes->addParam("idmodule",$module_id);
			$e_add_notes->addParam("module",$module);
			$e_add_notes->addParam("sqrecord",$sqcrm_record_id);
			echo '<form class="form-horizontal" id="Notes__eventAddNotes" name="Notes__eventAddNotes" action="'.CPANEL_EVENTCONTROLER_PATH.'eventcontroler.php" method="post" enctype="multipart/form-data">';
			echo $e_add_notes->getFormEvent();
			?>
			<?php 
			FieldType200::display_field('entity_notes','','expand_text_area');
			?>
			<br /><br />
			<?php
			FieldType21::display_field('note_files');
			?>
			<br /><br />
			<div id="notes_submit">
				<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
			</div>
			</form>
		</div>
		<hr class="form_hr">
		<div id="notes_entry">
		<!-- note content section loading with ajax -->
		</div> 
		<div id='load_more_notes' style="display:none;">
			<!--<button class="btn btn-primary btn-large" id="load_more_notes_btn"><?php echo _('more');?></button>-->
		</div>
		<!-- delete notes confirm modal -->
		<div class="modal fade hide" id="delete_entity_notes">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<span class="badge badge-warning"><?php echo _('WARNING!');?></span>
			</div>
			<div class="modal-body">
				<?php echo _('Are you sure you want to delete the note.');?>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close'); ?></a>
				<input type="submit" class="btn btn-primary delete_entity_note_submit" id="" value="<?php echo _('delete')?>"/>
			</div>
		</div>
	</div>
</div>
<script>
//Setting up the sql start and max as a global and used in the ajax call for loading more
var start = <?php echo $do_notes->sql_start ; ?> ;
var sql_max = <?php echo LIST_VIEW_PAGE_LENGTH ; ?> ;
var sql_end = sql_max ;
var cnt = 0 ;
$(document).ready(function() {
	// Load the notes when the page is loaded usig ajax
    load_notes(1);
    // Jquery Ajax submit plugin to submit the note form with Ajax
    var data_flag = 0 ;
    var options = {
		target: '#no_show', //Div tag where content info will be loaded in
		url:'/cpanel/ajax_evctl.php', //The php file that handles the file that is uploaded
		beforeSubmit: function() {
			$('#notes_submit').html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); //Including a preloader, it loads into the div tag with id uploader
		},
		success:  function(data) {
			//Here code can be included that needs to be performed if Ajax request was successful
			if (data.trim() == '1') {
				var err_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>';
				var err_message = err_element+'<strong>'+NOTES_REQUIRE+'</strong></div>';
				$("#message").html(err_message);
				$("#message").show();
				$("#notes_submit").html('<input type="submit" class="btn btn-primary" value="Save"/>');
			} else if (data.trim() == '2') {
				var err_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>';
				var err_message = err_element+'<strong>'+NOTES_CANT_BE_ADDED+'</strong></div>';
				$("#message").html(err_message);
				$("#message").show();
				$("#notes_submit").html('<input type="submit" class="btn btn-primary" value="Save"/>');
			} else {
				$("#notes_entry").prepend(data);
				$("#notes_submit").html('<input type="submit" class="btn btn-primary" value="Save"/>');
			}
			// this is done for setting the return data to null for the div #no_show else it will create conflict on edit 
			$('#no_show').html(''); 
			$('#entity_notes').val('');
		}
    };
    
    $('#Notes__eventAddNotes').submit(function() {
		$(this).ajaxSubmit(options);
        return false;
    });
    // Ajax submit ends here
    
    $("#notes_entry").on('mouseenter','.notes_content',function(e) {
		$(this).children(".notes_content_right").show();
    });
    
    $("#notes_entry").on('mouseleave','.notes_content',function(e) {
		$(this).children(".notes_content_right").hide();
    });
    
    // scroll to the note 
    var hash = location.hash.replace('#','');
    if (hash != '') {
		$(window).scrollTop(hash.offset().top).scrollLeft(hash.offset().left);
		//$('#'+hash).css("background-color","#FDFFBD");
	}
}); 

/*
* This function will be called once the note is submitted with the ajax submit
* Once the respose is received then the form will be reset to initial state.
* The text area for note will be set to null and if there are more than one file input available
* then remove the all but the first one.
*/
function reset_note_fields() {
	var submit_button = '<input type="submit" class="btn btn-primary" value="'+SAVE+'"/>';
	$("#notes_submit").html(submit_button);
	$("#entity_notes").val('');
	$('.more_file_inputs').each(function() {
		var current_id = (this.id);
		$("#"+current_id).html('');
	});
	$("#note_files").val('');
}

/*
* Initialization of the sql limit, when a note is added and we have already loaded more records then after save it reloads data
* and hence the sql limit is set to default so that on more load ajax call it starts from beginning not from previously set 
* values as global
*/
function init_sql_limit() {
	var start = <?php echo $do_notes->sql_start ; ?> ;
	var sql_max = <?php echo $do_notes->sql_max ; ?> ;
	var sql_end = sql_max ;
	var cnt = 0 ;
}

/*
* When a note is added then the notes will be reloaded with ajax
* Its using ajax_evctl.php for ajax call using the EventController function
*/
function load_notes(data_flag) {
	if (data_flag) { 
		init_sql_limit();
		$('#notes_entry').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		$.ajax({
			type: "GET",
			<?php
			$e_load_notes = new Event("\cpanel_notes\Notes->eventAjaxLoadNotes");
			$e_load_notes->setEventControler("/cpanel/ajax_evctl.php");
			$e_load_notes->addParam("idmodule",$module_id);
			$e_load_notes->addParam("sqrecord",$sqcrm_record_id);
			$e_load_notes->setSecure(false);
			?>
			url: "<?php echo $e_load_notes->getUrl(); ?>",
			beforeSubmit: function() {
				$('#notes_entry').html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
			},
			success:  function(html) {
				if (html != false) {
					$('#notes_entry').html(html);
					$('#load_more_notes').show();
				} else {
					$('#notes_entry').html('');
				}
			}
		});
	}
}
/*
* function to generate the note edit form
* Used ajax event controller
* while generating the edit form the current note content is saved in a hidden <p> so that its used if cancel is
* clicked to replace the note content
* also ensure that if the edit link is clicked multiple time then do not call ajax and reload the filed on each click
*/
function display_edit_notes(idnotes) {
	var notes_content_id = 'content_'+idnotes;
	var current_note = $("#"+notes_content_id).html();
	if ($("#content_hidden_"+idnotes).html() != '') {
		return false ;
	}
	$('#'+notes_content_id).html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
	$.ajax({
		type: "GET",
		<?php
        $e_dis_edit_notes = new Event("\cpanel_notes\Notes->eventAjaxDisplayUpdateNoteField");
        $e_dis_edit_notes->setEventControler("/cpanel/ajax_evctl.php");
        $e_dis_edit_notes->setSecure(false);
		?>
		url: "<?php echo $e_dis_edit_notes->getUrl(); ?>&idnotes="+idnotes,
		success:  function(html) {
			var ret_data = '';
			ret_data = html.trim();
			if (ret_data == '0') { 
				var succ_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>'+UNAUTHORIZED_EDIT+'</strong></div>';
				$("#message").html(succ_msg);
				$("#message").show();
				$('#'+notes_content_id).html(current_note);
			} else {
				$('#'+notes_content_id).html(ret_data);
				$("#content_hidden_"+idnotes).html(current_note);
			}
		}
    });
}

/*
* function to edit notes
* use ajax eventcontroler
* 
*/
function edit_notes(idnotes) {
	var notes_content_id = 'content_'+idnotes;
	var notes_text_area = 'entity_notes_edit_'+idnotes;
	if ($("#"+notes_text_area).val() == '') {
		display_js_error(NOTES_REQUIRE,'message');
		return false ;
	} else {
		$.ajax({
			type: "POST",
			<?php
			$e_edit_notes = new Event("\cpanel_notes\Notes->eventAjaxUpdateNotes");
			$e_edit_notes->setEventControler("/cpanel/ajax_evctl.php");
			$e_edit_notes->setSecure(false);
			?>
			url: "<?php echo $e_edit_notes->getUrl(); ?>&idnotes="+idnotes,
			data:"notes_edit_data="+encodeURIComponent($("#"+notes_text_area).val()),
			success:  function(html) {
				$('#'+notes_content_id).html(html);
				$("#content_hidden_"+idnotes).html('');
				var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>'+NOTES_UPDATED_SUCCESSFULLY+'</strong></div>';
				$("#message").html(succ_msg);
				$("#message").show();
			}
		});
	}
}

/*
* function to close the ajax edit form
*/
function close_edit_notes(idnotes)	{
	var notes_content_id = 'content_'+idnotes;
	$("#"+notes_content_id).html($("#content_hidden_"+idnotes).html());
	$("#content_hidden_"+idnotes).html('');
}

/*
* function to delete the note.
* use ajax eventcontroler
*/
function delete_notes(idnotes) {
	var notes_content_id = 'content_'+idnotes;
	var current_note = $("#"+notes_content_id).html();
	$("#delete_entity_notes").modal('show');
	$("#entiry_notes").on('click','.delete_entity_note_submit', function(e) {
		$('#'+notes_content_id).html('<img src="/themes/images/ajax-loader1.gif" border="0" />');
		$.ajax({
			type: "GET",
			<?php
			$e_del_notes = new Event("\cpanel_notes\Notes->eventAjaxDeleteNotes");
			$e_del_notes->setEventControler("/cpanel/ajax_evctl.php");
			$e_del_notes->setSecure(false);
			?>
			url: "<?php echo $e_del_notes->getUrl(); ?>&idnotes="+idnotes,
			success:  function(html) {
				var ret_data = html.trim();
				if(ret_data == '0') {
					var succ_element = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
					var succ_msg = succ_element+'<strong>'+UNAUTHORIZED_DELETE+'</strong></div>';
					$("#message").html(succ_msg);
					$("#message").show();
					$('#'+notes_content_id).html(current_note);
				} else {
					$('#note'+idnotes).remove();
					$('#note_separator_'+idnotes).remove();
					var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
					var succ_msg = succ_element+'<strong>'+NOTES_DELETED_SUCCESSFULLY+'</strong></div>';
					$("#message").html(succ_msg);
					$("#message").show();
				}
				$("#delete_entity_notes").modal('hide');
			}
		});
	});
}
</script>