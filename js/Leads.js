// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
* @author Abhik Chakraborty
*/

/*
* convert lead js function
* @param idleads
*/
function convert_lead(idleads) {
	var href = '/popups/convert_lead_modal?idmodule=3&m=Leads&referrar=detail&sqrecord='+idleads;
    if (href.indexOf('#') == 0) { 
		$(href).modal('open');
    } else {
		$.get(href, function(data) {
			//ugly heck to prevent the content getting append when opening the same modal multiple time
			$("#convert_lead").html(''); 
			$("#convert_lead").attr("id","ugly_heck");
			$('<div class="modal fade" tabindex="-1" role="dialog" id="convert_lead">' + data + '</div>').modal();
        }).success(function() { $('input:text:visible:first').focus(); });
    }
}