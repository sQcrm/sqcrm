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

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<h3><?php echo _('Edit values')?></h3>
</div>
<div class="modal-body">
	<div style = "float:left;margin-left:150px;">
		<div id="cf_data" >
			<div id="cf_js_errors" style="display:none;"></div>
			<?php 
			if ($pick_data != '') {
			?>
			<div id="" style="display:block;">
				<br />
				<textarea name="pick_values" id="pick_values" cols=10 rows=10><?php echo $pick_data; ?></textarea>
			</div>
			<?php 
			} else {
			?>
			<div id="" style="display:block;">
				<br />
				<textarea name="pick_values" id="pick_values" cols=10 rows=10></textarea>
			</div>
			<?php
			} 
			?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
	<input type="submit" class="btn btn-primary" value="<?php echo _('Save Changes')?>"/>
</div>
</form>
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