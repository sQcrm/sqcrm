<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Home page view
* @author Abhik Chakraborty
*/  
?>
<script type="text/javascript" src="/js/jquery/plugins/jquery.form.js"></script>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="datadisplay-outer">
					<div id="message"></div>
					<div class="box_content_header"><?php echo _('Change password');?>
						<hr class="form_hr">
						<br />
						<?php
						$e_change_pass = new Event("\cpanel_user\User->eventChangePassword");
						echo '<form class="" id="User__eventChangePassword" name="User__eventChangePassword" action="'.CPANEL_EVENTCONTROLER_PATH.'eventcontroler.php" method="post">';
						echo $e_change_pass->getFormEvent();
						?>
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
						<hr class="form_hr">
						<div id="settings_currency">
							<input type="submit" class="btn btn-primary" id="" value="<?php echo _('update');?>"/>
						</div>
						</form>
					</div>
					<div class="box_content_header"><?php echo _('Change your profile avatar');?>
						<hr class="form_hr">
						<br />
						<?php
						$e_logo_up = new Event("\cpanel_user\User->eventUploadUserAvatar");
						echo '<form class="form-horizontal" id="User__eventUploadUserAvatar" name="User__eventUploadUserAvatar" action="'.CPANEL_EVENTCONTROLER_PATH.'eventcontroler.php" method="post" enctype="multipart/form-data">';
						echo $e_logo_up->getFormEvent();
						echo FieldType12::display_field('contact_avatar',$_SESSION["do_cpaneluser"]->contact_avatar,'l');
						?>
						<hr class="form_hr">
						<div id="settings_currency">
							<input type="submit" class="btn btn-primary" id="" value="<?php echo _('update');?>"/>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {  
	var options = {
		target: '#message', //Div tag where content info will be loaded in
		url:'<?php echo CPANEL_EVENTCONTROLER_PATH ;?>ajax_evctl.php', //The php file that handles the file that is uploaded
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
			}
		}
	};
    
	$('#User__eventUploadUserAvatar').submit(function() {
		$(this).ajaxSubmit(options);
		return false;
	});
	
	var options1 = {
		target: '#message', //Div tag where content info will be loaded in
		url:'<?php echo CPANEL_EVENTCONTROLER_PATH ;?>ajax_evctl.php', //The php file that handles the file that is uploaded
		beforeSubmit: function() {
		},
		success:  function(data) {
			if (data.trim() == '1') {
				var succ_element = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>'+PASSWORD_UPDATED_SUCCESSFULLY+'</strong></div>';
				$("#message").html(succ_msg);
				$("#message").show();
			} else {
				var succ_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
				var succ_msg = succ_element+'<strong>'+data.trim()+'</strong></div>';
				$("#message").html(succ_msg);
				$("#message").show();
			}
		}
	};
	
	$('#User__eventChangePassword').submit(function() {
		var err = '';
		if ($('#password').val() == '') {
			err = 'Please enter password' ;
		} else if ($('#password').val().length < 8) {
			err = 'Password length should be minimum of 8 characters' ;
		} else if ($('#password').val() != $('#confirm_password').val()) {
			err = 'Password and confirm password is not matching' ;
		}
		if (err != '') {
			var succ_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var succ_msg = succ_element+'<strong>'+err+'</strong></div>';
			$("#message").html(succ_msg);
			$("#message").show();
			return false ;
		} else {
			$(this).ajaxSubmit(options1);
			return false;
		}
	});
	
}); // end document.ready
</script>