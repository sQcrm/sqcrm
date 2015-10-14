// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
* @author Abhik Chakraborty
* Common JS methods used across the CRM
*/

/*
* Function to select the role name and idrole from a popup modal role selector
* This function is specific to the field type FieldType103
* @param idrole
* @param rolename
* @param fieldname
*/  
function return_roles_selected_item(idrole,rolename,fieldname) {
	$("#role_name").attr('value',rolename);
	$("#"+fieldname).attr('value',idrole);
}

/*
* function to remove the role
* @param fieldname
*/
function remove_role(fieldname) {
	$("#role_name").attr('value','');
	$("#"+fieldname).attr('value','');
}

// function to close the autoclose messages
$(document).ready( function() {
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
* function to load the detail view tabs data
* @param module
* @param sqcrm_id
* @param section
*/
function load_deail_view_data(module,sqcrm_id,section) {
	var action_name = '';
	if (section == 'detail') {
		action_name = 'detail';
		if ($("#topbar_detail").hasClass('active')) {
			return false ;
		}
		$("#topbar_history").removeClass('active');
		$("#topbar_related").removeClass('active');
		$("#topbar_detail").addClass('active');
		if (module == 'User') {
			$("#topbar_loginaudit").removeClass('active');
			$("#topbar_homepage_components").removeClass('active');
		}
	} else if (section == 'history') {
		action_name = 'history';
		if ($("#topbar_history").hasClass('active')) {
			return false ;
		}
		$("#topbar_history").addClass('active');
		$("#topbar_related").removeClass('active');
		$("#topbar_detail").removeClass('active');
    
		if (module == 'User') {
			$("#topbar_loginaudit").removeClass('active');
			$("#topbar_homepage_components").removeClass('active');
		}
	} else if (section == 'related') {
		action_name = 'related';
		if ($("#topbar_related").hasClass('active')) {
			return false;
		}
		$("#topbar_related").addClass('active');
		$("#topbar_detail").removeClass('active');
		$("#topbar_history").removeClass('active');
		
		if (module == 'User') {
			$("#topbar_loginaudit").removeClass('active');
			$("#topbar_homepage_components").removeClass('active');
		}
	} else if (section == 'loginaudit') {
		action_name = 'loginaudit';
		if ($("#topbar_loginaudit").hasClass('active')) {
			return false;
		}
		$("#topbar_loginaudit").addClass('active');
		$("#topbar_detail").removeClass('active');
		$("#topbar_history").removeClass('active');
		$("#topbar_homepage_components").removeClass('active');
	} else if (section == 'homepage_components') {
		action_name = 'homepage_components';
		if ($("#topbar_homepage_components").hasClass('active')) {
			return false;
		}
		$("#topbar_homepage_components").addClass('active');
		$("#topbar_loginaudit").removeClass('active');
		$("#topbar_detail").removeClass('active');
		$("#topbar_history").removeClass('active');
	}
	$.ajax({
		type: "GET",
		url: action_name,
		data : "sqrecord="+sqcrm_id+"&ajaxreq="+true,
		success: function(result) { 
			$('#detail_view_section').html(result) ;
		},
		beforeSend: function() {
			$('#detail_view_section').html('<img class="ajax_loader" src="/themes/images/ajax-loader1.gif" border="0" />');
		}
	});
}

function compare_dates(date1,date2,check,date_format,time1,time2) {
	var first_val = '';
	var second_val = '';
	if (date_format == 'mm-dd-yyyy' || date_format == 'dd-mm-yyyy' || date_format == 'yyyy-mm-dd') {
		first_val = date1.split('-');
		second_val = date2.split('-');
	} else if (date_format == 'mm/dd/yyyy' || date_format == 'dd/mm/yyyy' || date_format == 'yyyy/mm/dd') {
		first_val = date1.split('/');
		second_val = date2.split('/');
	}
  
	if (date_format == 'mm-dd-yyyy' || date_format == 'mm/dd/yyyy') {
		var _first_date = first_val[2]+'/'+first_val[0]+'/'+first_val[1] ;
		var _second_date = second_val[2]+'/'+second_val[0]+'/'+second_val[1] ;
	} else if (date_format == 'dd-mm-yyyy' || date_format == 'dd/mm/yyyy') {
		var _first_date = first_val[2]+'/'+first_val[1]+'/'+first_val[0] ;
		var _second_date = second_val[2]+'/'+second_val[1]+'/'+second_val[0] ;
	} else if (date_format == 'yyyy-mm-dd' || date_format == 'yyyy/mm/dd') {
		var _first_date = first_val[0]+'/'+first_val[1]+'/'+first_val[2] ;
		var _second_date = second_val[0]+'/'+second_val[1]+'/'+second_val[2] ;
	}
	
	if (time1 != '' && time2 != '') {
		_first_date =_first_date+' '+time1;
		_second_date = _second_date+' '+time2;
	}
  
	var first_date = new Date(Date.parse(_first_date));
	var second_date = new Date(Date.parse(_second_date));
  
	if (check == 'g') {
		if (first_date > second_date) {
			return true ;
		} else {
			return false ;
		}
	} else if (check == 'l') {
		if (first_date < second_date) {
			return true ;
		} else {
			return false ;
		}
	} else if (check == 'e') {
		if (first_date == second_date) {
			return true ;
		} else {
			return false ;
		}
	} else if (check == 'ge') {
		if (first_date > second_date) {
			return true;
		} else if (first_date < second_date) {
			return false;
		} else {
			return true ;
		}
	} else if (check == 'le') {
		if (first_date > second_date) {
			return false;
		} else if (first_date < second_date) {
			return true;
		} else {
			return true ;
		} 
	}
}

function custom_validator(mid) {
	if (mid == 2) {
		var start_date = $("#start_date").val();
		var end_date = $("#end_date").val();
		var date_format = $("#js_user_date_format").val();
		var start_time = $("#start_time").val();
		var end_time = $("#end_time").val();
		if (compare_dates(end_date,start_date,'g',date_format,end_time,start_time)) {
			return true ;
		} else {
			display_js_error(EVENT_END_DATE_GREATER_THAN_START_DATE,'js_errors');
			return false ;
		}
	} else {
		return true;
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