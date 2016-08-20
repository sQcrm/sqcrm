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
$referrar_module_id = (int)$_GET["referrar_module_id"];

$do_combo_values = new ComboValues();
$do_combo_values->get_combo_values($idfields);
$pick_data = '';
  
while ($do_combo_values->next()) {
	$pick_data .= $do_combo_values->combo_value."\n" ; //adding new lines for the text area in pick options
}
 
$allow = true;
$e_edit = new Event("ComboValues->eventEditComboValues");
$e_edit->addParam("idfields",$idfields);
$e_edit->addParam("referrar_module_id",$referrar_module_id);
echo '<form class="form-horizontal" id="ComboValues__eventEditComboValues" name="ComboValues__eventEditComboValues" action="/eventcontroler.php" method="post">';
echo $e_edit->getFormEvent();
?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Edit values')?></span></h3>
		</div>
		<div class="modal-body">
			<div style = "float:left;margin-left:150px;">
				<div id="cf_data" >
					<div id="cf_js_errors" style="display:none;"></div>
					<?php 
					if ($pick_data != '') {
					?>
					<div id="" style="display:block;">
						<textarea name="pick_values" id="pick_values" cols=30 rows=10 class="form-control input-sm"><?php echo $pick_data; ?></textarea>
						<br />
					</div>
					<?php 
					} else {
					?>
					<div id="" style="display:block;">
						<textarea name="pick_values" id="pick_values" cols=30 rows=10 class="form-control input-sm"></textarea>
						<br />
					</div>
					<?php
					} 
					?>
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
	$('#ComboValues__eventEditComboValues').submit( function() {
		var error_msg = '';
		if ($("#pick_values").val() == '') {
			display_js_error(CUSTOM_FIELD_OPTION_VALUES_REQUIRE,'cf_js_errors');
			return false ;
		}
		var special_characters = "!@#$%^&*()+=[]\\\';,{}|\";<>?";
		var cnt = 0 ;
		
		for (var i=0 ;i<$("#pick_values").val().length;i++) {
			if (special_characters.indexOf($("#pick_values").val().charAt(i)) != -1) {
				cnt++;
			}
		}
		
		if (cnt > 0) {
			display_js_error(CUSTOM_FIELD_SPECIAL_CHARCTER_NOT_ALLOWED,'cf_js_errors');
			return false ;
		}
	});
}); // end document.ready
</script>