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
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-info"><?php echo _('Change Password')?></span></h3>
		</div>
		<div class="modal-body">
			<div class="datadisplay-outer">
				<div class="form-group">  
					<label class="control-label" for="password"><?php echo _('Password')?></label>  
					<div class="controls">  
						<input type="password" class="form-control input-sm" id="password" name="password"> 
					</div>
				</div>
				<div class="form-group">  
					<label class="control-label" for="confirm_password"><?php echo _('Confirm Password')?></label>  
					<div class="controls">  
						<input type="password" class="form-control input-sm" id="confirm_password" name="confirm_password"> 
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> <?php echo _('Close');?></a>
			<input type="submit" class="btn btn-primary" value="<?php echo _('Save Changes')?>"/>
		</div>
		</form>
	</div>
</div>
<?php 
} else { ?>
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3><span class="label label-warning"><?php echo _('WARNING')?></span></h3>
		</div>
		<div class="modal-body">
			<div class="alert alert-danger">
				<?php echo _('You do not have permission to perform this operation');?>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default active" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> <?php echo _('Close');?></a>
		</div>
	</div>
</div>
<?php 
}
?>
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