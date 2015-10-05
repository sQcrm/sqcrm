<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* add view form fields section
* @author Abhik Chakraborty
*/
?>
<div class="left_600">
	<a href="<?php echo NavigationControl::getNavigationLink($module,"list");?>" class="btn btn-inverse">
	<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
	<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
</div>
<div class="clear_float"></div>
<hr class="form_hr">
<?php
while ($do_block->next()) { ?>
<div class="box_content_header">
	<?php echo $do_block->block_label;?><hr class="form_hr">
	<?php
	if ($do_block->idblock == 8) {
		echo '<div class="right_500">';
		echo '<input type="radio" id="org_bill_to_ship">&nbsp;&nbsp;'._('Copy Billing Address to Shipping');
		echo '&nbsp;&nbsp;<input type="radio" id="org_ship_to_bill">&nbsp;&nbsp;'._('Copy Shipping Address to Billing');
		echo '</div>';
	} elseif ($do_block->idblock == 12) {
		echo '<div class="right_500">';
		echo '<input type="radio" id="cnt_mailing_to_other">&nbsp;&nbsp;'._('Copy Mailing Address to Other');
		echo '&nbsp;&nbsp;<input type="radio" id="cnt_other_to_mailing">&nbsp;&nbsp;'._('Copy Other Address to Mailing');
		echo '</div>';
	}
	?>
	<table width="100%">
	<?php 
	$do_crmfields->get_form_fields_information($do_block->idblock,$module_id) ;
	$num_fields = $do_crmfields->getNumRows() ;
	$tot_count = 0 ;
	while ($do_crmfields->next()) {
		$fieldobject = 'FieldType'.$do_crmfields->field_type;
		$tot_count++;
		if ($tot_count == 1 || $tot_count%2 != 0) { ?>
		<tr>
		<?php 
		} 
		?>
			<td width="40%">
				<div class="control-group">  
				<label class="control-label" for="<?php echo $do_crmfields->field_name; ?>"><?php echo $do_crmfields->field_label;?></label>  
					<div class="controls">  
					<?php
					if ($do_crmfields->field_type == 5 || $do_crmfields->field_type ==6) {
						$fieldobject::display_field($do_crmfields->field_name,$do_crmfields->idfields);
					} elseif ($do_crmfields->field_type == 9) {
						if ($module_id == 2) {
							$fieldobject::display_field($do_crmfields->field_name,$start_end_date);
						} else {
							$fieldobject::display_field($do_crmfields->field_name);
						}
					} elseif ($do_crmfields->field_type == 10) {
						if ($module_id == 2) {
							if ($do_crmfields->field_name == 'start_time')
								$fieldobject::display_field($do_crmfields->field_name,$start_time);
							elseif ($do_crmfields->field_name == 'end_time')
								$fieldobject::display_field($do_crmfields->field_name,$end_time);
						} else {
							$fieldobject::display_field($do_crmfields->field_name);
						}
					} elseif ($do_crmfields->field_type == 104) {
						$fieldobject::display_field($do_crmfields->field_name);
					} elseif ($do_crmfields->field_type == 130) {
						$fieldobject::display_field($do_crmfields->field_name);
					} elseif ($do_crmfields->field_type == 131) {
						$fieldobject::display_field($do_crmfields->field_name);
					} elseif ($do_crmfields->field_type == 141) {
						if (!isset($target_module)) {
							$target_module = $module ; 
						}
						$fieldobject::display_field($do_crmfields->field_name,$target_module);
					} elseif ($do_crmfields->field_type == 143) {
						if (!isset($target_module)) {
							$target_module = $module ; 
						}
						$fieldobject::display_field($do_crmfields->field_name,$target_module);
					} elseif ($do_crmfields->field_type == 15) {
						$fieldobject::display_field();
					} elseif ($do_crmfields->field_type == 150) {
						if ($add_from_related === true) {
							$fieldobject::display_field($do_crmfields->field_name,$related_to_id,$related_to_module_id);
						} else {
							$fieldobject::display_field($do_crmfields->field_name);
						}
					} elseif ($do_crmfields->field_type == 151) {
						if ($add_from_related === true) {
							$fieldobject::display_field($do_crmfields->field_name,$related_to_id,$related_to_module_id);
						} else {
							$fieldobject::display_field($do_crmfields->field_name);
						}
					} else {
						$fieldobject::display_field($do_crmfields->field_name,'','input-xlarge-100');
					}
					?>
					</div>
				</div>
			</td>
		<?php
		if ($tot_count != 1 && $tot_count%2 == 0 ) {  ?> 
		</tr>
		<?php 
		} 
	} 
	?>
	</table>
</div>
<?php 
} 
?>
<?php 
if ($module_id == 13 || $module_id == 14 || $module_id == 15 || $module_id == 16) {
	require("add_view_line_items.php");
}
?>
<hr class="form_hr">
<div class="left_600">
	<a href="<?php echo NavigationControl::getNavigationLink($module,"list");?>" class="btn btn-inverse">
	<i class="icon-white icon-remove-sign"></i> <?php echo _('Cancel');?></a>  
	<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
</div>
<div class="clear_float"></div>