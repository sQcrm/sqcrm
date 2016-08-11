<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Create report step6 view 
* @author Abhik Chakraborty
*/ 
$do_report_fields = new ReportFields();
$do_report_filter = new ReportFilter();
$primary_module_id = $_SESSION["primary_module"] ;
$secondary_module_id = $_SESSION["secondary_module"];
$primary_report_fields = $do_report_fields->get_module_fields_for_report($_SESSION["primary_module"]);
$secondary_report_fields = $do_report_fields->get_module_fields_for_report($_SESSION["secondary_module"]);

$date_filters = $do_report_filter->get_date_filter_fields($primary_module_id,$secondary_module_id);

$date_filter_options = $do_report_filter->get_date_filter_options();
$advanced_filter_options = $do_report_filter->get_advanced_filter_options();

$custom_date_options_style = 'style="display:block;"';

$selected_date_filters = array();
$selected_adv_filters = array();
if (isset($edit) && $edit == 1) {
	$selected_date_filters = $do_report_filter->get_saved_filter_details($sqcrm_record_id);
	$selected_adv_filters = $do_report_filter->get_saved_adv_filter_options($sqcrm_record_id);
} elseif (isset($_SESSION["report_filter"])) {
	$selected_date_filter = $_SESSION["report_filter"]["date_filter_options"];
	$selected_adv_filters = $_SESSION["report_filter"]["advanced_filter_options"];
}

if (count($selected_date_filter) > 0 && $selected_date_filter["report_date_field_type"] != 1) {
	$custom_date_options_style = 'style="display:none;"';
}

$e_set_report_data = new Event("Report->eventSetReportData");
if (isset($edit) && $edit == 1) {
	$edit_msg = _('Update Report');
	$e_set_report_data->addParam("mode","edit");
	$e_set_report_data->addParam("sqrecord",$sqcrm_record_id);
} else {
	$edit_msg = _('Create Report');
	$e_set_report_data->addParam("mode","add");
}
$e_set_report_data->addParam("step","6");
echo '<form class="form-horizontal" id="Report__eventSetReportData" name="Report__eventSetReportData" action="/eventcontroler.php" method="post">';
echo $e_set_report_data->getFormEvent();
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
			<div class="box_content">
				<p><strong><?php echo $edit_msg;?> > <?php echo _('Step 6');?></strong></p>
				<p class="lead"><?php echo _('Select filter options for the report')?></p> 
			</div>
			<div class="box_content">
				<p><?php echo _('Select date filter options for the report')?></p> 
				<label class="control-label" for=""><?php echo _('Select Date Filter');?></label>
				<div class="row">
					<div class="col-xs-6">
						<select name="report_date_field" id="report_date_field" class="form-control input-sm">
							<option value="0"><?php echo _('Select date filter');?></option>
							<?php
							foreach ($date_filters as $key=>$val) {
								$selected = '';
								if (count($selected_date_filter) > 0 && $selected_date_filter["report_date_field"] == $val["idfields"]) {
									$selected = "SELECTED";
								}
								echo '<option value="'.$val["idfields"].'" '.$selected.'>'.$val["field_label"].'</option>';
							}
							?>
						</select>
					</div>
					<div class="col-xs-6">
						<select name="report_date_field_type" id="report_date_field_type" class="form-control input-sm">
							<?php
							foreach ($date_filter_options as $key=>$val) {
								$selected = '';
								if (count($selected_date_filter) > 0 && $selected_date_filter["report_date_field_type"] == $key) {
									$selected = "SELECTED";
								}
								echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<br />
				<div class="row" id="report_date_filter" <?php echo $custom_date_options_style;?>>
					<?php
					$report_date_start ='';
					$report_date_end = '';
					if (count($selected_date_filter) > 0) {
						$report_date_start = $selected_date_filter["report_date_start"];
						$report_date_end = $selected_report_filters["report_date_end"];
					}
					?>
					<div class="col-xs-8">
						<div class="row">
							<div class="col-xs-3">
								<label class="control-label" for=""><?php echo _('Start date');?></label>
							</div>
							<div class="col-xs-5">
								<?php echo FieldType9::display_field('report_date_start',$report_date_start);?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<label class="control-label" for=""><?php echo _('End date');?></label>
							</div>
							<div class="col-xs-5">
								<?php echo FieldType9::display_field('report_date_end',$report_date_end);?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box_content">
				<p><?php echo _('Select advanced filter options for the report')?></p> 
				<?php
				$cnt = 0;
				for ($i=0;$i<=4;$i++) {
					$cnt++;
				?>
				<div class="row">
					<div class="col-xs-4">
						<select name="report_adv_fields_<?php echo $i+1;?>" id="report_adv_fields_<?php echo $i+1;?>" class="form-control input-sm">
							<option value="0"><?php echo _('none');?></option>
							<?php
							echo '<optgroup label="'.$_SESSION["do_module"]->modules_full_details[$_SESSION["primary_module"]]["label"].'"></optgroup>';
							foreach ($primary_report_fields as $idblock=>$blockdata) { 
								foreach ($blockdata as $blockname=>$fieldinfo) {
									echo '<optgroup label="'.$blockname.'" style="padding-left:15px">';
									foreach ($fieldinfo as $key=>$val) {
										if ($val["field_type"] == 9 ) continue ;
										$selected = '';
										if (is_array($selected_adv_filters) && count($selected_adv_filters) > 0 && array_key_exists("advanced_filter_options",$selected_adv_filters)) {
											if (array_key_exists("report_adv_fields_$cnt",$selected_adv_filters["advanced_filter_options"])) {
												if ($selected_adv_filters["advanced_filter_options"]["report_adv_fields_$cnt"] == $val["idfields"]) {
													$selected = "SELECTED";
												}
											}
										}
										echo '<option value="'.$val["idfields"].'" style="padding-left:30px" '.$selected.'>'.$val["field_label"].'</option>';
									}
								}
							}
							if (count($secondary_report_fields) > 0) { 
								echo '<optgroup label="'.$_SESSION["do_module"]->modules_full_details[$secondary_module_id]["label"].'"></optgroup>';
								foreach ($secondary_report_fields as $idblock=>$blockdata) { 
									foreach ($blockdata as $blockname=>$fieldinfo) {
										echo '<optgroup label="'.$blockname.'" style="padding-left:15px">';
										foreach ($fieldinfo as $key=>$val) {
											if ($val["field_type"] == 9 ) continue ;
											if ($primary_module_id == 2 && $secondary_module_id == 5 && $val["idfields"] == 114) continue;
											if ($primary_module_id == 4 && $secondary_module_id == 5 && $val["idfields"] == 114) continue;
											if ($primary_module_id == 6 && $secondary_module_id == 5 && $val["idfields"] == 114) continue;
											$selected = '';
											if (is_array($selected_adv_filters) && count($selected_adv_filters) > 0 && array_key_exists("advanced_filter_options",$selected_adv_filters)) {
												if (array_key_exists("report_adv_fields_$cnt",$selected_adv_filters["advanced_filter_options"])) {
													if ($selected_adv_filters["advanced_filter_options"]["report_adv_fields_$cnt"] == $val["idfields"]) {
														$selected = "SELECTED";
													}
												}
											}
											echo '<option value="'.$val["idfields"].'" style="padding-left:30px" '.$selected.'>'.$val["field_label"].'</option>';
										}
									}
								}
							}
							?>
						</select>
					</div>
					<div class="col-xs-4">
						<select name="report_adv_fields_type_<?php echo $i+1;?>" id = "report_adv_fields_type_<?php echo $i+1;?>" class="form-control input-sm"> 	
							<?php
							foreach ($advanced_filter_options as $key=>$val) {
								$selected = '';
								if (is_array($selected_adv_filters) && count($selected_adv_filters) > 0 && array_key_exists("advanced_filter_options",$selected_adv_filters)) {
									if (array_key_exists("report_adv_fields_type_$cnt",$selected_adv_filters["advanced_filter_options"])) {
										if ($selected_adv_filters["advanced_filter_options"]["report_adv_fields_type_$cnt"] == $key) {
											$selected = "SELECTED";
										}
									}
								}
								echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
							}
							?>
						</select>
					</div>
					<div class="col-xs-3">
						<?php
							$report_adv_fields_val = '';
							if (is_array($selected_adv_filters) && count($selected_adv_filters) > 0 && array_key_exists("advanced_filter_options",$selected_adv_filters)) {
								if (array_key_exists("report_adv_fields_val_$cnt",$selected_adv_filters["advanced_filter_options"])) {
									$report_adv_fields_val = $selected_adv_filters["advanced_filter_options"]["report_adv_fields_val_$cnt"];
								}
							}
						?>
						<input type="text" name="report_adv_fields_val_<?php echo $i+1;?>" id="report_adv_fields_val_<?php echo $i+1;?>" value="<?php echo $report_adv_fields_val;?>" class="form-control input-sm">
					</div>
					<div class="col-xs-1">
						<?php if($i<4) echo '<span style="font-size: 11px;">'._('and').'</span>';?>
					</div>
				</div>
				<?php
				}
				?>
				<br />
				<hr class="form_hr">
				<?php
				if (isset($edit) && $edit == 1) { ?>
					<a href="<?php echo NavigationControl::getNavigationLink($module,"edit?step=5&sqrecord=".$sqcrm_record_id);?>" class="btn btn-default active">
				<?php } else { ?>
					<a href="<?php echo NavigationControl::getNavigationLink($module,"add?step=5");?>" class="btn btn-default active">
				<?php } ?>
					<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <?php echo _('Back');?></a>  
					<input type="submit" class="btn btn-primary" value="<?php echo _('Next');?>"/>	
			</div>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {  
	$("#report_date_field_type").change(function() {
		var date_filter_type = $(this).val();
		if (date_filter_type != 1) {
			$("#report_date_filter").hide();
		} else {
			$("#report_date_filter").show();
		}
	});
	
	$('#Report__eventSetReportData').submit( function() { 
		if ($("#report_adv_fields_2").val() != '0' && $("#report_adv_fields_1").val() == '0') {
			display_js_error(REPORT_SELECT_PREVIOUS_ORDER_BY,'js_errors');
			$("#report_adv_fields_2").val('0');
			return false;
		}
		
		if ($("#report_adv_fields_3").val() != '0' && $("#report_adv_fields_2").val() == '0') {
			display_js_error(REPORT_SELECT_PREVIOUS_ORDER_BY,'js_errors');
			$("#report_adv_fields_3").val('0');
			return false;
		}
		
		if ($("#report_adv_fields_4").val() != '0' && $("#report_adv_fields_3").val() == '0') {
			display_js_error(REPORT_SELECT_PREVIOUS_ORDER_BY,'js_errors');
			$("#report_adv_fields_4").val('0');
			return false;
		}
		
		if ($("#report_adv_fields_5").val() != '0' && $("#report_adv_fields_4").val() == '0') {
			display_js_error(REPORT_SELECT_PREVIOUS_ORDER_BY,'js_errors');
			$("#report_adv_fields_5").val('0');
			return false;
		}
		
		if ($("#report_adv_fields_1").val() != '0') {
			if ($("#report_adv_fields_type_1").val() == 0) {
				display_js_error(REPORT_SELECT_FILTER_TYPE,'js_errors');
				$("#report_adv_fields_type_1").focus();
				return false ;
			}
			if ($("#report_adv_fields_val_1").val() == '') {
				display_js_error(REPORT_SELECT_FILTER_VALUE,'js_errors');
				$("#report_adv_fields_val_1").focus();
				return false ;
			}
		}
		
		if ($("#report_adv_fields_2").val() != '0') {
			if ($("#report_adv_fields_type_2").val() == 0) {
				display_js_error(REPORT_SELECT_FILTER_TYPE,'js_errors');
				$("#report_adv_fields_type_2").focus();
				return false ;
			}
			
			if ($("#report_adv_fields_val_2").val() == '') {
				display_js_error(REPORT_SELECT_FILTER_VALUE,'js_errors');
				$("#report_adv_fields_val_2").focus();
				return false ;
			}
		}
		
		if ($("#report_adv_fields_3").val() != '0') {
			if ($("#report_adv_fields_type_3").val() == 0) {
				display_js_error(REPORT_SELECT_FILTER_TYPE,'js_errors');
				$("#report_adv_fields_type_3").focus();
				return false ;
			}
			
			if ($("#report_adv_fields_val_3").val() == '') {
				display_js_error(REPORT_SELECT_FILTER_VALUE,'js_errors');
				$("#report_adv_fields_val_3").focus();
				return false ;
			}
		}
		
		if ($("#report_adv_fields_4").val() != '0') {
			if ($("#report_adv_fields_type_4").val() == 0) {
				display_js_error(REPORT_SELECT_FILTER_TYPE,'js_errors');
				$("#report_adv_fields_type_4").focus();
				return false ;
			}
			
			if ($("#report_adv_fields_val_4").val() == '') {
				display_js_error(REPORT_SELECT_FILTER_VALUE,'js_errors');
				$("#report_adv_fields_val_4").focus();
				return false ;
			}
		}
		
		if ($("#report_adv_fields_5").val() != '0') {
			if ($("#report_adv_fields_type_5").val() == 0) {
				display_js_error(REPORT_SELECT_FILTER_TYPE,'js_errors');
				$("#report_adv_fields_type_5").focus();
				return false ;
			}
			
			if ($("#report_adv_fields_val_5").val() == '') {
				display_js_error(REPORT_SELECT_FILTER_VALUE,'js_errors');
				$("#report_adv_fields_val_5").focus();
				return false ;
			}
		}	
	});
});
</script>