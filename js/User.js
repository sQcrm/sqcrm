// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
* @author Abhik Chakraborty
*/
$(document).ready(function() {
	/**
	* deleting an user by selecting and clicking on the delete button from the list page
	* @see popups/delete_user_modal.php
	*/
	$('#delete_data_user').click(function() {
    	var sData = oTable.$('input:checkbox').serialize();
		if (sData == '') {
			var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var err_msg = err_element+'<strong>'+SELECT_ONE_RECORD_BEFORE_DELETE+'</strong></div>';
			$("#message").html(err_msg);
			$("#message").show();
			return false ;
		} else {
			$("#delete_confirm").modal('show');
			$("#delete_confirm .btn-primary").off('click');
			$("#delete_confirm .btn-primary").click(function(){
				$("#delete_confirm").modal('hide');
				var href = '/popups/delete_user_modal?classname=User&m=Settings&referrar=users&'+sData;
				if (href.indexOf('#') == 0) {
					$(href).modal('open');
				} else {
					$.get(href, function(data) {
						//ugly heck to prevent the content getting append when opening the same modal multiple time
						$("#delete_user_transfer_data").html(''); 
						$("#delete_user_transfer_data").hide();
						$("#delete_user_transfer_data").attr("id","ugly_heck");
						$('<div class="modal fade" tabindex="-1" role="dialog" id="delete_user_transfer_data">' + data + '</div>').modal();
					}).success(function() { $('input:text:visible:first').focus(); });
				}
			});
		}
	});
  
	/**
	* deleting an user by clicking on the delete link from the list page
	* @see popups/delete_user_modal.php
	*/
	$(".datadisplay").on('click','.delete_entity_user', function(e) {
		var sqrecord = $(this).closest('a').attr('id') ; 
		if (sqrecord == '') {
			var err_element = '<div class="alert alert-danger sqcrm-top-message" id="sqcrm_auto_close_messages"><a href="#" class="close" data-dismiss="alert">&times;</a>' ;
			var err_msg = err_element+'<strong>'+SELECT_ONE_RECORD_BEFORE_DELETE+'</strong></div>';
			$("#message").html(err_msg);
			$("#message").show();
			return false ;
		} else {
			$("#delete_confirm").modal('show');
			$("#delete_confirm .btn-primary").off('click');
			$("#delete_confirm .btn-primary").click(function() {
				$("#delete_confirm").modal('hide');
				var href = '/popups/delete_user_modal?classname=User&m=Settings&referrar=users&sqrecord='+sqrecord;
				if (href.indexOf('#') == 0) {
					$(href).modal('open');
				} else {
					$.get(href, function(data) {
						//ugly heck to prevent the content getting append when opening the same modal multiple time
						$("#delete_user_transfer_data").html(''); 
						$("#delete_user_transfer_data").hide();
						$("#delete_user_transfer_data").attr("id","ugly_heck");
						$('<div class="modal fade" tabindex="-1" role="dialog" id="delete_user_transfer_data">' + data + '</div>').modal();
					}).success(function() { $('input:text:visible:first').focus(); });
				}
			});
		}
	});
});