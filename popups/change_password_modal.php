<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Change password modal
* @author Abhik Chakraborty
*/
include_once("config.php");

$module = $_GET["m"];
$sqcrm_record_id = $_GET["sqrecord"];
$fieldname = $_GET["fieldname"];
$allow = true;
if ($allow === true) {
	$e_del = new Event($module."->eventChangePassword");
	$e_del->addParam("sqrecord",$sqcrm_record_id);
	$e_del->addParam("fieldname",$fieldname);
	$e_del->addParam("next_page",NavigationControl::getNavigationLink($module,$return_page));
	echo '<form class="form-horizontal" id="'.$module.'__eventChangePassword" name="'.$module.'__eventChangePassword" action="/eventcontroler.php" method="post">';
	echo $e_del->getFormEvent();
?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3><?php echo _('Change Password')?></h3>
	</div>
	<div class="modal-body">
		<div class="control-group">  
			<label class="control-label" for="password"><?php echo _('Password')?></label>  
			<div class="controls">  
				<input type="password" class="input-xlarge-100" id="password" name="password"> 
			</div>
		</div>
		<div class="control-group">  
			<label class="control-label" for="confirm_password"><?php echo _('Confirm Password')?></label>  
			<div class="controls">  
				<input type="password" class="input-xlarge-100" id="confirm_password" name="confirm_password"> 
			</div>
		</div>  
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> <?php echo _('Close');?></a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Save Changes')?>"/>
	</div>
	</form>
<?php 
} ?>
<script>
$(document).ready(function() {
	$('#<?php echo $module?>__eventChangePassword').validate({
		rules: {
			password: {
				minlength: 8,
                required: true
			},
			confirm_password: {
				minlength: 8,
				required: true,
				equalTo:"#password"
			}
		},
		highlight: function(label) {
			$(label).closest('.control-group').addClass('error');
		},
		success: function(label) {
			label
			.text('OK!').addClass('valid')
			.closest('.control-group').addClass('success');
		}
	});
}); // end document.ready
</script>