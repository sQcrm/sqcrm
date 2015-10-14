<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* detail view line items
* @author Abhik Chakraborty
*/
?>
<div class="box_content_header">
	<?php echo _('Terms and condition'); ?>
	<hr class="form_hr">
	<span style="font-size:12px;line-height:1.3;">
		<?php echo nl2br($module_obj->terms_condition); ?>
	</span>
</div>
<?php
if (is_array($lineitems) && count($lineitems) > 0) { ?>
<div class="box_content_header">
	<div class="left_300"><?php echo _('Item Information'); ?></div>
		<table class="table table-bordered" id="table_line_items">
			<thead>
				<tr>
					<th width="30%"><?php echo _('Item Name');?></th>
					<th width="10%"><?php echo _('Qty');?></th>
					<th width="18%"><?php echo _('Price');?></th>
					<th width="20%"><?php echo _('Total');?></th>
					<th width="20%"><?php echo _('Net Price');?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($lineitems as $key=>$items) {
				?>
				<tr>
					<td>
						<?php
						$item_name = $items["item_name"] ;
						$item_name_display = '' ;
						if ($items["item_type"] == 'product') {
							$item_name_display = FieldType166::display_value($items["item_value"],$items["item_name"]) ;
						} else {
							$item_name_display = $items["item_name"] ;
						}
						echo '<span style="font-size:12px;line-height:1.3;">'.$item_name_display.'</span>';
						echo '<br /><br /><br />';
						echo '<span style="font-size:12px;line-height:1.3;">'.$items["item_description"].'</span>';
						?>
					</td>
					<td><?php echo FieldType16::display_value($items["item_quantity"]); ?></td>
					<td>
						<div style="height:40px;line-height:1.3;">
							<?php echo FieldType30::display_value($items["item_price"]);?>
						</div>
						<div style="height:40px;line-height:1.3;">
							<?php
							if ($items["discount_type"] == 'direct') {
								echo _('Discount').'-'.FieldType30::display_value($items["discount_value"]);
							} elseif ($items["discount_type"] == 'percentage') {
								echo _('Discount').'-'.$items["discount_value"].' %';
							} else {
								echo _('Discount').'-'._('no discount');
							}
							?>
						</div>
						<div style="height:40px;line-height:1.3;">
							<strong><?php echo _('Total after discount')?></strong>
						</div>
						<div style="height:40px;line-height:1.3;">
							<strong><?php echo _('Tax')?></strong>
							<?php 
							if ($items["tax_values"] != '') {
								echo ' - '.rtrim($items["tax_values"],',');
							} else {
								echo ' - no tax';
							}
							?>
						</div>
					</td>
					<td>
						<div style="height:40px;line-height:1.3;">
							<?php echo FieldType30::display_value($items["item_price"]*$items["item_quantity"]);?>
						</div>
						<div style="height:40px;line-height:1.3;">
							<?php echo FieldType30::display_value($items["discounted_amount"]);?>
						</div>
						<div style="height:40px;line-height:1.3;">
							<?php echo FieldType30::display_value($items["total_after_discount"]);?>
						</div>
						<div style="height:40px;line-height:1.3;">
							<?php echo FieldType30::display_value($items["taxed_amount"]);?>
						</div>
					</td>
					<td>
						<?php echo FieldType30::display_value($items["net_total"]);?>
					</td>
				</tr>
			<?php 
			} ?>
		</tbody>
	</table>		
		
	<!-- grand total -->
	<table class="table table-bordered" id="table_grand_total">
		<tbody>
			<tr>
				<td width="80%">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<b><?php echo _('Net Total');?><b>
					</div>
				<td width="20%">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<?php echo FieldType30::display_value($module_obj->net_total); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<b><?php echo _('Discount');?></b>
						<?php	
						if ($module_obj->discount_type == 'percentage') {
							echo ' - '.$module_obj->discount_value.' %';
						} elseif ($module_obj->discount_type == 'direct') {
							echo ' - '.FieldType30::display_value($module_obj->discount_value);
						} else {
							echo ' - '._('no discount');
						}
						?>
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<?php echo FieldType30::display_value($module_obj->discounted_amount); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<b><?php echo _('Tax');?></b>
						<?php 
						if ($module_obj->tax_values !='') {
							echo ' - '.rtrim($module_obj->tax_values,',');
						}
					?>
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<?php echo FieldType30::display_value($module_obj->taxed_amount); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:30px;text-align:right;line-height:1.3;">
						<b><?php echo _('Shipping/Handling charges');?></b>
					</div>
				</td>
				<td width="20%">
					<div style="height:30px;text-align:right;line-height:1.3;">
						<?php echo FieldType30::display_value($module_obj->shipping_handling_charge); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<b><?php echo _('Shipping/Handling Tax');?></b>
						<?php	
						if ($module_obj->shipping_handling_tax_values != '') {
							echo ' - '.rtrim($module_obj->shipping_handling_tax_values,',');
						}
						?>
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<?php echo FieldType30::display_value($module_obj->shipping_handling_taxed_amount); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:30px;text-align:right;line-height:1.3;">
						<b><?php echo _('Final Adjustment');?></b>
						<?php
						if ($module_obj->final_adjustment_type == 'add') {
							echo '(+)';
						} elseif ($module_obj->final_adjustment_type == 'deduct') {
							echo '(-)';
						}
						?>
					</div>
				</td>
				<td width="20%">
					<div style="height:30px;text-align:right;line-height:1.3;">
						<?php echo FieldType30::display_value($module_obj->final_adjustment_amount); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" style="text-align: right;">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<b><?php echo _('Grand Total');?></b>
					</div>
				</td>
				<td width="20%">
					<div style="height:20px;text-align:right;line-height:1.3;">
						<?php echo FieldType30::display_value($module_obj->grand_total); ?>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
<?php 
} ?>
</div>
<script src="/js/lineitem.js"></script>