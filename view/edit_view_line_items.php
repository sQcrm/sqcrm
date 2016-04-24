<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* edit view form fields section
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
	<textarea name="terms_cond" id="terms_cond" class="expand_text_area"><?php echo $module_obj->terms_condition;?></textarea>
	<?php
	if ($module_obj->terms_condition == '') {?>
	<br /><br />
	<span style="font-size:12px;line-height:1.3;">
		<input type="checkbox" id="copy_default_terms_cond">
		<?php
		echo _('Copy default terms & condition');
	}?>
	</span>
	<div style="display:none;"><textarea id="terms_cond_copy"><?php echo $tems_condition;?></textarea></div>
		<script>
		$(document.body).on('click', '#copy_default_terms_cond' ,function(e) {
			if ($(this).is(":checked")) {
				$("#terms_cond").val($("#terms_cond_copy").val());
			} else { 
				$("#terms_cond").val('');
			}
		});
		</script>
	</div>
	<div class="box_content_header">
		<div class="left_300"><?php echo _('Item Information'); ?></div>
		<div class="right_300">
			<a href="#" class="btn btn-primary add_new_line_item">
			<i class="icon-white icon-plus"></i><?php echo _('Add more');?></a>
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
				<?php
				if (is_array($lineitems) && count($lineitems) > 0) {
					foreach ($lineitems as $lineitem) { ?>
					<tr id="<?php echo $lineitem["idlineitems"]; ?>">
						<td>
							<input type="hidden" name="idlineitems[]" value="<?php echo $lineitem["idlineitems"]; ?>">
							<a href="#" class="btn btn-primary btn-mini bs-prompt delete_line_item" id="<?php echo $lineitem["idlineitems"]; ?>">
							<i class="icon-white icon-trash"></i>
							</a>
						</td>
						<td>
							<select name="line_item_selector_opt[]" id="line_item_selector_opt_<?php echo $lineitem["idlineitems"]; ?>" onchange="lineItemTypeChanged(<?php echo $lineitem["idlineitems"];?>);">
								<option value="product" <?php echo ($lineitem["item_type"] == 'product' ? "SELECTED":""); ?>>
									<?php echo _('Products'); ?>
								</option>
								<option value="manual" <?php echo ($lineitem["item_type"] == 'manual' ? "SELECTED":""); ?>>
									<?php echo _('Manual'); ?>
								</option>
							</select>
							<?php
							$item_selector_block_style = 'display:block;float:right;margin-top:3px;margin-right:35px;';
							$readonly = 'readonly';
							
							if ($lineitem["item_type"] == 'manual') {
								$item_selector_block_style = 'display:none;float:right;margin-top:3px;margin-right:35px;';
								$readonly = '';
							}
							?>
							<br /><br />
							<input name="line_item_name[]" id="line_item_name_<?php echo $lineitem["idlineitems"]; ?>" value="<?php echo $lineitem["item_name"]; ?>" autocomplete="off" type="text" class="input-xlarge-100 line_item_name" <?php echo $readonly;?>>
							<input type="hidden" value="<?php echo $lineitem["item_value"]; ?>" name="line_item_value[]" id="line_item_value_<?php echo $lineitem["idlineitems"]; ?>">
							<input type="hidden" value="<?php echo $lineitem["item_type"]; ?>" name="line_item_type[]" id="line_item_type_<?php echo $lineitem["idlineitems"]; ?>">
							
							<span style="<?php echo $item_selector_block_style;?>" id="line_item_selector_block_<?php echo $lineitem["idlineitems"];?>">
							<a href="#" id="<?php echo $lineitem["idlineitems"]; ?>"  class="line_item_selector btn btn-primary btn-mini"><i class="icon-white icon-plus-sign"></i></a>
							</span>
							<br /><br />
							<textarea name="line_item_description[]" id="line_item_description_<?php echo $lineitem["idlineitems"]; ?>" class="input-xlarge-100"><?php echo $lineitem["item_description"];?></textarea>
						</td>
						<td>
							<input class="input-mini line_item_quantity" value="<?php echo $lineitem["item_quantity"]; ?>" name="line_item_quantity[]" id="<?php echo $lineitem["idlineitems"]; ?>" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number">
						</td>
						<td>
							<div style="height:40px;">
								<input class="input-small line_item_price" value="<?php echo $lineitem["item_price"]; ?>" name="line_item_price[]" id="line_item_price_<?php echo $lineitem["idlineitems"]; ?>" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number" <?php echo $readonly;?>>
							</div>
							<div style="height:40px;">
								<a href="#" id="<?php echo $lineitem["idlineitems"]; ?>" class="line_item_discount_edit"><?php echo _('Discount'); ?></a>
								<input type="hidden" value="<?php echo $lineitem["discount_type"];?>" name="line_discount_type[]" id="line_discount_type_<?php echo $lineitem["idlineitems"]; ?>">
								<input type="hidden" value="<?php echo $lineitem["discount_value"];?>" name="line_discount_value[]" id="line_discount_value_<?php echo $lineitem["idlineitems"]; ?>">
								<!-- popup modal for line item discount -->
								<div class="modal hide fade" id="item_discount_<?php echo $lineitem["idlineitems"]; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>
										<span class="badge badge-info"><?php echo _('Set Discount for ');?><span id="on_price"><?php echo $lineitem["item_price"]; ?></span></span>
									</div>
									<div class="modal-body">
										<div class="box_content">
											<table class="table">
												<tr>
													<td colspan="2">
														<input type="radio" <?php echo ($lineitem["discount_type"] =='no_discount' || $lineitem["discount_type"]=='' ? 'checked': '');?> id="<?php echo $lineitem["idlineitems"]; ?>" name="line_discount_edit_<?php echo $lineitem["idlineitems"]; ?>" class="item_no_dis_edit" value="1">&nbsp;&nbsp;<?php echo _('no discount');?>
													</td>
												</tr>
												<tr>
													<td>
														<input type="radio" <?php echo ($lineitem["discount_type"] =='percentage' ? 'checked': '');?> id="<?php echo $lineitem["idlineitems"]; ?>" name="line_discount_edit_<?php echo $lineitem["idlineitems"]; ?>" class="item_perc_dis_edit" value="2">
														&nbsp;&nbsp;<?php echo _('% discount');?>
													</td>
													<td>
														<span id="" class= "perc_discount_val_span_edit">
															<input type="number" value="<?php echo ($lineitem["discount_type"] =='percentage' ? $lineitem["discount_value"] : '');?>" class="input-small perc_discount_val_edit" name="line_perc_discount_val_edit_<?php echo $lineitem["idlineitems"]; ?>" id="line_perc_discount_val_edit_<?php echo $lineitem["idlineitems"]; ?>"> %
														</span>
													</td>
												</tr>
												<tr>
													<td>
														<input type="radio" <?php echo ($lineitem["discount_type"] =='direct' ? 'checked': '');?> id="<?php echo $lineitem["idlineitems"]; ?>" name="line_discount_edit_<?php echo $lineitem["idlineitems"]; ?>" class="item_direct_dis_edit" value="3">
														&nbsp;&nbsp;<?php echo _('direct reduction');?>
													</td>
													<td>
														<span id="<?php echo $lineitem["idlineitems"]; ?>" class="dir_discount_val_span_edit">
															<input type="number" value="<?php echo ($lineitem["discount_type"] =='direct' ? $lineitem["discount_value"] : '');?>" class="input-small dir_discount_val_edit" name="line_dir_discount_val_edit_<?php echo $lineitem["idlineitems"]; ?>" id="line_dir_discount_val_edit_<?php echo $lineitem["idlineitems"]; ?>">
														</span>
													</td>
												</tr>
											</table>
										</div>
									</div>
									<div class="modal-footer">
										<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
										<input type="button" id="<?php echo $lineitem["idlineitems"]; ?>" class="btn btn-primary set_line_discount_edit" value="<?php echo _('Set Discount')?>"/>
									</div>
								</div>
							</div>
							<div style="height:40px;">
								<strong><?php echo _('Total after discount')?></strong>
							</div>
							
							<div style="height:40px;" id="line_item_tax_section">
								<a href="#" id="<?php echo $lineitem["idlineitems"]; ?>" class="line_item_tax_available_edit"><?php echo _('Tax'); ?></a>
								<input type="hidden" value="<?php (false === $lineitem["product_available_tax"] ? 0:1)?>" name="line_has_tax_<?php echo $lineitem["idlineitems"]; ?>" id="line_has_tax_<?php echo $lineitem["idlineitems"]; ?>">
								<input type="hidden" name="line_tax_selected[]" class=".line_tax_selected" id="line_tax_selected_<?php echo $lineitem["idlineitems"]; ?>">
								<?php
									//if(false !== $lineitem["product_available_tax"]){
								?>
								<div id="line_tax_<?php echo $lineitem["idlineitems"]; ?>" class="modal hide fade">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>
										<span class="badge badge-info"><?php echo _('Set Tax for -').$lineitem["item_name"];?>
										<span id="tax_line_name"></span></span>
									</div>
									<div class="modal-body">
										<div class="box_content">
											<table>
												<?php
												$product_available_tax = $lineitem["product_available_tax"];
												$product_tax_selected = $lineitem["tax_values"];
												//var_dump($product_available_tax);
												if (is_array($product_available_tax) && count($product_available_tax) > 0) {
													if (strlen($product_tax_selected) > 5) {
														$product_tax_selected  = rtrim($product_tax_selected,',');
														$product_tax_selected_array = explode(',',$product_tax_selected);
														$selected_array = array();
														if (count($product_tax_selected_array) > 0) {
															foreach ($product_tax_selected_array as $k=>$sel_value) {
																$sel_value_array = explode('::',$sel_value);
																$selected_array[$sel_value_array[0]] = $sel_value_array[1];
															}
														}
														foreach ($product_available_tax as $key=>$val) {
															if (array_key_exists($key,$selected_array)) {
																echo '<tr>';
																echo '<td style="width:90px;">';
																$checked = '';
																echo '<input type="checkbox" CHECKED name="cb_line_tax_ft_'.$lineitem["idlineitems"].'[]" value="'.$key.'">';
																echo '<span style="font-size: 12px;margin-left:4px;">'.$key.' ( % )</span>';
																echo '</td>';
																echo '<td style="margin-left:5px;"><input type="text" value="'.$selected_array[$key].'" class="input-mini" id="cb_linetax_val_'.$key.'_'.$lineitem["idlineitems"].'"></td>';
																echo '</tr>';
															} else {
																echo '<tr>';
																echo '<td style="width:90px;">';
																$checked = '';
																echo '<input type="checkbox" name="cb_line_tax_ft_'.$lineitem["idlineitems"].'[]" value="'.$key.'">';
																echo '<span style="font-size: 12px;margin-left:4px;">'.$key.' ( % )</span>';
																echo '</td>';
																echo '<td style="margin-left:5px;"><input type="text" value="'.$val.'" class="input-mini" id="cb_linetax_val_'.$key.'_'.$lineitem["idlineitems"].'"></td>';
																echo '</tr>';
															}
														}
													} else {
														foreach ($product_available_tax as $key=>$val) {
															echo '<tr>';
															echo '<td style="width:90px;">';
															$checked = '';
															echo '<input type="checkbox" name="cb_line_tax_ft_'.$lineitem["idlineitems"].'[]" value="'.$key.'">';
															echo '<span style="font-size: 12px;margin-left:4px;">'.$key.' ( % )</span>';
															echo '</td>';
															echo '<td style="margin-left:5px;"><input type="text" value="'.$val.'" class="input-mini" id="cb_linetax_val_'.$key.'_'.$lineitem["idlineitems"].'"></td>';
															echo '</tr>';
														}
													}
												} else {
													if (strlen($product_tax_selected) > 5) { 
														$product_tax_selected  = rtrim($product_tax_selected,',');
														$product_tax_selected_array = explode(',',$product_tax_selected);
														$selected_array = array();
														if (count($product_tax_selected_array) > 0) {
															foreach ($product_tax_selected_array as $k=>$sel_value) {
																$sel_value_array = explode('::',$sel_value);
																$selected_array[$sel_value_array[0]] = $sel_value_array[1];
															}
														}
														foreach ($product_service_tax as $key=>$val) {
															if (array_key_exists($val["tax_name"],$selected_array)) {
																echo '<tr>';
																echo '<td style="width:90px;">';
																$checked = '';
																echo '<input type="checkbox" CHECKED name="cb_line_tax_ft_'.$lineitem["idlineitems"].'[]" value="'.$val["tax_name"].'">';
																echo '<span style="font-size: 12px;margin-left:4px;">'.$val["tax_name"].' ( % )</span>';
																echo '</td>';
																echo '<td style="margin-left:5px;"><input type="text" value="'.$selected_array[$val["tax_name"]].'" class="input-mini" id="cb_linetax_val_'.$val["tax_name"].'_'.$lineitem["idlineitems"].'"></td>';
																echo '</tr>';
															} else {
																echo '<tr>';
																echo '<td style="width:90px;">';
																$checked = '';
																echo '<input type="checkbox" name="cb_line_tax_ft_'.$lineitem["idlineitems"].'[]" value="'.$val["tax_name"].'">';
																echo '<span style="font-size: 12px;margin-left:4px;">'.$val["tax_name"].' ( % )</span>';
																echo '</td>';
																echo '<td style="margin-left:5px;"><input type="text" value="'.$val["tax_value"].'" class="input-mini" id="cb_linetax_val_'.$val["tax_name"].'_'.$lineitem["idlineitems"].'"></td>';
																echo '</tr>';
															}
														}
													} else { 
														foreach ($product_service_tax as $key=>$val) {
															echo '<tr>';
															echo '<td style="width:90px;">';
															$checked = '';
															echo '<input type="checkbox" name="cb_line_tax_ft_'.$lineitem["idlineitems"].'[]" value="'.$val["tax_name"].'">';
															echo '<span style="font-size: 12px;margin-left:4px;">'.$val["tax_name"].' ( % )</span>';
															echo '</td>';
															echo '<td style="margin-left:5px;"><input type="text" value="'.$val["tax_value"].'" class="input-mini" id="cb_linetax_val_'.$val["tax_name"].'_'.$lineitem["idlineitems"].'"></td>';
															echo '</tr>';
														}
													}
												}
												?>
											</table>
										</div>
									</div>
									<div class="modal-footer" id="set_tax_from_default_footer">
										<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
										<input type="button" id="<?php echo $lineitem["idlineitems"]; ?>" class="btn btn-primary set_tax_available_edit" value="Set Tax"/>
									</div>
								</div>
								<?php //} ?>
							</div>
						</td>
						<td>
							<div style="height:40px;">
								<input type="hidden" value="<?php echo $lineitem["item_price"]*$lineitem["item_quantity"];?>" id="line_item_total_<?php echo $lineitem["idlineitems"]; ?>" name = line_item_total[]>
								<span class="total_<?php echo $lineitem["idlineitems"]; ?>" id="total_<?php echo $lineitem["idlineitems"]; ?>"><?php echo $lineitem["item_price"]*$lineitem["item_quantity"];?></span>
							</div>
							
							<div style="height:40px;">
								<input type="hidden" value="<?php echo $lineitem["discounted_amount"];?>" id="line_discounted_amount_value_<?php echo $lineitem["idlineitems"]; ?>" name = line_discounted_amount_value[]>
								<span class="line_discounted_amount_<?php echo $lineitem["idlineitems"]; ?>" id="line_discounted_amount_<?php echo $lineitem["idlineitems"]; ?>"><?php echo $lineitem["discounted_amount"];?></span>
							</div>
							
							<div style="height:40px;">
								<input type="hidden" value="<?php echo $lineitem["total_after_discount"]; ?>" id="line_total_after_discount_given_<?php echo $lineitem["idlineitems"]; ?>" name="line_total_after_discount_given[]">
								<span class="line_total_after_discount_<?php echo $lineitem["idlineitems"]; ?>" id="line_total_after_discount_<?php echo $lineitem["idlineitems"]; ?>"><?php echo $lineitem["total_after_discount"]; ?></span>
							</div>
							
							<div style="height:40px;">
								<input type="hidden" id="line_item_tax_values_<?php echo $lineitem["idlineitems"]; ?>" value="<?php echo $lineitem["tax_values"]; ?>" name="line_item_tax_values[]">
								<input type="hidden" id="line_item_tax_total_<?php echo $lineitem["idlineitems"]; ?>" value="<?php echo $lineitem["taxed_amount"]; ?>" name="line_item_tax_total[]">
								<span class="line_tax_on_total_<?php echo $lineitem["idlineitems"]; ?>" id="line_tax_on_total_<?php echo $lineitem["idlineitems"]; ?>"><?php echo $lineitem["taxed_amount"]; ?></span>
							</div>	
						</td>
						<td>
							<input type="hidden" value="<?php echo $lineitem["net_total"]; ?>" id="line_net_price_<?php echo $lineitem["idlineitems"]; ?>" name="line_net_price[]">
							<span id="line_net_price_section_<?php echo $lineitem["idlineitems"]; ?>"><?php echo $lineitem["net_total"]; ?></span>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
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
						<input type="hidden" name="net_total_lines" id="net_total_lines" value="<?php echo $module_obj->net_total;?>">
						<span class="net_total_lines"><?php echo $module_obj->net_total;?></span>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;">
						<b><a href="#" class="final_discount"><?php echo _('Discount'); ?><b></a>
						<input type="hidden" name="final_discount_val" id="final_discount_val" value="<?php echo ($module_obj->discount_type =='' || $module_obj->discount_type =='no_discount' ? 0: $module_obj->discount_value); ?>">
						<input type="hidden" name="final_discount_type" id="final_discount_type" value = "<?php echo ($module_obj->discount_type =='' ? 'no_discount':$module_obj->discount_type) ;?>">
						<input type="hidden" name="final_discounted_total" id="final_discounted_total" value="<?php echo $module_obj->discounted_amount;?>">
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;">
						<span class="final_discount_dis"><?php echo $module_obj->discounted_amount;?></span>
					</div>
				</td>
			</tr>
			
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;">
						<b><a href="#" class="final_tax"><?php echo _('Tax'); ?><b></a>
						<input type="hidden" name="final_tax_val" id="final_tax_val" value="<?php echo $module_obj->tax_values;?>">
						<input type="hidden" name="final_tax_amount" id="final_tax_amount" value="<?php echo $module_obj->taxed_amount;?>">
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;">
						<span class="final_tax_dis"><?php echo $module_obj->taxed_amount;?></span>
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
						<input value="<?php echo $module_obj->shipping_handling_charge ;?>" class="input-small" name="final_ship_hand_charge" id="final_ship_hand_charge" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number">
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;">
						<b><a href="#" class="final_ship_hand_tax"><?php echo _('Shipping/Handling Tax'); ?><b></a>
						<input type="hidden" value="<?php echo $module_obj->shipping_handling_tax_values ;?>" name="final_ship_hand_tax_val" id="final_ship_hand_tax_val">
						<input type="hidden" value="<?php echo $module_obj->shipping_handling_taxed_amount;?>" name="final_ship_hand_tax_amount" id="final_ship_hand_tax_amount">
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;">
						<span class="final_ship_hand_tax_dis"><?php echo $module_obj->shipping_handling_taxed_amount;?></span>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:30px;text-align:right;">
						<b><?php echo _('Final Adjustment'); ?><b>
						<select class="input-small" name="final_adjustment" id="final_adjustment">
							<option value="none" <?php echo ($module_obj->final_adjustment_type =='none' ? 'SELECTED':'');?>><?php echo _('Select');?></option>
							<option value="add" <?php echo ($module_obj->final_adjustment_type =='add' ? 'SELECTED':'');?>><?php echo _('Add');?></option>
							<option value="deduct" <?php echo ($module_obj->final_adjustment_type =='deduct' ? 'SELECTED':'');?>><?php echo _('Deduct');?></option>
						</select>
					</div>
				</td>
				<td width="20%">
					<div style="height:30px;text-align:right;">
						<input class="input-small" value="<?php echo $module_obj->final_adjustment_amount; ?>" name="final_adjustment_val" id="final_adjustment_val" autocomplete="off" onkeypress="" ondrop="return false;" onpaste="return false;" type="number">
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
						<input type="hidden" name="grand_total" id="grand_total" value="<?php echo $module_obj->grand_total; ?>">
						<span class="grand_total_val"><?php echo $module_obj->grand_total; ?></span>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script src="/js/lineitem.js?v=1.2"></script>
<!-- popup modal no tax for item with options to set defaut tax -->
<div class="modal hide fade" id="item_no_tax">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
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
						<span>
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
	<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
	<input type="button" id="" class="btn btn-primary set_tax_from_default" value="<?php echo _('Set Tax')?>"/>
</div>
</div>

<!-- popup modal for grand tax -->
<div class="modal hide fade" id="grand_tax">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-info"><?php echo _('Set final tax - ');?><span id="grand_tax_name"></span></span>
	</div>
	<div class="modal-body">
		<div class="box_content">
			<table>
				<?php
				$grand_tax_selected_val = $module_obj->tax_values;
				if (strlen($grand_tax_selected_val) > 5) {
					$grand_tax_selected_array = explode(',',rtrim($grand_tax_selected_val,','));
				}
				$selected_grand_tax = array();
				if (count($grand_tax_selected_array) > 0) {
					foreach ($grand_tax_selected_array as $k=>$sel_value) {
						$sel_value_array = explode('::',$sel_value);
						$selected_grand_tax[$sel_value_array[0]] = $sel_value_array[1];
					}
				}
				$i= 0 ; 
				foreach ($product_service_tax as $key=>$val) {
					$i++;
					if (array_key_exists($val["tax_name"],$selected_grand_tax)) {
				?>
				<tr>
					<td style="width:150px;">
						<input type="checkbox" CHECKED name="grand_tax_opts[]" id = "cb_grand_tax_<?php echo $i; ?>" value="<?php echo $val["tax_name"];?>">
						<span style="font-size: 12px;margin-left:4px;"><?php echo $val["tax_name"];?> ( % )</span>
					</td>
					<td style="margin-left:5px;">
						<span>
						<input type="text" value="<?php echo $selected_grand_tax[$val["tax_name"]];?>" class="input-mini" id="grand_tax_val_<?php echo $val["tax_name"];?>" name="grand_tax_val_<?php echo $val["tax_name"];?>">
					</td>
				</tr>
				<?php 
				} else {
				?>
				<tr>
					<td style="width:150px;">
						<input type="checkbox" name="grand_tax_opts[]" id = "cb_grand_tax_<?php echo $i; ?>" value="<?php echo  $val["tax_name"];?>">
						<span style="font-size: 12px;margin-left:4px;"><?php echo  $val["tax_name"];?> ( % )</span>
					</td>
					<td style="margin-left:5px;">
						<input type="text" value="<?php echo $val["tax_value"];?>" class="input-mini" id="grand_tax_val_<?php echo $val["tax_name"];?>" name="grand_tax_val_<?php echo $val["tax_name"];?>">
					</td>
				</tr>
				<?php
				}
			}
			?>
			</table>
		</div>
	</div>
	<div class="modal-footer" id="set_grand_tax_from_default_footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="button" id="" class="btn btn-primary set_grand_tax" value="<?php echo _('Set Tax')?>"/>
	</div>
</div>

<!-- popup modal for shipping and handling tax on grand total -->
<div class="modal hide fade" id="shipping_handling_tax">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-info"><?php echo _('Set shipping and handling tax - ');?><span id="grand_tax_name"></span></span>
	</div>
	<div class="modal-body">
		<div class="box_content">
			<table>
				<?php
				$shipping_handling_tax_selected_val = $module_obj->shipping_handling_tax_values;
				if (strlen($shipping_handling_tax_selected_val) > 5) {
					$shipping_handling_tax_selected_val_array = explode(',',rtrim($shipping_handling_tax_selected_val,','));
				}
				$selected_shipping_handling_tax = array();
				if (count($shipping_handling_tax_selected_val_array) > 0) {
					foreach ($shipping_handling_tax_selected_val_array as $k=>$sel_value) {
						$sel_value_array = explode('::',$sel_value);
						$selected_shipping_handling_tax[$sel_value_array[0]] = $sel_value_array[1];
					}
				}
				$i= 0 ; 
				foreach ($shipping_handling_tax as $key=>$val) {
					if (array_key_exists($val["tax_name"],$selected_shipping_handling_tax)) {
						$i++;
				?>
				<tr>
					<td style="width:150px;">
						<input type="checkbox" CHECKED name="grand_shtax_opts[]" id = "cb_sh_tax_<?php echo $i; ?>" value="<?php echo $val["tax_name"];?>">
						<span style="font-size: 12px;margin-left:4px;"><?php echo $val["tax_name"];?> ( % )</span>
					</td>
					<td style="margin-left:5px;">
						<input type="text" value="<?php echo $selected_shipping_handling_tax[$val["tax_name"]];?>" class="input-mini" id="sh_tax_val_<?php echo $val["tax_name"];?>" name="sh_tax_val_<?php echo $val["tax_name"];?>">
					</td>
				</tr>
				<?php 
				} else {?>
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
			}
			?>
			</table>
		</div>
	</div>
	<div class="modal-footer" id="set_shipping_handling_tax_from_default_footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="button" id="" class="btn btn-primary set_shipping_handling_tax" value="<?php echo _('Set Tax')?>"/>
	</div>
</div>

<!-- popup modal for line item discount -->
<div class="modal hide fade" id="item_discount">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
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
						<span id="" class= "perc_discount_val_span_edit" style="display:block;">
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
						<span id="" class="dir_discount_val_span_edit" style="display:block;">
							<input type="number" class="input-small dir_discount_val" name="" id="">
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="button" id="" class="btn btn-primary set_line_discount" value="<?php echo _('Set Discount')?>"/>
	</div>
</div>

<!-- popup modal for grand discount -->
<div class="modal hide fade" id="grand_discount">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge badge-info"><?php echo _('Set the grand discount on ');?><span id="grand_on_price"></span></span>
	</div>
	<div class="modal-body">
		<div class="box_content">
			<table class="table">
				<tr>
					<td colspan="2">
						<input type="radio" <?php echo ($module_obj->discount_type =='no_discount' || $module_obj->discount_type=='' ? 'checked': '');?> id="grand_discount_option" name="grand_discount_option" class="" value="1">&nbsp;&nbsp;<?php echo _('no discount');?>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio"  <?php echo ($module_obj->discount_type =='percentage' ? 'checked': '');?> id="grand_discount_option" name="grand_discount_option" class="" value="2">
						&nbsp;&nbsp;<?php echo _('% discount');?>
					</td>
					<td>
						<span id="" class= "grand_discount_val_span" style="">
							<input type="number" value="<?php echo ($module_obj->discount_type =='percentage' ? $module_obj->discount_value : '');?>" class="input-small" name="grand_perc_discount" id="grand_perc_discount"> %
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" <?php echo ($module_obj->discount_type =='direct' ? 'checked': '');?> id="grand_discount_option" name="grand_discount_option" class="" value="3">
						&nbsp;&nbsp;<?php echo _('direct reduction');?>
					</td>
					<td>
						<span id="" class="grand_dir_discount_val_span" style="">
							<input type="number" value="<?php echo ($module_obj->discount_type =='direct' ? $module_obj->discount_value : '');?>" class="input-small" name="grand_dir_discount" id="grand_dir_discount">
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="button" id="" class="btn btn-primary set_grand_discount" value="<?php echo _('Set Discount')?>"/>
	</div>
</div>

<div class="modal hide fade" id="delete_lineitem_warning">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<span class="badge warning"><?php echo _('DELETE CONFIRM');?></span>
	</div>
	<div class="modal-body">
		<div class="box_content">
			<strong><?php echo _('Are you sure you want to delete');?></strong>
		</div>
	</div>
	<div class="modal-footer" id="set_tax_from_default_footer">
		<a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon-white icon-remove-sign"></i> Close</a>
		<input type="button" id="" class="btn btn-primary line_item_delete_true" value="<?php echo _('Yes')?>"/>
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