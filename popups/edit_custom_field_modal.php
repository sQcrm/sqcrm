<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Change password modal
* @author Abhik Chakraborty
*/
include_once("config.php");
  
$idmodule =  (int)$_GET["idmodule"];
$referrar = $_GET["referrar"];
$idfields = (int)$_GET["sqrecord"];
$do_custom_fields = new CustomFields();
$do_custom_fields->getId($idfields);
$field_validation = $do_custom_fields->field_validation ;
$field_validation_data = array();
 
if ($field_validation != '') {
	$field_validation_data = json_decode($field_validation,true);
}
  
if ($do_custom_fields->field_type == 5 || $do_custom_fields->field_type == 6) {
	$do_combo_values = new ComboValues();
	$do_combo_values->get_combo_values($idfields);
	$pick_data = '';
	while ($do_combo_values->next()) {
		$pick_data .= $do_combo_values->combo_value."\n" ; //adding new lines for the text area in pick options
	}
}

  $allow = true;
  $e_add = new Event("CustomFields->eventEditCustomField");
  echo '<form class="form-horizontal" id="CustomFields__eventEditCustomField" name="CustomFields__eventEditCustomField" action="/eventcontroler.php" method="post">';
  echo $e_add->getFormEvent();
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Edit custom field')?></span></h3>
		</div>
		<div class="modal-body">
			<div style = "float:left;margin-right:20px;">
				<input type="hidden" name="idmodule_ed" id="idmodule_ed" value="<?php echo $do_custom_fields->idmodule; ?>">
				<input type="hidden" name="idfields_ed" id="idfields_ed" value="<?php echo $idfields ; ?>">
				<input type="hidden" name="custom_field_type_ed" id="custom_field_type_ed" value="<?php echo $do_custom_fields->field_type ; ?>">
				<div id="cf_data" >
					<div id="cf_js_errors" style="display:none;"></div>
					<div id="" style="display:block;">
						<?php
						echo _('Label :');
						?>
						<input type="text" class="form-control input-sm" name="cf_label_ed" id="cf_label_ed" value="<?php echo $do_custom_fields->field_label;?>">
					</div>
					<?php
					if (count($field_validation_data)>0 && array_key_exists("required",$field_validation_data)) {
						$cf_req_checked = '';
						if ($field_validation_data) $cf_req_checked = "CHECKED";
					}
					?>
					<div id="" style="display:block;"><br />
						<?php
						echo _('Required :');
						?>
						<input type="checkbox" name="cf_req_ed" id="cf_req_ed" <?php echo $cf_req_checked;?> >
					</div>
					
					<div id="" style="display:none;"><br />
						<?php
						echo _('Length :');
						?>
					</div>
					<?php
					$cf_max_len_div_ed_style = 'style="display:none;"';
					if ((count($field_validation_data)>0 && array_key_exists("maxlength",$field_validation_data))
					|| ($do_custom_fields->field_type == 1 && $cf_req_checked !='')
					) {
						$cf_max_len_div_ed_style = 'style="display:block;"';
					}
					?>
					<div id="cf_max_len_div_ed" <?php echo $cf_max_len_div_ed_style; ?> ><br />
						<?php 
						echo _('Max Length');
						?>
						<input type="text"  class="form-control input-sm" name="cf_max_len_ed" id="cf_max_len_ed" value="<?php echo $field_validation_data["maxlength"]?>">
					</div>
					
					<?php
					$cf_min_len_div_ed_style = 'style="display:none;"';
					if( (count($field_validation_data)>0 && array_key_exists("minlength",$field_validation_data) )
						|| ( $do_custom_fields->field_type == 1 && $cf_req_checked !='')
					){
						$cf_min_len_div_ed_style = 'style="display:block;"';
					}
					?>
					<div id="cf_min_len_div_ed" <?php echo $cf_min_len_div_ed_style; ?> >
						<br />
						<?php 
						echo _('Min Length');
						?>
						<input type="text" class="form-control input-sm" name="cf_min_len_ed" id="cf_min_len_ed" value="<?php echo $field_validation_data["minlength"]?>" >
					</div>
					
					<?php 
					if ($pick_data != '') {
					?>
					<div id="" style="display:block;">
						<br />
						<?php
						echo _('Values');
						?>
						<textarea name="cf_pick_ed" id="cf_pick_ed" cols=7 rows=6 class="form-control input-sm"><?php echo $pick_data; ?></textarea>
					</div>
					<?php 
					} 
					?>
					<?php
					$cf_pick_notequal_ed_div_style = 'style="display:none;"';
					if (count($field_validation_data)>0 && array_key_exists("notEqual",$field_validation_data) && $cf_req_checked != '') {
						$cf_pick_notequal_ed_div_style = 'style="display:block;"';
					}
					?>
					<div id="cf_pick_notequal_ed_div" <?php echo $cf_pick_notequal_ed_div_style ; ?> >
						<br />
						<?php
						echo _('Not Equal :')
						?>
						<input type="text" class="form-control input-sm" name="cf_pick_notequal_ed" id="cf_pick_notequal_ed" size=5 value="<?php echo $field_validation_data["notEqual"];?>">
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
	$("#cf_req_ed").click(function() {
		if ($("#custom_field_type_ed").val() == 5) {
			if ($("#cf_req_ed").is(':checked')) {
				$("#cf_pick_notequal_ed_div").show();
			} else {
				$("#cf_pick_notequal_ed_div").hide();
			}
		}
		
		if ($("#custom_field_type_ed").val() == 1) {
			if ($("#cf_req_ed").is(':checked')) {
				$("#cf_max_len_div_ed").show();
				$("#cf_min_len_div_ed").show();
			} else {
				$("#cf_max_len_div_ed").hide();
				$("#cf_min_len_div_ed").hide();
			}
		}
	});
	
	$('#CustomFields__eventEditCustomField').submit( function() {
		var error_msg = '';
		var custom_field_type = $("#custom_field_type_ed").val() ;
		
		if ($("#cf_label_ed").val() == '') {
			display_js_error(CUSTOM_FIELD_LABEL_REQUIRE,'cf_js_errors');
			return false ;
		}
      
		if (custom_field_type == 1 || custom_field_type == 7 || custom_field_type == 8) {
			var numeric_values = /^[0-9]+$/;
			if ($("#cf_max_len_ed").val() != '') { 
				if (!numeric_values.test($("#cf_max_len_ed").val())) {
					display_js_error(CUSTOM_FIELD_LENGTH_NUMERIC_VALUE,'cf_js_errors');
					return false ;
				}
			}
			
			if ($("#cf_min_len_ed").val() != '') {
				if (!numeric_values.test($("#cf_min_len_ed").val())) {
					display_js_error(CUSTOM_FIELD_LENGTH_NUMERIC_VALUE,'cf_js_errors');
					return false ;
				}
			}
		}

		if (custom_field_type == 5 || custom_field_type == 6) {
			if ($("#cf_pick_ed").val() == '') {
				display_js_error(CUSTOM_FIELD_OPTION_VALUES_REQUIRE,'cf_js_errors');
				return false ;
			}
			var special_characters = "!@#$%^&*()+=-[]\\\';,{}|\";<>?";
			var cnt = 0 ;
			
			for (var i=0 ;i<$("#cf_pick_ed").val().length;i++) {
				if (special_characters.indexOf($("#cf_pick_ed").val().charAt(i)) != -1) {
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