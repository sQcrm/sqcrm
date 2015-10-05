// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
* @author Abhik Chakraborty
*/
$(document).ready(function() {
	$("#terms_cond").expandingTextarea();
});

function send_invoice_with_email(idinvoice,idorganization) {
	var href = '/popups/send_invoice_with_email?m=Invoice&idinvoice='+idinvoice+'&idorganization='+idorganization;
	if (href.indexOf('#') == 0) { 
		$(href).modal('open');
	} else {
		$.get(href, function(data) {
			//ugly heck to prevent the content getting append when opening the same modal multiple time
			$("#send_invoice_with_email_modal").html(''); 
			$("#send_invoice_with_email_modal").attr("id","ugly_heck");
			$('<div class="modal hide fade in" id="#send_invoice_with_email_modal">' + data + '</div>').modal();
		}).success(function() { $('input:text:visible:first').focus(); });
	}
}