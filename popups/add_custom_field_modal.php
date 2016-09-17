<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Change password modal
* @author Abhik Chakraborty
*/
include_once("config.php");
  
$idmodule =  $_GET["idmodule"];
$referrar = $_GET["referrar"];
$allow = true;
$e_add = new Event("CustomFields->eventAddCustomField");
echo '<form class="" id="CustomFields__eventAddCustomField" name="CustomFields__eventAddCustomField" action="/eventcontroler.php" method="post">';
echo $e_add->getFormEvent();
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Add a new custom field')?></span></h3>
		</div>
		<div class="modal-body">
			<div style="float:left;width:200px;" class="box_content">
				<ul class="nav nav-list" id="cf_selector">
					<li class="" id="1"><a href="#"><img src="/themes/images/text-box.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Text Box');?></a></li>
					<li class="" id="2"><a href="#"><img src="/themes/images/text-area.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Text Area');?></a></li>
					<li class="" id="7"><a href="#"><img src="/themes/images/email.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Email');?></a></li>
					<li class="" id="8"><a href="#"><img src="/themes/images/url.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Website');?></a></li>
					<li class="" id="9"><a href="#"><img src="/themes/images/date.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Date');?></a></li>
					<li class="" id="5"><a href="#"><img src="/themes/images/picklist.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Pick List');?></a></li>
					<li class="" id="3"><a href="#"><img src="/themes/images/checkbox.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Check Box');?></a></li>
					<li class="" id="6"><a href="#"><img src="/themes/images/picklist.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Multi Select Combo');?></a></li>
					<li class="" id="10"><a href="#"><img src="/themes/images/date.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Time');?></a></li>
					<li class="" id="210"><a href="#"><img src="/themes/images/twitter.png" style="vertical-align:center;margin-right:4px;"><?php echo _('Twitter Handler');?></a></li>
				</ul>
			</div>
			<div style = "float:left;margin-left:240px;position:absolute;margin-right:20px;">
				<input type="hidden" name="idmodule" id="idmodule" value="<?php echo $idmodule; ?>">
				<input type="hidden" name="custom_field_type" id="custom_field_type" value="">
				<div id="cf_data" >
					<div id="cf_js_errors" style="display:none;"></div>
					<div id="cf_label_div" style="display:none;">
						<?php
						echo _('Label :');
						?>
						<input type="text" name="cf_label" id="cf_label" class="form-control input-sm">
					</div>
					<div id="cf_req_div" style="display:none;"><br />
						<?php
						echo _('Required :');
						?>
						<input type="checkbox" name="cf_req" id="cf_req">
					</div>
					<div id="cf_len_div" style="display:none;"><br />
						<?php
						echo _('Length :');
						?>
						<input type="text" name="cf_len" id="cf_len" size=5 class="form-control input-sm">
					</div>
					<div id="cf_max_len_div" style="display:none;"><br />
						<?php 
						echo _('Max Length');
						?>
						<input type="text" name="cf_max_len" id="cf_max_len" class="form-control input-sm">
					</div>

					<div id="cf_min_len_div" style="display:none;"><br />
						<?php 
						echo _('Min Length');
						?>
						<input type="text" name="cf_min_len" id="cf_min_len" class="form-control input-sm">
					</div>
					
					<div id="cf_pick_div" style="display:none;"><br />
						<?php
						echo _('Values');
						?>
						<textarea name="cf_pick" id="cf_pick" cols=7 rows=6 class="form-control input-sm"></textarea>
					</div>
					<div id="cf_pick_notequal_div" style="display:none;"><br />
						<?php
						echo _('Not Equal :')
						?>
						<input type="text" name="cf_pick_notequal" id="cf_pick_notequal" size=5 class="form-control input-sm">
					</div>
				</div>
			</div>
		</div>
		<div class="clear_float"></div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
			<input type="submit" class="btn btn-primary" value="<?php echo _('Save Changes')?>"/>
		</div>
		</form>
	</div>
</div>

<script>
$(document).ready(function() {  
	// Check which li element is clicked and then make the clicked on active and rest not active
	$("#cf_selector li").click(function() {
		var li_id = $(this).attr('id') ;
		$("#cf_selector li").each( function() {
			$(this).attr("class","");
		});
		$(this).attr("class","active");
		// Generate the input form fields depending on what is selected for custom field
		$("#custom_field_type").val(li_id);
		$("#cf_label_div").show();
		$("#cf_req_div").show();

		if (li_id == 1 || li_id == 7 || li_id == 8) {
			$("#cf_len_div").show();
		} else {
			$("#cf_len_div").hide();
		}
    
		if (li_id == 5 || li_id == 6) {
			$("#cf_pick_div").show();
		} else {
			$("#cf_pick_div").hide();
		}
	});
    
	$("#cf_req").click(function() {
		if ($("#custom_field_type").val() == 5) {
			if ($("#cf_req").is(':checked')) {
				$("#cf_pick_notequal_div").show();
			} else {
				$("#cf_pick_notequal_div").hide();
			}
		}
		
		if ($("#custom_field_type").val() == 1) {
			if ($("#cf_req").is(':checked')) {
				$("#cf_max_len_div").show();
				$("#cf_min_len_div").show();
			} else {
				$("#cf_max_len_div").hide();
				$("#cf_min_len_div").hide();
			}
		}
	});

	$('#CustomFields__eventAddCustomField').submit( function() {
		var error_msg = '';
		var custom_field_type = $("#custom_field_type").val() ;
		if (custom_field_type == '') {
			display_js_error(CUSTOM_FIELD_FIELDTYPE_REQUIRE,'cf_js_errors');
			return false ;
		}
      
		if ($("#cf_label").val() == '') {
			display_js_error(CUSTOM_FIELD_LABEL_REQUIRE,'cf_js_errors');
			return false ;
		}
      
		if (custom_field_type == 1 || custom_field_type == 7 || custom_field_type == 8) {
			if ($("#cf_len").val() == '' || $("#cf_len").val() == 0) {
				display_js_error(CUSTOM_FIELD_LENGTH_REQUIRE,'cf_js_errors');
				return false ;
			}
			var numeric_values = /^[0-9]+$/;
			if (!numeric_values.test($("#cf_len").val())) {
				display_js_error(CUSTOM_FIELD_LENGTH_NUMERIC_VALUE,'cf_js_errors');
				return false ;
			}
        
			if ($("#cf_max_len").val() != '') {
				if (!numeric_values.test($("#cf_max_len").val())) {
					display_js_error(CUSTOM_FIELD_LENGTH_NUMERIC_VALUE,'cf_js_errors');
					return false ;
				}
			}
        
			if ($("#cf_min_len").val() != '') {
				if (!numeric_values.test($("#cf_min_len").val())) {
					display_js_error(CUSTOM_FIELD_LENGTH_NUMERIC_VALUE,'cf_js_errors');
					return false ;
				}
			}
		}

		if (custom_field_type == 5 || custom_field_type == 6) {
			if ($("#cf_pick").val() == '') {
				display_js_error(CUSTOM_FIELD_OPTION_VALUES_REQUIRE,'cf_js_errors');
				return false ;
			}
			var special_characters = "!@#$%^&*()+=-[]\\\';,{}|\";<>?";
			var cnt = 0 ;
			for (var i=0 ;i<$("#cf_pick").val().length;i++) {
				if (special_characters.indexOf($("#cf_pick").val().charAt(i)) != -1) {
					cnt++;
				}
			}
			if (cnt > 0) {
				display_js_error(CUSTOM_FIELD_SPECIAL_CHARCTER_NOT_ALLOWED,'cf_js_errors');
				return false ;
			}
		}
	});
}); // end document.ready
</script>