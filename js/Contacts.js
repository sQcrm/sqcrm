// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
* @author Abhik Chakraborty
*/

$("#cnt_mailing_to_other").click( function() {  
	$("#cnt_other_street").html($("#cnt_mail_street").val());
	$("#cnt_other_pobox").attr('value',$("#cnt_mail_pobox").val());
	$("#cnt_other_city").attr('value',$("#cnt_mailing_city").val());
	$("#cnt_other_state").attr('value',$("#cnt_mailing_state").val());
	$("#cnt_other_postalcode").attr('value',$("#cnt_mailing_postalcode").val());
	$("#cnt_other_country").attr('value',$("#cnt_mailing_country").val());
	$("#cnt_other_to_mailing").removeAttr('checked');
});

$("#cnt_other_to_mailing").click( function() {
	$("#cnt_mail_street").html($("#cnt_other_street").val());
	$("#cnt_mail_pobox").attr('value',$("#cnt_other_pobox").val());
	$("#cnt_mailing_city").attr('value',$("#cnt_other_city").val());
	$("#cnt_mailing_state").attr('value',$("#cnt_other_state").val());
	$("#cnt_mailing_postalcode").attr('value',$("#cnt_other_postalcode").val());
	$("#cnt_mailing_country").attr('value',$("#cnt_other_country").val());
	$("#cnt_mailing_to_other").removeAttr('checked');
});