// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/*
 * @author Abhik Chakraborty
*/

$(document).ready(function() {
	var thousand_seperator = '<?php echo $currency_data["thousand_seperator"] ; ?>';
	var decimal_symbol = '<?php echo $currency_data["decimal_symbol"] ; ?>';
	var decimal_point = '<?php echo $currency_data["decimal_point"] ; ?>';
	
	/*$('#table_line_items > tbody  > tr').each(function() {
		var id = this.id ;
		$("#line_item_price_"+id).maskMoney({ 
			thousands:thousand_seperator, 
			decimal:decimal_symbol,
			precision:decimal_point
		});
	});*/
	
	//-- select line item function , opens the data in modal list view 
	$(document.body).on('click', '.line_item_selector' ,function(e) {
		e.preventDefault();
		var current_id = this.id;
		var val = $("#line_item_selector_opt_"+current_id).val();
		if (val == 'product') {
			var href = '/popups/listdata_popup_modal?m=Products&action=list&fieldname=item_name&line_level='+current_id+'&line_item=yes';
			if (href.indexOf('#') == 0) {
				$(href).modal('open');
			} else {
				$.get(href, function(data) {
					//ugly heck to prevent the content getting append when opening the same modal multiple time
					$("#listdata_popup_selector").html(''); 
					$("#listdata_popup_selector").attr("id","ugly_heck");
					$('<div class="modal hide in" id="listdata_popup_selector" style="width:700px;">' + data + '</div>').modal();
				}).success(function() { $('input:text:visible:first').focus(); window.scrollTo(0,document.body.scrollHeight);});
			}
		}
	});
	//-- select line item ends --//
	
	//-- add a new line item by appending to the line item table
	$(".add_new_line_item").on('click', function(e) {
		e.preventDefault();
		var row_count = $("#table_line_items tr").length ;
		if(row_count == 0 ) row_count = 1 ;
		var line_item = '';
		line_item +='<tr id="'+row_count+'">';
		line_item +=	'<td>';
		line_item += 		'<a href="#" class="btn btn-primary btn-mini bs-prompt delete_line_item" id="'+row_count+'"><i class="icon-white icon-trash"></i></a>';
		line_item +=	'</td>';
		line_item +=	'<td>';
		line_item += 		'<select name="line_item_selector_opt[]" id="line_item_selector_opt_'+row_count+'">';
		line_item +=			'<option value="product">Products</option>';
		line_item +=		'</select>';
		line_item +=		'<br /><br />';
		line_item +=		'<input name="line_item_name[]" id="line_item_name_'+row_count+'" autocomplete="off" type="text" class="input-xlarge-100">';
		line_item +=		'<input type="hidden" name="line_item_value[]" id="line_item_value_'+row_count+'">';
		line_item +=		'<input type="hidden" name="line_item_type[]" id="line_item_type_'+row_count+'">';
		line_item +=		'&nbsp;&nbsp;';
		line_item +=		'<a href="#"  id="'+row_count+'"  class="line_item_selector btn btn-primary btn-mini"><i class="icon-white icon-plus-sign"></i></a>';
		line_item +=		'<br /><br />';
		line_item +=		'<textarea name="line_item_description[]" id="line_item_description_'+row_count+'" class="input-xlarge-100"></textarea>';
		line_item +=	'</td>';
		line_item +=	'<td><input class="input-mini line_item_quantity" name="line_item_quantity[]" id="'+row_count+'" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number"></td>';
		line_item +=	'<td>';
		line_item += 		'<div style="height:40px;">';
		line_item +=			'<input class="input-small" name="line_item_price[]" id="line_item_price_'+row_count+'" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number">';
		line_item +=		'</div>';
		line_item +=		'<div style="height:40px;">';
		line_item +=			'<a href="#" id="'+row_count+'" class="line_item_discount">Discount</a>';
		line_item +=			'<input type="hidden" name="line_discount_type[]" id="line_discount_type_'+row_count+'">';
		line_item +=			'<input type="hidden" name="line_discount_value[]" id="line_discount_value_'+row_count+'">';
		line_item +=		'</div>';
		line_item +=		'<div style="height:40px;">';
		line_item +=			'<strong>Total after discount</strong>';
		line_item +=		'</div>';
		line_item +=		'<div style="height:40px;" id="line_item_tax_section">';
		line_item +=			'<a href="#" id="'+row_count+'" class="line_item_tax">Tax</a>';
		line_item +=			'<input type="hidden" name="line_has_tax_'+row_count+'" id="line_has_tax_'+row_count+'" value="0">';
		line_item +=			'<div id="line_tax_'+row_count+'" class="modal hide">';
		line_item +=			'<input type="hidden" name="line_tax_selected[]" class=".line_tax_selected" id="line_tax_selected_'+row_count+'">'
		line_item +=			'<div id="line_tax_'+row_count+'" class="modal hide">';
		line_item +=			'</div>';
		line_item +=		'</div>';
		line_item +=	'</td>';
		line_item +=  '<td>';
		line_item +=		'<div style="height:40px;">';
		line_item +=			'<input type="hidden" id="line_item_total_'+row_count+'" name = line_item_total[]>';
		line_item +=			'<span class="total_'+row_count+'" id="total_'+row_count+'">0.00</span>';
		line_item +=		'</div>';
		line_item +=		'<div style="height:40px;">';
		line_item +=			'<input type="hidden" id="line_discounted_amount_value_'+row_count+'" name = line_discounted_amount_value[]>';
		line_item +=			'<span class="line_discounted_amount_'+row_count+'" id="line_discounted_amount_'+row_count+'">0.00</span>';
		line_item +=		'</div>';
		line_item +=		'<div style="height:40px;">';
		line_item +=			'<input type="hidden" id="line_total_after_discount_given_'+row_count+'" name="line_total_after_discount_given[]">';
		line_item +=			'<span class="line_total_after_discount_'+row_count+'"  id="line_total_after_discount_'+row_count+'">0.00</span>';
		line_item +=		'</div>';
		line_item +=		'<div style="height:40px;">';
		line_item +=			'<input type="hidden" id="line_item_tax_values_'+row_count+'" name="line_item_tax_values[]">';
		line_item +=			'<input type="hidden" id="line_item_tax_total_'+row_count+'" name="line_item_tax_total[]">';
		line_item +=			'<span class="line_tax_on_total_'+row_count+'" id="line_tax_on_total_'+row_count+'">0.00</span>';
		line_item +=		'</div>';
		line_item +=	'</td>';
		line_item +=	'<td>';
		line_item += 		'<input type="hidden" id="line_net_price_'+row_count+'" name="line_net_price[]">';
		line_item +=		'<span id="line_net_price_section_'+row_count+'"></span>';
		line_item +=	'</td>';
		line_item +='</tr>';
		$('#table_line_items').append(line_item);
	});
	//-- add a new line item ends --//
	
	//-- delete a line item
	$(document.body).on('click', '.delete_line_item' ,function(e) {
		e.preventDefault();
		$(this).closest("tr").remove();
		reset_grand_values();
	});
	//-- delete line item ends --//
	
	//-- when the qunatity is changed do the calculation
	$(document.body).on('change keyup mouseup blur','.line_item_quantity',function() {
		var current_id = this.id;
		var qty = parseInt($(this).val(),10);
		var line_item_price = $("#line_item_price_"+current_id).val();
		if (qty > 0) {
			var total_price = parseFloat(line_item_price*qty).toFixed(2);
			$("#line_item_total_"+current_id).attr('value',total_price);
			$("#total_"+current_id).html(total_price);
			$("#line_total_after_discount_given_"+current_id).html(total_price);
			$("#line_net_price_"+current_id).attr('value',total_price);
			$("#line_net_price_section_"+current_id).html(total_price);
		} else {
			var total_price = line_item_price ;
			$("#line_item_total_"+current_id).attr('value',total_price);
			$("#total_"+current_id).html(total_price);
			$("#line_total_after_discount_given_"+current_id).html(total_price);
			$("#line_net_price_"+current_id).attr('value',total_price);
			$("#line_net_price_section_"+current_id).html(total_price);
		}
		reset_line_values(current_id);
	});
	//-- quantity change calculation ends --//
	
	
	//-- function doing all the calculation to get the net line price
	function reset_line_values(current_id) {
		// total price quantity*unit price
		var line_total_price =  parseFloat($("#line_item_total_"+current_id).val()).toFixed(2);
		// check if there is any discount 
		var discount_type = $("#line_discount_type_"+current_id).val();
		var discount_val = parseFloat($("#line_discount_value_"+current_id).val()).toFixed(2);
		var discounted_amount = 0 ;
		if (discount_val > 0) {
			if (discount_type == 'percentage') {
				discounted_amount = parseFloat(line_total_price*discount_val/100).toFixed(2) ; 
				var total_after_discount = parseFloat(line_total_price - discounted_amount).toFixed(2) ;
			} else if (discount_type == 'direct') {
				discounted_amount = discount_val ;
				var total_after_discount = parseFloat(line_total_price - discounted_amount).toFixed(2) ;
			} else {
				var total_after_discount = line_total_price ;
			}
			$("#line_total_after_discount_"+current_id).html(parseFloat(total_after_discount).toFixed(2));
			$("#line_total_after_discount_given_"+current_id).attr('value',parseFloat(total_after_discount).toFixed(2));
			$("#line_discounted_amount_value_"+current_id).attr('value',parseFloat(discounted_amount).toFixed(2));
			$("#line_discounted_amount_"+current_id).html(parseFloat(discounted_amount).toFixed(2));
		} else {
			var total_after_discount = line_total_price ;
			$("#line_total_after_discount_"+current_id).html(parseFloat(line_total_price).toFixed(2));
			$("#line_total_after_discount_given_"+current_id).attr('value',parseFloat(line_total_price).toFixed(2));
			$("#line_discounted_amount_value_"+current_id).attr('value',0);
			$("#line_discounted_amount_"+current_id).html(parseFloat(discounted_amount).toFixed(2));
		}
		//check if tax is given
		var tax_values = $("#line_item_tax_values_"+current_id).val();
		
		var tax_amount = 0 ;
		if (tax_values.trim() != '') {
			tax_values = tax_values.substring(0, tax_values.length - 1);
			tax_values.split(',').forEach(function(val) {
				var tax = val.split('::');
				tax_amount += parseFloat(total_after_discount*tax[1]/100) ;
			});
		}
		$("#line_item_tax_total_"+current_id).attr('value',parseFloat(tax_amount).toFixed(2));
		$("#line_tax_on_total_"+current_id).html(parseFloat(tax_amount).toFixed(2));
	
		var net_price = parseFloat(total_after_discount) + parseFloat(tax_amount);
		$("#line_net_price_"+current_id).attr('value',parseFloat(net_price).toFixed(2));
		$("#line_net_price_section_"+current_id).html(parseFloat(net_price).toFixed(2));
		
		reset_grand_values();
	}
	//-- function ends here --//
	
	function reset_grand_values() {
		//grand total
		var net_sum_total = 0 ;
		var grand_total = 0 ;
		$('#table_line_items > tbody  > tr').each(function() {
			var id = this.id ;
			net_sum_total += parseFloat($("#line_net_price_"+id).val());
		});
		$("#net_total_lines").attr('value',parseFloat(net_sum_total).toFixed(2));
		$(".net_total_lines").html(parseFloat(net_sum_total).toFixed(2));
		
		grand_total = net_sum_total ;
		var grand_discount_type = $("#final_discount_type").val();
		var grand_discount_val = $("#final_discount_val").val();
		var discounted_amount = 0 ;
		
		if (grand_discount_val > 0) {
			if (grand_discount_type == 'percentage') {
				discounted_amount = parseFloat(net_sum_total*grand_discount_val/100).toFixed(2) ; 
				grand_total = parseFloat(net_sum_total - discounted_amount).toFixed(2) ;
			} else if (grand_discount_type == 'direct') {
				discounted_amount = grand_discount_val ;
				grand_total = parseFloat(net_sum_total - discounted_amount).toFixed(2) ;
			} else {
				grand_total = parseFloat(net_sum_total).toFixed(2) ;
			}
			$(".final_discount_dis").html(parseFloat(discounted_amount).toFixed(2));
			$("#final_discounted_total").attr('value',parseFloat(discounted_amount).toFixed(2));
		} else {
			grand_total = parseFloat(net_sum_total).toFixed(2) ;
			$(".final_discount_dis").html('0.00');
			$("#final_discounted_total").attr('value','0.00');
		}
		
		var tax_values = $("#final_tax_val").val();
		//var tax_amount_g = 0 ;
		
		var tax_amount = 0 ;
		if (tax_values.trim() != '') {
			tax_values = tax_values.substring(0, tax_values.length - 1);
			tax_values.split(',').forEach(function(val) {
				var tax = val.split('::');
				tax_amount += parseFloat(grand_total*tax[1]/100) ;
			});
		}
		grand_total = parseFloat(tax_amount)+parseFloat(grand_total);
		grand_total = parseFloat(grand_total).toFixed(2) ;
		$("#final_tax_amount").attr('value',parseFloat(tax_amount).toFixed(2));
		$(".final_tax_dis").html(parseFloat(tax_amount).toFixed(2));
		
		var sh_charge = $("#final_ship_hand_charge").val();
		if (sh_charge > 0) {
			grand_total = parseFloat(sh_charge)+parseFloat(grand_total);
		}
		grand_total = parseFloat(grand_total).toFixed(2) ;
		
		var shipping_handling_tax = 0 ;
		var sh_tax_val = $("#final_ship_hand_tax_val").val();
		if (sh_tax_val.trim() != '') {
			sh_tax_val = sh_tax_val.substring(0, sh_tax_val.length - 1);
			sh_tax_val.split(',').forEach(function(val) {
				var tax = val.split('::');
				shipping_handling_tax += parseFloat(grand_total*tax[1]/100) ;
			});
		}
		grand_total = parseFloat(shipping_handling_tax)+parseFloat(grand_total);
		grand_total = parseFloat(grand_total).toFixed(2) ;
		$("#final_ship_hand_tax_amount").attr('value',parseFloat(shipping_handling_tax).toFixed(2));
		$(".final_ship_hand_tax_dis").html(parseFloat(shipping_handling_tax).toFixed(2));
		
		var final_adjustment = $("#final_adjustment_val").val();
		if (final_adjustment > 0) {
			if ($("#final_adjustment").val() == 'add') {
				grand_total = parseFloat(final_adjustment)+parseFloat(grand_total);
				//grand_total += parseFloat(final_adjustment).toFixed(2);
			} else if ($("#final_adjustment").val() == 'deduct') {
				grand_total = parseFloat(grand_total)-parseFloat(final_adjustment);
				//grand_total -= parseFloat(final_adjustment).toFixed(2);
			} else {
				$("#final_adjustment_val").attr('value',0);
			}
		}
		grand_total = parseFloat(grand_total).toFixed(2) ;
		$("#grand_total").attr('value',parseFloat(grand_total).toFixed(2));
		$(".grand_total_val").html(parseFloat(grand_total).toFixed(2));
	}
	
	//-- add line discount, sets the modal element ids with the current_id
	$(document.body).on('click', '.line_item_discount' ,function(e) {
		e.preventDefault();
		var current_id = this.id;
		//-- do some validation
		if ($("#line_item_name_"+current_id).val() == '') {
			display_js_error(SELECT_LINE_ITEM,'js_errors');
			return false;
		}
		
		if ($(this).closest('tr').find('.line_item_quantity').val() == '') {
			display_js_error(ADD_LINE_ITEM_QTY,'js_errors');
			return false;
		}
		
		if ($("#line_item_price_"+current_id).val() == '') {
			display_js_error(ADD_LINE_ITEM_PRICE,'js_errors');
			return false;
		}
		//-- now lets make the modal element ids dynamic
		$(".item_no_dis").attr("id",current_id);
		$(".item_perc_dis").attr("id",current_id);
		$(".item_direct_dis").attr("id",current_id);
		$(".set_line_discount").attr("id",current_id);
		$(".perc_discount_val").attr("id","line_perc_discount_val_"+current_id);
		$(".dir_discount_val").attr("id","line_dir_discount_val_"+current_id);
		
		$(".perc_discount_val_span").attr("id","perc_discount_val_span_"+current_id);
		$(".dir_discount_val_span").attr("id","dir_discount_val_span_"+current_id);
		
		$(".item_no_dis").attr("name","line_discount_"+current_id);
		$(".item_perc_dis").attr("name","line_discount_"+current_id);
		$(".item_direct_dis").attr("name","line_discount_"+current_id);
		$(".perc_discount_val").attr("name","line_perc_discount_val_"+current_id);
		$(".dir_discount_val").attr("name","line_dir_discount_val_"+current_id);
		var price = $("#line_item_price_"+current_id).val() ;
		$("#on_price").html(price);
		$("#item_discount").modal('show');
	});
	//-- add line discount ends --//
	
	//-- add line discount, edit form 
	$(document.body).on('click', '.line_item_discount_edit' ,function(e) {
		e.preventDefault();
		var current_id = this.id;
		//-- do some validation
		if ($("#line_item_name_"+current_id).val() == '') {
			display_js_error(SELECT_LINE_ITEM,'js_errors');
			return false;
		}
		
		if ($(this).closest('tr').find('.line_item_quantity').val() == '') {
			display_js_error(ADD_LINE_ITEM_QTY,'js_errors');
			return false;
		}
		
		if ($("#line_item_price_"+current_id).val() == '') {
			display_js_error(ADD_LINE_ITEM_PRICE,'js_errors');
			return false;
		}
		$("#item_discount_"+current_id).modal('show');
	});
	//-- add line discount, edit form ends --//
	
	$(document.body).on('click', '.final_discount' ,function(e) {
		e.preventDefault();
		$("#grand_discount").modal('show');
	});
	
	$(document.body).on('click', '.final_tax' ,function(e) {
		e.preventDefault();
		$("#grand_tax").modal('show');
	});
	
	$(document.body).on('click', '.final_ship_hand_tax' ,function(e) {
		e.preventDefault();
		$("#shipping_handling_tax").modal('show');
	});
	
	
	//-- showing % discount entry option
	$(document.body).on('click', '.item_perc_dis' ,function(e) {
		var current_id = this.id;
		$("#perc_discount_val_span_"+current_id).show('slow');
		$("#dir_discount_val_span_"+current_id).hide('slow');
	});
	//-- showing % discount entry option --//
	
	//-- showing direct discount entry option
	$(document.body).on('click', '.item_direct_dis' ,function(e){
		var current_id = this.id;
		$("#dir_discount_val_span_"+current_id).show('slow');
		$("#perc_discount_val_span_"+current_id).hide('slow');
	});
	//-- howing direct discount entry option ends --//
	
	//-- no discount
	$(document.body).on('click', '.item_no_dis' ,function(e){
		var current_id =  this.id;
		$("#dir_discount_val_span_"+current_id).hide('slow');
		$("#perc_discount_val_span_"+current_id).hide('slow');
	});
	//-- no discount ends --//
	
	//-- finally setting the discount %, direct or no discount. Oh also do the calculation on line amount
	$(document.body).on('click', '.set_line_discount' ,function(e) {
		var current_id =  this.id;
		var discount_selected = $('input[name=line_discount_'+current_id+']:checked').val() ;
		var discount_type = '';
		var discount_value = 0 ;
		var discounted_amount = 0 ;
		
		if (discount_selected ==1) {
			$("#line_discount_type_"+current_id).attr('value','no_discount');
			$("#line_discount_value_"+current_id).attr('value',0);
		}
		if (discount_selected == 2) {
			if ($("#line_perc_discount_val_"+current_id).val() == '') {
				display_js_error('Please add discount value','js_errors');
				return false;
			} else {
				discount_type = 'percentage';
				discount_value = $("#line_perc_discount_val_"+current_id).val() ;
				$("#line_discount_type_"+current_id).attr('value','percentage');
				$("#line_discount_value_"+current_id).attr('value',discount_value);
			}
		}
		if (discount_selected == 3 ) {
			if ($("#line_dir_discount_val_"+current_id).val() == '') {
				display_js_error('Please add discount value','js_errors');
				return false;
			} else {
				discount_type = 'direct';
				discount_value = $("#line_dir_discount_val_"+current_id).val() ;
				discounted_amount = discount_value ;
				$("#line_discount_type_"+current_id).attr('value','direct');
				$("#line_discount_value_"+current_id).attr('value',discount_value);
			}
		}
		reset_line_values(current_id);
		$("#item_discount").modal('hide');
	});
	
	$(document.body).on('click', '.set_line_discount_edit' ,function(e) {
		var current_id =  this.id;
		var discount_selected = $('input[name=line_discount_edit_'+current_id+']:checked').val() ;
		var discount_type = '';
		var discount_value = 0 ;
		var discounted_amount = 0 ;
		if (discount_selected ==1) {
			$("#line_discount_type_"+current_id).attr('value','no_discount');
			$("#line_discount_value_"+current_id).attr('value',0);
		}
		if (discount_selected == 2) {
			if ($("#line_perc_discount_val_edit_"+current_id).val() == '') {
				display_js_error(ADD_LINE_DISCOUNT_VALUE,'js_errors');
				return false;
			} else {
				discount_type = 'percentage';
				discount_value = $("#line_perc_discount_val_edit_"+current_id).val() ;
				$("#line_discount_type_"+current_id).attr('value','percentage');
				$("#line_discount_value_"+current_id).attr('value',discount_value);
			}
		}
		if (discount_selected == 3) {
			if ($("#line_dir_discount_val_edit_"+current_id).val() == '') {
				display_js_error(ADD_LINE_DISCOUNT_VALUE,'js_errors');
				return false;
			} else {
				discount_type = 'direct';
				discount_value = $("#line_dir_discount_val_edit_"+current_id).val() ;
				discounted_amount = discount_value ;
				$("#line_discount_type_"+current_id).attr('value','direct');
				$("#line_discount_value_"+current_id).attr('value',discount_value);
			}
		}
		reset_line_values(current_id);
		$("#item_discount_"+current_id).modal('hide');
	});
	
	//-- tax options on line item, if item is not having any tax show the defaut tax options
	$(document.body).on('click', '.line_item_tax' ,function(e) {
		e.preventDefault();
		var current_id =  this.id;
		if ($("#line_item_name_"+current_id).val() == '') {
			display_js_error(SELECT_LINE_ITEM,'js_errors');
			return false;
		}
		
		if ($(this).closest('tr').find('.line_item_quantity').val() == '') {
			display_js_error(ADD_LINE_ITEM_QTY,'js_errors');
			return false;
		}
		
		if ($("#line_item_price_"+current_id).val() == '') {
			display_js_error(ADD_LINE_ITEM_PRICE,'js_errors');
			return false;
		}
		
		if ($("#line_has_tax_"+current_id).val() == '0') {
			$("#tax_line_name").html($("#line_item_name_"+current_id).val());
			$("#item_no_tax").modal('show');
			$(".set_tax_from_default").attr("id",current_id);
		} else {
			//do something
			$("#line_tax_"+current_id).modal('show');
		}
	});
	
	$(document.body).on('click', '.line_item_tax_available_edit' ,function(e) {
		e.preventDefault();
		var current_id =  this.id;
		if ($("#line_item_name_"+current_id).val() == '') {
			display_js_error(SELECT_LINE_ITEM,'js_errors');
			return false;
		}
		
		if ($(this).closest('tr').find('.line_item_quantity').val() == '') {
			display_js_error(ADD_LINE_ITEM_QTY,'js_errors');
			return false;
		}
		
		if ($("#line_item_price_"+current_id).val() == '') {
			display_js_error(ADD_LINE_ITEM_PRICE,'js_errors');
			return false;
		}
		
		if ($("#line_has_tax_"+current_id).val() == '0') {
			$("#tax_line_name").html($("#line_item_name_"+current_id).val());
			$("#item_no_tax").modal('show');
			$(".set_tax_from_default").attr("id",current_id);
		} else {
			//do something
			$("#line_tax_"+current_id).modal('show');
		}
	});
	//-- tax options on line item ends --//
	
	//-- if the item does not have a tax associated then allow to add defaut tax and display that
	$(document.body).on('click', '.set_default_tax' ,function(e) {
		e.preventDefault();
		$("#defaut_tax_block").show('slow');
		$("#set_tax_from_default_footer").show('slow');
	});
	//-- default tax section show ends here
	
	//-- set the available tax to the line amount
	$(document.body).on('click', '.set_tax_available' ,function(e) {
		e.preventDefault();
		var current_id =  this.id;
		var tax_values ='';
		var tax_amount = 0 ;
		var line_total_after_discount = $("#line_total_after_discount_"+current_id).val();
		var taxes = $('input[type="checkbox"][name="cb_line_tax_ft_'+current_id+'\\[\\]"]:checked').map(function() { 
			tax_values += this.value+'::'+$("#cb_linetax_val_"+this.value+'_'+current_id).val()+',';
		}).get();
		
		$("#line_item_tax_values_"+current_id).attr('value',tax_values);
		reset_line_values(current_id);
		$("#line_tax_"+current_id).modal('hide');
	});
	
	$(document.body).on('click', '.set_default_tax' ,function(e) {
		e.preventDefault();
		$("#defaut_tax_block").show('slow');
		$("#set_tax_from_default_footer").show('slow');
	});
	//-- default tax section show ends here
	
	//-- set the available tax to the line amount
	$(document.body).on('click', '.set_tax_available_edit' ,function(e) {
		e.preventDefault();
		var current_id =  this.id;
		var tax_values ='';
		var tax_amount = 0 ;
		var line_total_after_discount = $("#line_total_after_discount_"+current_id).val();
		var taxes = $('input[type="checkbox"][name="cb_line_tax_ft_'+current_id+'\\[\\]"]:checked').map(function() { 
			tax_values += this.value+'::'+$("#cb_linetax_val_"+this.value+'_'+current_id).val()+',';
		}).get();
		$("#line_item_tax_values_"+current_id).attr('value',tax_values);
		reset_line_values(current_id);
		$("#line_tax_"+current_id).modal('hide');
	});
	//-- setting available tax on line amount ends --//
	
	
	//-- set the available tax to the line amount
	$(document.body).on('click', '.set_tax_from_default' ,function(e) {
		e.preventDefault();
		var current_id =  this.id;
		var tax_values ='';
		var tax_amount = 0 ;
		var taxes = $('input[type="checkbox"][name="line_default_tax_opts\\[\\]"]:checked').map(function() { 
			tax_values += this.value+'::'+$("#line_default_tax_val_"+this.value).val()+',';
		}).get();
		$("#line_item_tax_values_"+current_id).attr('value',tax_values);
		reset_line_values(current_id);
		$("#item_no_tax").modal('hide');
	});
	//-- setting available tax on line amount ends --//
	
	
	//-- setting grand tax 
	$(document.body).on('click', '.set_grand_tax' ,function(e) {
		e.preventDefault();
		var tax_values ='';
		var tax_amount = 0 ;
		var taxes = $('input[type="checkbox"][name="grand_tax_opts\\[\\]"]:checked').map(function() { 
			tax_values += this.value+'::'+$("#grand_tax_val_"+this.value).val()+',';
		}).get();
		$("#final_tax_val").attr('value',tax_values);
		reset_grand_values();
		$("#grand_tax").modal('hide');
	});
	//-- setting grand ends
	
	$(document.body).on('change keyup mouseup blur','#final_ship_hand_charge',function() {
		reset_grand_values();
	});
	
	$(document.body).on('change keyup mouseup blur','#final_adjustment_val',function() {
		reset_grand_values();
	});
	
	$(document.body).on('change','#final_adjustment',function() {
		reset_grand_values();
	});
	
	//-- setting shipping and handling tax on net total
	$(document.body).on('click', '.set_shipping_handling_tax' ,function(e) {
		e.preventDefault();
		var tax_values ='';
		var tax_amount = 0 ;
		var taxes = $('input[type="checkbox"][name="grand_shtax_opts\\[\\]"]:checked').map(function() { 
			tax_values += this.value+'::'+$("#sh_tax_val_"+this.value).val()+',';
		}).get();
		$("#final_ship_hand_tax_val").attr('value',tax_values);
		reset_grand_values();
		$("#shipping_handling_tax").modal('hide');
	});
	//-- setting shipping and handling tax ends
	
	//-- setting grand discount
	$(document.body).on('click', '.set_grand_discount' ,function(e) {
		var discount_selected = $('input[name=grand_discount_option]:checked').val() ;
		if (discount_selected ==1) {
			$("#final_discount_type").attr('value','no_discount');
			$("#final_discount_val").attr('value',0);
			$("#final_discounted_total").attr('value',0);
			$(".final_discount_dis").html('0.00');
		}
		
		if (discount_selected == 2) {
			discount_type = 'percentage';
			discount_value = $("#grand_perc_discount").val() ;
			$("#final_discount_type").attr('value','percentage');
			$("#final_discount_val").attr('value',discount_value);
		}
		if (discount_selected == 3) {
			discount_type = 'direct';
			discount_value = $("#grand_dir_discount").val() ;
			discounted_amount = discount_value ;
			$("#final_discount_type").attr('value','direct');
			$("#final_discount_val").attr('value',discount_value);
		}
		reset_grand_values();
		$("#grand_discount").modal('hide');
	});
	//-- setting grand discount ends
});