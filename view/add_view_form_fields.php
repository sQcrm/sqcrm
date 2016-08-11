<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* add view form fields section
* @author Abhik Chakraborty
*/
?>
<div class="left_large">
	<a href="<?php echo NavigationControl::getNavigationLink($module,"list");?>" class="btn btn-default active">
	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>  
	<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
</div>
<div class="clear_float"></div>
<hr class="form_hr">
<?php
while ($do_block->next()) { ?>
<div class="box_content_header">
	<strong><?php echo $do_block->block_label;?></strong><hr class="form_hr">
	<?php
	if ($do_block->idblock == 8) {
		echo '<div class="right_large">';
		echo '<input type="radio" id="org_bill_to_ship" class="">&nbsp;&nbsp;'._('Copy Billing Address to Shipping');
		echo '&nbsp;&nbsp;<input type="radio" id="org_ship_to_bill" class="">&nbsp;&nbsp;'._('Copy Shipping Address to Billing');
		echo '</div>';
	} elseif ($do_block->idblock == 12) {
		echo '<div class="right_large">';
		echo '<input type="radio" id="cnt_mailing_to_other" class="">&nbsp;&nbsp;'._('Copy Mailing Address to Other');
		echo '&nbsp;&nbsp;<input type="radio" id="cnt_other_to_mailing" class="">&nbsp;&nbsp;'._('Copy Other Address to Mailing');
		echo '</div>';
	}
	?>
	<?php 
	$do_crmfields->get_form_fields_information($do_block->idblock,$module_id) ;
	$num_fields = $do_crmfields->getNumRows() ;
	$tot_count = 0 ;
	while ($do_crmfields->next()) {
		$fieldobject = 'FieldType'.$do_crmfields->field_type;
		$tot_count++;
		if ($tot_count == 1 || $tot_count%2 != 0) { ?>
		<div class="row">
		<?php 
		} ?>
			<div class="col-md-6">
				<div class="col-md-12">
					<div class="form-group">  
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
							$fieldobject::display_field('','',$module_id);
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
							$fieldobject::display_field($do_crmfields->field_name,'','form-control input-sm');
						}
						?>
						</div>
					</div>
				</div>
			</div>
		<?php
		if ($tot_count != 1 && $tot_count%2 == 0 ) {  ?> 
		</div>
		<?php 
		} 
	} 
	if ($tot_count%2 != 0) echo '</div>';
	?>
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
<div class="left_large">
	<a href="<?php echo NavigationControl::getNavigationLink($module,"list");?>" class="btn btn-default active">
	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Cancel');?></a>  
	<input type="submit" class="btn btn-primary" value="<?php echo _('Save');?>"/>
</div>
<div class="clear_float"></div>