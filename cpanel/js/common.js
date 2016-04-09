// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
* @author Abhik Chakraborty
* Common JS methods used across the CRM
*/

// function to close the autoclose messages
$(document).ready( function() {
	$.ajax({
		type: "GET",
		url: '/cpanel/message.php',
		data : "clean_message=1&r="+generateRandonString(10)
	});
	window.setInterval(
		function() {
			$("#sqcrm_auto_close_messages").fadeTo(700,0).slideUp(700, function() {
				$(this).remove();
			});
		}, 
	5000);
});

/*
* Function to display the JS error in the top section of the CRM like showing serverside messages
* @param error_msg
* @param div_element, element within which the message should be added before showing it
*/
function display_js_error(error_msg,div_element) {
	error_msg = '<strong>'+error_msg+'</strong>';
	if (error_msg != '') {
		var error_html_start = '<div class="alert alert-error sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
		var error_html_end = '</div>';      
		if (div_element !='') {
			$("#"+div_element).html(error_html_start+error_msg+error_html_end);
			$("#"+div_element).show();
		}
	}
}

/*
* Function to display the JS success in the top section of the CRM like showing serverside messages
* @param success_msg
* @param div_element, element within which the message should be added before showing it
*/
function display_js_success(success_msg,div_element) {
	success_msg = '<strong>'+success_msg+'</strong>';
	if (success_msg != '') {
		var success_html_start = '<div class="alert alert-success sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
		var success_html_end = '</div>';      
		if (div_element !='') {
			$("#"+div_element).html(success_html_start+success_msg+success_html_end);
			$("#"+div_element).show();
		}
	}
}

/**
* function to generate a randon string with a specific length
* @param integer length
* @return string 
*/
function generateRandonString(length) {
	var strings = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ;
	var result = '';
	for (var i = length; i > 0; --i) result += strings[Math.round(Math.random() * (strings.length - 1))];
	return result;
}

/**
* function to load the user avatar change modal
*/
function changeUserAvatar() {
	var href = '/popups/change_user_avatar?idmodule=7&m=User';
	if (href.indexOf('#') == 0) { 
		$(href).modal('open');
	} else {
		$.get(href, function(data) {
			//ugly heck to prevent the content getting append when opening the same modal multiple time
			$("#change_user_avatar").html(''); 
			$("#change_user_avatar").attr("id","ugly_heck");
			$('<div class="modal hide fade in" id="change_user_avatar">' + data + '</div>').modal();
		}).success(function() { $('input:text:visible:first').focus(); });
	}
}