// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
 * @author Abhik Chakraborty
*/
$(document).ready(function() {
	// Validate
	// Add Profile form
	$('#Profile__eventAddNewProfileStep1').validate({
		rules: {
			profilename: {
				minlength: 2,
                required: true
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
           
        
	// Update Profile form
	$('#Profile__eventRenameProfile').validate({
		rules: {
			profilename: {
				minlength: 2,
				required: true
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
          
	// Add Roles form
	$('#Roles__eventAddNewRole').validate({
		rules: {
			rolename: {
			minlength: 2,
				required: true
			},
			'select_to[]': {
				required: true
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
           
	// Edit Roles Form
	$('#Roles__eventEditRole').validate({
		rules: {
			rolename: {
				minlength: 2,
                required: true
			},
			'select_to[]': {
				required: true
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
           
	// Add Group form
	$('#Group__eventAddNewGroup').validate({
		rules: {
			group_name: {
				minlength: 2,
                required: true
			},
			'select_to[]': {
				required: true
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
           
	// Edit Group form
	$('#Group__eventEditGroup').validate({
		rules: {
			group_name: {
				minlength: 2,
                required: true
			},
			'select_to[]': {
				required: true
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

/*
* Onhover on the roles hierarchy
* generate the add/edit link 
*/
$(".role_hierarchy").hover(
	function (){ $(this).children(".role_hierarchy_opt").show();},
	function (){ $(this).children(".role_hierarchy_opt").hide();}
);


/*
* Transfer Profile from one multi-select to another in role add/edit form
*/
$('#profile_add_select').click(function() {
	$('#select_from option:selected').each( function() {
		var transfer_val = $(this).val() ;
		$('#select_to').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
		// Done to make the item selected so that the validation knows an item is selected and validator is happy :)
		//$('#select_to').find('option:[value="'+transfer_val+'"]').attr('selected',true);
		$("#select_to option[value='"+transfer_val+"']").attr("selected", 1);
		$(this).remove();
	});
});

$('#profile_remove_select').click(function() {
	$('#select_to option:selected').each( function() {
		$('#select_from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
		$(this).remove();
		$('#select_to option').attr('selected',true);
	});
});

/*
* Transfer Users from one multi-select to another in role add/edit form
*/
$('#user_add_select').click(function() {
	$('#select_from option:selected').each( function() {
		var transfer_val = $(this).val() ;
		$('#select_to').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
		//$('#select_to').find('option:[value="'+transfer_val+'"]').attr('selected',true);
		$("#select_to option[value='"+transfer_val+"']").attr("selected", 1);
		$(this).remove();
	});
});

$('#user_remove_select').click(function(){
  $('#select_to option:selected').each( function(){
    $('#select_from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
    $(this).remove();
    // Done to make the item selected so that the validation knows an item is selected
    $('#select_to option').attr('selected',true);
  });
});


/*
* Function to set the standard permission for each module by the permission type
* @param id , id of the checkbox
* @param permission_type
*/
function  set_standard_permission(id,permission_type) {
	var s = id.split("_"); 
	var idmodule = s[1];
	//alert(idmodule);
	if ($('#'+id).is(':checked')) {
		// meaning add/edit is allowed then the user must view data else how the heck user will add/edit ah...
		if (permission_type == 1) { 
			var d_view = 'm_'+idmodule+'_2';
			if ($('#'+d_view).is(':checked') == false) {
				$('#'+d_view).attr('checked','checked');
			}
		}
		// meaning delete is allowed then the user must view data else you know what I mean
		if (permission_type == 3) {
			var d_view = 'm_'+idmodule+'_2';
			if ($('#'+d_view).is(':checked') == false) {
				$('#'+d_view).attr('checked','checked');
			}
		}
		// If all the standard permission is set then ofcource the module itself is permitted
		var e = 'm_'+idmodule+'_1'; // edit permission checkbox
		var v = 'm_'+idmodule+'_2'; // view permission checkbox
		var d = 'm_'+idmodule+'_3'; // delete permission checkbox
		if ($('#'+e).is(':checked') && $('#'+v).is(':checked') && $('#'+d).is(':checked')) {
			var mod_chk_box  = 'mod_'+idmodule;
			$('#'+mod_chk_box).attr('checked','checked');
		}
	} else {
		// meaning if no permission to view then ofcource no permission for add/edit and delete also
		if (permission_type == 2) { 
			var d_edit = 'm_'+idmodule+'_1';
			var d_delete = 'm_'+idmodule+'_3';
			$('#'+d_edit).removeAttr('checked');
			$('#'+d_delete).removeAttr('checked');
			// Remove the global permissions
			$('#global_view_all').removeAttr('checked');
			$('#global_addedit_all').removeAttr('checked');
		} else { 
			$('#global_addedit_all').removeAttr('checked');
		}
	}
}

/*
* Set the permission by each module
* @param idmodule
*/
function set_standard_permission_by_module(idmodule) {
    var id = 'mod_'+idmodule;
    if ( $('#'+id).is(':checked') == false ) {
        var d_edit = 'm_'+idmodule+'_1';
        var d_view = 'm_'+idmodule+'_2';
        var d_delete = 'm_'+idmodule+'_3';
        $('#'+d_edit).removeAttr('checked');
        $('#'+d_view).removeAttr('checked');
        $('#'+d_delete).removeAttr('checked');
        
        // Remove the global permissions
        $('#global_view_all').removeAttr('checked');
        $('#global_addedit_all').removeAttr('checked');
    }
}


/*
* Set the global permission
* @param id, id of the checkbox
*/
function set_global_permission(id) {
	if (id == 'global_view_all') {
		if ($('#'+id).is(':checked')) {
			$("#module_permission input").each( function() {
				$(this).attr('checked','checked');
			});
			$("#view_permission input").each( function() {
				$(this).attr('checked','checked');
			});
		}
    }
    
    if (id == 'global_addedit_all') {
		if ($('#'+id).is(':checked')) {
			$('#global_view_all').attr('checked','checked');
			
			$("#module_permission input").each( function() {
				$(this).attr('checked','checked');
			});
			
			$("#view_permission input").each( function() {
				$(this).attr('checked','checked');
			});
			
			$("#add_permission input").each( function() {
				$(this).attr('checked','checked');
			});
			
			$("#delete_permission input").each( function() {
				$(this).attr('checked','checked');
			});
		}
    }
}

/*
* updating the datashare permission on setting module
* @see modules/Settings/datashare_details.php
*/
$("#update_datashare").click( function() {
	$("#datashare_disp").fadeOut("slow", function() {
		$("#ds_display").fadeOut("slow");
		$("#datashare_edit").fadeIn("slow");
		$("#ds_edit_top").fadeIn("slow");
		$("#ds_edit_bottom").fadeIn("slow");
	});
});
  

/*
* confirm modal for deleting profile 
*/
function return_delete_profile_confirm(id,classname,module,referrar) {
	$("#delete_confirm").modal('show');
	$("#delete_confirm .btn-primary").click(function() {
		$("#delete_confirm").modal('hide');
		var href = '/popups/delete_profile_modal?sqrecord='+id+'&classname='+classname+'&m='+module+'&referrar='+referrar;
		
		if (href.indexOf('#') == 0) {
			$(href).modal('open');
		} else {
			$.get(href, function(data) {
				//ugly heck to prevent the content getting append when opening the same modal multiple time
				$("#return_delete_profile_confirm").html(''); 
				$("#return_delete_profile_confirm").hide();
				$("#return_delete_profile_confirm").attr("id","ugly_heck");
				$('<div class="modal hide fade in" id="return_delete_profile_confirm">' + data + '</div>').modal();
			}).success(function() { $('input:text:visible:first').focus(); });
		}
	});
}

/*
 * confirm modal for deleting group 
*/
/*function return_delete_group_confirm(id,classname,module,referrar){
    var href = '/popups/delete_group_modal?sqrecord='+id+'&classname='+classname+'&m='+module+'&referrar='+referrar;
    if (href.indexOf('#') == 0) {
            $(href).modal('open');
    } else {
            $.get(href, function(data) {
                    //ugly heck to prevent the content getting append when opening the same modal multiple time
                    $("#return_delete_group_confirm").html(''); 
                    $("#return_delete_group_confirm").attr("id","ugly_heck");
                    $('<div class="modal hide fade in"  id="return_delete_group_confirm">' + data + '</div>').modal();
            }).success(function() { $('input:text:visible:first').focus(); });
    }
  
}*/

/*
* function to generate the custom field view in setting module
*/

$("#cf_module_selector").change( function() {
	var mid = $("#cf_module_selector").val() ;
	if (mid == 3) {
		$("#map_custom_field").show() ;
	} else {
		$("#map_custom_field").hide() ;
	}
	$.ajax({
		type: "GET",
		url: "customfield_list",
		data : "cmid="+mid+"&ajaxreq="+true,
		success: function(result) { 
			$('#cf_entry').html(result) ;
		}
	});
});

/*
* custom field add modal
* @param module
* @param referrar
*/
function add_custom_field(module,referrar) {
	var idmodule = $("#cf_module_selector").val() ;
	var href = '/popups/add_custom_field_modal?idmodule='+idmodule+'&m='+module+'&referrar='+referrar;
	if (href.indexOf('#') == 0) { 
		$(href).modal('open');
	} else {
		$.get(href, function(data) {
			//ugly heck to prevent the content getting append when opening the same modal multiple time
			$("#add_custom_field").html(''); 
			$("#add_custom_field").attr("id","ugly_heck");
			$('<div class="modal hide fade in" id="add_custom_field">' + data + '</div>').modal();
		}).success(function() { $('input:text:visible:first').focus(); });
	}
}

/*
* custom field edit modal
* @param module
* @param idfields
* @param referrar
*/
function edit_custom_field(module,idfields,referrar) {
	var href = '/popups/edit_custom_field_modal?&m='+module+'&classname=CustomFields&sqrecord='+idfields+'&referrar='+referrar;
	if (href.indexOf('#') == 0) { 
		$(href).modal('open');
	} else {
		$.get(href, function(data) {
			//ugly heck to prevent the content getting append when opening the same modal multiple time
			$("#edit_custom_field").html(''); 
			$("#edit_custom_field").attr("id","ugly_heck");
			$('<div class="modal hide fade in" id="edit_custom_field">' + data + '</div>').modal();
		}).success(function() { $('input:text:visible:first').focus(); });
	}
}