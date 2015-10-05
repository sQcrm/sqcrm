// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
 * @author Abhik Chakraborty
*/

$("#module_fields_add_select").click(function(e) {
	e.preventDefault(); 
	$("#select_module_fields option:selected").each( function() {
		var transfer_val = $(this).val() ;
		var append_data = true ;
		$("#cv_fields option").each(function() {
			if(transfer_val == this.value){
				append_data = false ;
				return false;
			}
		});
		if(append_data == true) {
			$("#cv_fields").append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
			// Done to make the item selected so that the validation knows an item is selected and validator is happy :)
			$("#cv_fields option[value='"+transfer_val+"']").attr("selected", 1);
		}
	});
});
	
$("#cv_fields_delete").click(function(e) {
	e.preventDefault(); 
	$("#cv_fields option:selected").each( function() {
		$(this).remove();
	});
	$('#cv_fields option').prop('selected', true);
});
	
$("#cv_fields_up").click(function(e) {
	e.preventDefault(); 
	$("#cv_fields option:selected").each( function() {
		var new_position = $("#cv_fields option").index(this) - 1;
		if (new_position > -1) {
			$("#cv_fields option").eq(new_position).before("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
			$(this).remove();
		}
	});
});
	
$("#cv_fields_down").click(function(e) {
	e.preventDefault(); 
	var count_fields = $("#cv_fields option").size();
	$("#cv_fields option:selected").each( function() {
		var new_position = $("#cv_fields option").index(this) + 1;
		if (new_position < count_fields) {
			$("#cv_fields option").eq(new_position).after("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
			$(this).remove();
		}
	});
});

$("#cv_date_field_type").change(function() {
	var date_filter_type = $(this).val();
	if (date_filter_type != 1) {
		$("#cv_date_filter").hide();
	} else {
		$("#cv_date_filter").show();
	}
});

$('#CustomView__addEditRecord').submit( function() { 
	if ($("#cvname").val() === '') {
		display_js_error(CV_ADD_NAME,'js_errors');
		return false;
	}	
	
	var selected_fields = [] ;
	$("#cv_fields option:selected").each(function() {
		var value = $(this).val();
		if (value) {
			selected_fields.push(value);
		}
	});
	
	if (selected_fields.length === 0) {
		display_js_error(CV_SELECT_FIELDS,'js_errors');
		return false;
	}
	
	if ($("#cv_adv_fields_2").val() != '0' && $("#cv_adv_fields_1").val() == '0') {
		display_js_error(CV_SELECT_PREVIOUS_ORDER_OPTION,'js_errors');
		$("#cv_adv_fields_2").val('0');
		return false;
	}
		
	if ($("#cv_adv_fields_3").val() != '0' && $("#cv_adv_fields_2").val() == '0') {
		display_js_error(CV_SELECT_PREVIOUS_ORDER_OPTION,'js_errors');
		$("#cv_adv_fields_3").val('0');
		return false;
	}
		
	if ($("#cv_adv_fields_4").val() != '0' && $("#cv_adv_fields_3").val() == '0') {
		display_js_error(CV_SELECT_PREVIOUS_ORDER_OPTION,'js_errors');
		$("#cv_adv_fields_4").val('0');
		return false;
	}
		
	if ($("#cv_adv_fields_5").val() != '0' && $("#cv_adv_fields_4").val() == '0') {
		display_js_error(CV_SELECT_PREVIOUS_ORDER_OPTION,'js_errors');
		$("#cv_adv_fields_5").val('0');
		return false;
	}
		
	if ($("#cv_adv_fields_1").val() != '0') {
		if ($("#cv_adv_fields_type_1").val() == 0) {
			display_js_error(CV_SELECT_FILTER_TYPE,'js_errors');
			$("#cv_adv_fields_type_1").focus();
			return false ;
		}
		
		if ($("#cv_adv_fields_val_1").val() == '') {
			display_js_error(CV_SELECT_FILTER_VALUE,'js_errors');
			$("#cv_adv_fields_val_").focus();
			return false ;
		}
	}
		
	if ($("#cv_adv_fields_2").val() != '0') {
		if ($("#cv_adv_fields_type_2").val() == 0) {
			display_js_error(CV_SELECT_FILTER_TYPE,'js_errors');
			$("#cv_adv_fields_type_2").focus();
			return false ;
		}
		
		if ($("#cv_adv_fields_val_2").val() == '') {
			display_js_error(CV_SELECT_FILTER_VALUE,'js_errors');
			$("#cv_adv_fields_val_2").focus();
			return false ;
		}
	}
		
	if ($("#cv_adv_fields_3").val() != '0') {
		if ($("#cv_adv_fields_type_3").val() == 0) {
			display_js_error(CV_SELECT_FILTER_TYPE,'js_errors');
			$("#cv_adv_fields_type_3").focus();
			return false ;
		}
		
		if ($("#cv_adv_fields_val_3").val() == '') {
			display_js_error(CV_SELECT_FILTER_VALUE,'js_errors');
			$("#cv_adv_fields_val_3").focus();
			return false ;
		}
	}
		
	if ($("#cv_adv_fields_4").val() != '0') {
		if ($("#cv_adv_fields_type_4").val() == 0) {
			display_js_error(CV_SELECT_FILTER_TYPE,'js_errors');
			$("#cv_adv_fields_type_4").focus();
			return false ;
		}
		
		if ($("#cv_adv_fields_val_4").val() == '') {
			display_js_error(CV_SELECT_FILTER_VALUE,'js_errors');
			$("#cv_adv_fields_val_4").focus();
			return false ;
		}
	}
		
	if ($("#cv_adv_fields_5").val() != '0') {
		if ($("#cv_adv_fields_type_5").val() == 0) {
			display_js_error(CV_SELECT_FILTER_TYPE,'js_errors');
			$("#cv_adv_fields_type_5").focus();
			return false ;
		}
		
		if ($("#cv_adv_fields_val_5").val() == '') {
			display_js_error(CV_SELECT_FILTER_VALUE,'js_errors');
			$("#cv_adv_fields_val_5").focus();
			return false ;
		}
	}
	
	//finally set all the cv_fields as selected
	$('#cv_fields option').prop('selected', true);
});