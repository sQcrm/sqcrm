<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* add view form fields section
* @author Abhik Chakraborty
*/
$currency = $_SESSION["do_global_settings"]->get_setting_data_by_name('currency_setting');
$currency_data = json_decode($currency,true);
$do_tax = new TaxSettings();
$shipping_handling_tax = $do_tax->shipping_handling_tax();
$product_service_tax = $do_tax->product_service_tax();

?>
<div class="box_content_header">
	<?php echo _('Terms and condition'); ?>
	<hr class="form_hr">
	<textarea name="terms_cond" id="terms_cond" class="expand_text_area"><?php echo $tems_condition;?></textarea>
	<br /><br />
</div>

<div class="box_content_header">
	<div class="left_300"><?php echo _('Item Information'); ?></div>
	<div class="right_300">
		<a href="#" class="btn btn-primary add_new_line_item">
		<i class="glyphicon glyphicon-plus"></i><?php echo _('Add more');?></a>
	</div>
	<table class="table table-bordered" id="table_line_items">
		<thead>
			<tr>
				<th width="2%"></th>
				<th width="30%"><?php echo _('Item Name');?></th>
				<th width="10%"><?php echo _('Qty');?></th>
				<th width="18%"><?php echo _('Price');?></th>
				<th width="20%"><?php echo _('Total');?></th>
				<th width="20%"><?php echo _('Net Price');?></th>
			</tr>
		</thead>
		<tbody>
			<!-- line item 1 -->
			<tr id="1">
				<td>
					<a href="#" class="btn btn-primary btn-xs delete_line_item" id="1"><i class="glyphicon glyphicon-trash"></i></a>
				</td>
				<td>
					<select name="line_item_selector_opt[]" class="form-control input-sm" id="line_item_selector_opt_1" onchange="lineItemTypeChanged('1');">
						<option value="product"><?php echo _('Products'); ?></option>
						<option value="manual"><?php echo _('Manual'); ?></option>
					</select>
					<br /><br />
					<input name="line_item_name[]" id="line_item_name_1" autocomplete="off" type="text" class="form-control input-sm" readonly>
					<input type="hidden" name="line_item_value[]" id="line_item_value_1">
					<input type="hidden" name="line_item_type[]" id="line_item_type_1">
					&nbsp;&nbsp;
					<span id="line_item_selector_block_1">
						<a href="#"  id="1"  class="line_item_selector btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus-sign"></i></a>
					</span>
					<br /><br />
					<textarea name="line_item_description[]" id="line_item_description_1" class="form-control input-sm"></textarea>
				</td>
				<td>
					<input class="line_item_quantity form-control input-sm" name="line_item_quantity[]" id="1" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number"></td>
				<td>	
					<div style="height:40px;">
						<input class="line_item_price form-control input-sm" name="line_item_price[]" id="line_item_price_1" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number" readonly>
					</div>
					
					<div style="height:40px;">
						<a href="#" id="1" class="line_item_discount"><?php echo _('Discount'); ?></a>
						<input type="hidden" name="line_discount_type[]" id="line_discount_type_1">
						<input type="hidden" name="line_discount_value[]" id="line_discount_value_1">
					</div>
					
					<div style="height:40px;">
						<strong><?php echo _('Total after discount')?></strong>
					</div>
					
					<div style="height:40px;" id="line_item_tax_section">
						<a href="#" id="1" class="line_item_tax"><?php echo _('Tax'); ?></a>
						<input type="hidden" name="line_has_tax_1" id="line_has_tax_1" value='0'>
						<input type="hidden" name="line_tax_selected[]" class=".line_tax_selected" id="line_tax_selected_1">
						<div id="line_tax_1" class="modal hide fade"></div>
					</div>			
				</td>
				<td>
					<div style="height:40px;">
						<input type="hidden" id="line_item_total_1" name = line_item_total[]>
						<span class="total_1" id="total_1">0.00</span>
					</div>
					
					<div style="height:40px;">
						<input type="hidden" id="line_discounted_amount_value_1" name = line_discounted_amount_value[]>
						<span class="line_discounted_amount_1" id="line_discounted_amount_1">0.00</span>
					</div>
					
					<div style="height:40px;">
						<input type="hidden" id="line_total_after_discount_given_1" name="line_total_after_discount_given[]">
						<span class="line_total_after_discount_1" id="line_total_after_discount_1">0.00</span>
					</div>
					
					<div style="height:40px;">
						<input type="hidden" id="line_item_tax_values_1" name="line_item_tax_values[]">
						<input type="hidden" id="line_item_tax_total_1" name="line_item_tax_total[]">
						<span class="line_tax_on_total_1" id="line_tax_on_total_1">0.00</span>
					</div>	
				</td>
				<td>
					<input type="hidden" id="line_net_price_1" name="line_net_price[]">
					<span id="line_net_price_section_1"></span>
				</td>
			</tr>
		</tbody>
	</table>
	<!-- grand total -->
	<table class="table table-bordered" id="table_grand_total">
		<tbody>
			<tr>
				<td width="80%">
					<div style="height:20px;text-align:right;">
						<b>Net Total<b></td>
					</div>
				<td width="20%">
					<div style="height:20px;text-align:right;">
						<input type="hidden" name="net_total_lines" id="net_total_lines">
						<span class="net_total_lines">0.00</span>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;">
						<b><a href="#" class="final_discount"><?php echo _('Discount'); ?><b></a>
						<input type="hidden" name="final_discount_val" id="final_discount_val">
						<input type="hidden" name="final_discount_type" id="final_discount_type">
						<input type="hidden" name="final_discounted_total" id="final_discounted_total">
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;">
						<span class="final_discount_dis">0.00</span>
					</div>
				</td>
			</tr>
			
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;">
						<b><a href="#" class="final_tax"><?php echo _('Tax'); ?><b></a>
						<input type="hidden" name="final_tax_val" id="final_tax_val">
						<input type="hidden" name="final_tax_amount" id="final_tax_amount">
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;">
						<span class="final_tax_dis">0.00</span>
					</div>
				</td>
			</tr>
			
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:30px;text-align:right;">
						<b><?php echo _('Shipping/Handling charges'); ?><b>
					</div>
				</td>
				<td width="20%">
					<div style="height:30px;text-align:right;">
						<input class="form-control input-sm" name="final_ship_hand_charge" id="final_ship_hand_charge" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number">
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;">
						<b><a href="#" class="final_ship_hand_tax"><?php echo _('Shipping/Handling Tax'); ?><b></a>
						<input type="hidden" name="final_ship_hand_tax_val" id="final_ship_hand_tax_val">
						<input type="hidden" name="final_ship_hand_tax_amount" id="final_ship_hand_tax_amount">
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;">
						<span class="final_ship_hand_tax_dis">0.00</span>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:30px;text-align:right;">
						<b><?php echo _('Final Adjustment'); ?><b>
						<select class="input-small" name="final_adjustment" id="final_adjustment">
							<option value="none"><?php echo _('Select');?></option>
							<option value="add"><?php echo _('Add');?></option>
							<option value="deduct"><?php echo _('Deduct');?></option>
						</select>
					</div>
				</td>
				<td width="20%">
					<div style="height:30px;text-align:right;">
						<input class="input-small" name="final_adjustment_val" id="final_adjustment_val" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number">
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;">
						<b><?php echo _('Grand Total'); ?><b>
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;">
						<input type="hidden" name="grand_total" id="grand_total">
						<span class="grand_total_val">0.00</span>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<script src="/js/lineitem.js?v=1.2"></script>
<!-- popup modal no tax for item with options to set defaut tax -->
<div class="modal fade" tabindex="-1" role="dialog" id="item_no_tax">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="badge badge-info"><?php echo _('No tax available for - ');?><span id="tax_line_name"></span></span>
		</div>
		<div class="modal-body">
			<div class="box_content">
				<strong><?php echo _('Item does not have any tax');?></strong>
				<br /><br />
				<a href="#" class="set_default_tax"><?php echo _('still would like to add tax'); ?></a>
				<br /><br />
				<div id="defaut_tax_block" style="display:none;">
					<table>
					<?php
					$i= 0 ; 
					foreach ($product_service_tax as $key=>$val) {
						$i++;
					?>
					<tr>
						<td style="width:150px;">
							<input type="checkbox" name="line_default_tax_opts[]" id = "line_default_tax_<?php echo $i; ?>" value="<?php echo $val["tax_name"];?>">
							<span style="font-size: 12px;margin-left:4px;"><?php echo $val["tax_name"];?> ( % )</span>
						</td>
						<td style="margin-left:5px;">
							<input type="text" value="<?php echo $val["tax_value"];?>" class="input-mini" id="line_default_tax_val_<?php echo $val["tax_name"];?>" name="grand_tax_val_<?php echo $val["tax_name"];?>">
						</td>
					</tr>
					<?php 
					}
					?>
					</table>
				</div>
			</div>
		</div>
		<div class="modal-footer" id="set_tax_from_default_footer">
			<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
			<input type="button" id="" class="btn btn-primary set_tax_from_default" value="<?php echo _('Set Tax')?>"/>
		</div>
	</div>
</div>

<!-- popup modal for grand tax -->
<div class="modal fade" tabindex="-1" role="dialog" id="grand_tax">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="badge badge-info"><?php echo _('Set final tax - ');?><span id="grand_tax_name"></span></span>
		</div>
		<div class="modal-body">
			<div class="box_content">
				<table>
				<?php
				$i= 0 ; 
				foreach ($product_service_tax as $key=>$val) {
					$i++;
				?>
					<tr>
						<td style="width:150px;">
							<input type="checkbox" name="grand_tax_opts[]" id = "cb_grand_tax_<?php echo $i; ?>" value="<?php echo $val["tax_name"];?>">
							<span style="font-size: 12px;margin-left:4px;"><?php echo $val["tax_name"];?> ( % )</span>
						</td>
						<td style="margin-left:5px;">
							<input type="text" value="<?php echo $val["tax_value"];?>" class="input-mini" id="grand_tax_val_<?php echo $val["tax_name"];?>" name="grand_tax_val_<?php echo $val["tax_name"];?>">
						</td>
					</tr>
					<?php 
					}
					?>
				</table>
			</div>
		</div>
		<div class="modal-footer" id="set_grand_tax_from_default_footer">
			<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
			<input type="button" id="" class="btn btn-primary set_grand_tax" value="<?php echo _('Set Tax')?>"/>
		</div>
	</div>
</div>

<!-- popup modal for shipping and handling tax on grand total -->
<div class="modal fade" tabindex="-1" role="dialog" id="shipping_handling_tax">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="badge badge-info"><?php echo _('Set shipping and handling tax - ');?><span id="grand_tax_name"></span></span>
		</div>
		<div class="modal-body">
			<div class="box_content">
				<table>
					<?php
					$i= 0 ; 
					foreach ($shipping_handling_tax as $key=>$val) {
						$i++;
					?>
					<tr>
						<td style="width:150px;">
							<input type="checkbox" name="grand_shtax_opts[]" id = "cb_sh_tax_<?php echo $i; ?>" value="<?php echo $val["tax_name"];?>">
							<span style="font-size: 12px;margin-left:4px;"><?php echo $val["tax_name"];?> ( % )</span>
						</td>
						<td style="margin-left:5px;">
							<span>
							<input type="text" value="<?php echo $val["tax_value"];?>" class="input-mini" id="sh_tax_val_<?php echo $val["tax_name"];?>" name="sh_tax_val_<?php echo $val["tax_name"];?>">
						</td>
					</tr>
				<?php 
				}
				?>
				</table>
			</div>
		</div>
		<div class="modal-footer" id="set_shipping_handling_tax_from_default_footer">
			<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
			<input type="button" id="" class="btn btn-primary set_shipping_handling_tax" value="<?php echo _('Set Tax')?>"/>
		</div>
	</div>
</div>

<!-- popup modal for line item discount -->
<div class="modal fade" tabindex="-1" role="dialog" id="item_discount">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="badge badge-info"><?php echo _('Set Discount for ');?><span id="on_price"></span></span>
		</div>
		<div class="modal-body">
			<div class="box_content">
				<table class="table">
					<tr>
						<td colspan="2">
							<input type="radio" id="" name="" class="item_no_dis" value="1">&nbsp;&nbsp;<?php echo _('no discount');?>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" id="" name="" class="item_perc_dis" value="2">
							&nbsp;&nbsp;<?php echo _('% discount');?>
						</td>
						<td>
							<span id="" class= "perc_discount_val_span" style="display:none;">
								<input type="number" class="input-small perc_discount_val" name="" id=""> %
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" id="" name="" class="item_direct_dis" value="3">
							&nbsp;&nbsp;<?php echo _('direct reduction');?>
						</td>
						<td>
							<span id="" class="dir_discount_val_span" style="display:none;">
								<input type="number" class="input-small dir_discount_val" name="" id="">
							</span>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
			<input type="button" id="" class="btn btn-primary set_line_discount" value="<?php echo _('Set Discount')?>"/>
		</div>
	</div>
</div>

<!-- popup modal for grand discount -->
<div class="modal fade" tabindex="-1" role="dialog" id="grand_discount">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="badge badge-info"><?php echo _('Set the grand discount on ');?><span id="grand_on_price"></span></span>
		</div>
		<div class="modal-body">
			<div class="box_content">
				<table class="table">
					<tr>
						<td colspan="2">
							<input type="radio" id="grand_discount_option" name="grand_discount_option" class="" value="1">&nbsp;&nbsp;<?php echo _('no discount');?>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" id="grand_discount_option" name="grand_discount_option" class="" value="2">
							&nbsp;&nbsp;<?php echo _('% discount');?>
						</td>
						<td>
							<span id="" class= "grand_discount_val_span" style="">
								<input type="number" class="input-small" name="grand_perc_discount" id="grand_perc_discount"> %
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" id="grand_discount_option" name="grand_discount_option" class="" value="3">
							&nbsp;&nbsp;<?php echo _('direct reduction');?>
						</td>
						<td>
							<span id="" class="grand_dir_discount_val_span" style="">
								<input type="number" class="input-small" name="grand_dir_discount" id="grand_dir_discount">
							</span>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
			<input type="button" id="" class="btn btn-primary set_grand_discount" value="<?php echo _('Set Discount')?>"/>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="delete_lineitem_warning">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="badge warning"><?php echo _('DELETE CONFIRM');?></span>
		</div>
		<div class="modal-body">
			<div class="box_content">
				<strong><?php echo _('Are you sure you want to delete');?></strong>
			</div>
		</div>
		<div class="modal-footer" id="set_tax_from_default_footer">
			<a href="#" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Close');?></a>
			<input type="button" id="" class="btn btn-primary line_item_delete_true" value="<?php echo _('Yes')?>"/>
		</div>
	</div>
</div>
<script>
/**
* item type selector change function
*/
function lineItemTypeChanged(current_id) {
	var selector_id = 'line_item_selector_opt_'+current_id ;
	var line_item_type = $('#'+selector_id).val();
	if (line_item_type === 'manual') {
		$('#line_item_name_'+current_id).prop('readonly', false);
		$('#line_item_price_'+current_id).prop('readonly', false);
		$('#line_item_selector_block_'+current_id).hide();
	} else if (line_item_type === 'product') {
		$('#line_item_name_'+current_id).prop('readonly', true);
		$('#line_item_price_'+current_id).prop('readonly', true);
		$('#line_item_selector_block_'+current_id).show();
	}
}
</script>