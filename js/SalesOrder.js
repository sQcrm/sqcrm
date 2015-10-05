// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
 * @author Abhik Chakraborty
*/
$(document).ready(function() {
	$("#terms_cond").expandingTextarea();
});

function send_salesorder_with_email(idsales_order,idorganization) {
	var href = '/popups/send_salesorder_with_email?m=SalesOrder&idsales_order='+idsales_order+'&idorganization='+idorganization;
	if (href.indexOf('#') == 0) { 
		$(href).modal('open');
	} else {
		$.get(href, function(data) {
			//ugly heck to prevent the content getting append when opening the same modal multiple time
			$("#send_salesorder_with_email_modal").html(''); 
			$("#send_salesorder_with_email_modal").attr("id","ugly_heck");
			$('<div class="modal hide fade in" id="#send_quote_with_email_modal">' + data + '</div>').modal();
		}).success(function() { $('input:text:visible:first').focus(); });
	}
}