<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Change user avatar modal
* @author Abhik Chakraborty
*/
include_once("config.php");
?>
<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<div id="message"></div>
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h3><?php echo _('Change your profile avatar')?></h3>
	</div>
	<?php
	$e_logo_up = new Event("User->eventUploadUserAvatar");
	echo '<form class="form-horizontal" id="User__eventUploadUserAvatar" name="User__eventUploadUserAvatar"  method="post" enctype="multipart/form-data">';
	echo $e_logo_up->getFormEvent();
	?>
	<div class="modal-body">
		<div class="datadisplay-outer">
			<?php
			echo FieldType12::display_field('user_avatar',$_SESSION["do_user"]->user_avatar,'l');
			?>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-default" id="close-avatar-popup" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
		<input type="submit" class="btn btn-primary" value="<?php echo _('Save Changes')?>"/>
	</div>
	</form>
</div>
<script>
$(document).ready(function() {  
	var options = {
		target: '#message', //Div tag where content info will be loaded in
		url:'/ajax_evctl.php', //The php file that handles the file that is uploaded
		beforeSubmit: function() {
		},
		success:  function(data) {
			if (data.trim() == '0') {
				var succ_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>'+UPLOAD_ERROR+'</strong></div>';
				$("#message").html(succ_msg);
				$("#message").show();
			} else {
				var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>'+UPLOAD_SUCCESS+'</strong></div>';
				var profile_image = '<div class="circular_35" style="background-image: url(\''+data.trim()+'\')"></div>';
				$("#user-profile").html(profile_image);
				$("#message").html(succ_msg);
				$("#message").show();
				$( "#close-avatar-popup" ).trigger( "click" );
			}
		}
	};
    
	$('#User__eventUploadUserAvatar').submit(function() {
		$(this).ajaxSubmit(options);
		return false;
	});
}); // end document.ready
</script>