// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
 * @author Abhik Chakraborty
*/

$("#org_bill_to_ship").click( function() {
	$("#org_ship_address").html($("#org_bill_address").val());
	$("#org_ship_pobox").attr('value',$("#org_bill_pobox").val());
	$("#org_ship_city").attr('value',$("#org_bill_city").val());
	$("#org_ship_state").attr('value',$("#org_bill_state").val());
	$("#org_ship_postalcode").attr('value',$("#org_bill_postalcode").val());
	$("#org_ship_country").attr('value',$("#org_bill_country").val());
	$("#org_ship_to_bill").removeAttr('checked');
});

$("#org_ship_to_bill").click( function() {
	$("#org_bill_address").html($("#org_ship_address").val());
	$("#org_bill_pobox").attr('value',$("#org_ship_pobox").val());
	$("#org_bill_city").attr('value',$("#org_ship_city").val());
	$("#org_bill_state").attr('value',$("#org_ship_state").val());
	$("#org_bill_postalcode").attr('value',$("#org_ship_postalcode").val());
	$("#org_bill_country").attr('value',$("#org_ship_country").val());
	$("#org_bill_to_ship").removeAttr('checked');
});