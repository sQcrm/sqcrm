// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
 * @author Abhik Chakraborty
*/
$(document).ready(function() {
	$("#terms_cond").expandingTextarea();
});

function send_po_with_email(idpurchase_order,idcontact) {
	var href = '/popups/send_po_with_email?m=PurchaseOrder&idpurchase_order='+idpurchase_order+'&idcontact='+idcontact;
	if (href.indexOf('#') == 0) { 
		$(href).modal('open');
	} else {
		$.get(href, function(data) {
			//ugly heck to prevent the content getting append when opening the same modal multiple time
			$("#send_po_with_emaill").html(''); 
			$("#send_po_with_emaill").attr("id","ugly_heck");
			$('<div class="modal fade" tabindex="-1" role="dialog" id="send_po_with_emaill">' + data + '</div>').modal();
		}).success(function() { $('input:text:visible:first').focus(); });
	}
}