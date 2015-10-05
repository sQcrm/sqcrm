<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Data History fields
* @author Abhik Chakraborty
*/  

?>
<div class="datadisplay-outer">
	<?php
	echo '<div id="message"></div>';
	$e_edit = new Event("DataHistoryFieldOption->eventAjaxSaveHistoryFields");
	$e_edit->addParam("mid",$dh_module);// see module/Settings/datahistory_settings.php
	echo '<form class="form-horizontal" id="DataHistoryFieldOption__eventAjaxSaveHistoryFields" name="DataHistoryFieldOption__eventAjaxSaveHistoryFields"  method="post" enctype="multipart/form-data">';
	echo $e_edit->getFormEvent();
	if (count($datahistory_fields) > 0) {
		foreach ($datahistory_fields as $key=>$fields_info) {
		?>
		<label class="checkbox" for="">
			<input type="checkbox" name="datahistory_fields[]" value="<?php echo $fields_info["idfields"];?>" <?php echo ($fields_info["selected"] == 'yes' ? 'CHECKED':'') ?>>
			<?php echo $fields_info["field_label"];?>
		</label>
		<br />
		<?php
		}
	}
	?>
	<hr class="form_hr">
	<div id="dhf_settings">
		<input type="submit" class="btn btn-primary" id="" value="<?php echo _('Save');?>"/>
	</div>
	</form>
</div>

<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var options = {
		target: '#message', //Div tag where content info will be loaded in
		url:'/ajax_evctl.php', //The php file that handles the file that is uploaded
		beforeSubmit: function() {
			$('#dhf_settings').html('<img src="/themes/images/ajax-loader1.gif" border="0" />'); //Including a preloader, it loads into the div tag with id uploader
		},
		success:  function(data) {
			//Here code can be included that needs to be performed if Ajax request was successful
			var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var succ_msg = succ_element+'<strong>'+data+'</strong></div>';
			$("#message").html(succ_msg);
			var submit_btn = '<input type="submit" class="btn btn-primary" id="" value="<?php echo _('Save');?>"/>';
			$("#dhf_settings").html(submit_btn);
		}
    };
    
    $('#DataHistoryFieldOption__eventAjaxSaveHistoryFields').submit(function() {
		$(this).ajaxSubmit(options);
        return false;
    });
    // Ajax submit ends here
    
    $("#dh_module_selector").change( function() {
		var mid = $("#dh_module_selector").val() ;
		$.ajax({
			type: "GET",
			url: "datahistory_settings",
			data : "cmid="+mid+"&ajaxreq="+true,
			success: function(result) { 
				$('#dh_entry').html(result) ;
			}
		});
	});
});
</script>