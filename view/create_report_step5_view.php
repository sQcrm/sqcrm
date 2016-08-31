<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Create report step5 view 
* @author Abhik Chakraborty
*/ 
$do_report_fields = new ReportFields();
$primary_module_id = $_SESSION["primary_module"] ;
$secondary_module_id = $_SESSION["secondary_module"];
$primary_report_fields = $do_report_fields->get_module_fields_for_report($_SESSION["primary_module"]);
$secondary_report_fields = $do_report_fields->get_module_fields_for_report($_SESSION["secondary_module"]);

$do_report_sorting = new ReportSorting(); 
$sort_fields = $do_report_sorting->get_report_sorting_fields_on_create($_SESSION["report_fields"]); 

// storing the sort fields details in the session
$_SESSION["report_fields_data"] = $sort_fields; 

$selected_report_order_by = array();
if (isset($edit) && $edit == 1) {
	$selected_report_order_by = $do_report_sorting->get_saved_orderby_fields($sqcrm_record_id);
} elseif (isset($_SESSION["report_order_by"])) {
	$selected_report_order_by = $_SESSION["report_order_by"];
}

$e_set_report_data = new Event("Report->eventSetReportData");
$e_set_report_data->addParam("step","5");
if (isset($edit) && $edit == 1) {
	$edit_msg = _('Update Report');
	$e_set_report_data->addParam("mode","edit");
	$e_set_report_data->addParam("sqrecord",$sqcrm_record_id);
} else {
	$edit_msg = _('Create Report');
	$e_set_report_data->addParam("mode","add");
}
echo '<form class="form-horizontal" id="Report__eventSetReportData" name="Report__eventSetReportData" action="/eventcontroler.php" method="post">';
echo $e_set_report_data->getFormEvent();
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="box_content">
				<p><strong><?php echo $edit_msg;?> > <?php echo _('Step 5');?></strong></p>
				<p class="lead"><?php echo _('Select order by options for report')?></p> 
			</div>
			<div class="box_content">
				<div class="row">
					<div class="col-xs-2">
						<label class="control-label"><?php echo _('Order data by');?></label>
					</div>
					<div class="col-xs-4">
						<select name="report_order_by_1" id="report_order_by_1" class="form-control input-sm">
							<option value="0"><?php echo _('Select field'); ?></option>
							<?php
							foreach ($sort_fields as $key=>$val) {
								$selected = '';
								if (is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_1"]["order_by_field"] == $val["idfields"])
									$selected = "SELECTED";
								echo '<option value="'.$val["idfields"].'" '.$selected.'>'.$val["module_label"].'::'.$val["field_label"].'</option>';
							}
							?>
						</select>
					</div>
					<div class="col-xs-4">
						<select name="report_order_by_type_1" id="report_order_by_type_1" class="form-control input-sm">
							<option value="1" <?php if(is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_1"]["order_by_type"] == 1) echo "SELECTED" ;?>><?php echo _('Ascending');?></option>
							<option value="2" <?php if(is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_1"]["order_by_type"] == 2) echo "SELECTED" ;?>><?php echo _('Descending');?></option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2">
						<label class="control-label"><?php echo _('and then by');?></label>
					</div>
					<div class="col-xs-4">
						<select name="report_order_by_2" id="report_order_by_2" class="form-control input-sm">
							<option value="0"><?php echo _('Select field'); ?></option>
							<?php
							foreach ($sort_fields as $key=>$val) {
								$selected = '';
								if (is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_2"]["order_by_field"] == $val["idfields"])
									$selected = "SELECTED";
								echo '<option value="'.$val["idfields"].'" '.$selected.'>'.$val["module_label"].'::'.$val["field_label"].'</option>';
							}
							?>
						</select>
					</div>
					<div class="col-xs-4">
						<select name="report_order_by_type_2" id="report_order_by_type_2" class="form-control input-sm">
							<option value="1" <?php if(is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_2"]["order_by_type"] == 1) echo "SELECTED" ;?>><?php echo _('Ascending');?></option>
							<option value="2" <?php if(is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_2"]["order_by_type"] == 2) echo "SELECTED" ;?>><?php echo _('Descending');?></option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2">
						<label class="control-label"><?php echo _('and finally');?></label>
					</div>
					<div class="col-xs-4">
						<select name="report_order_by_3" id="report_order_by_3" class="form-control input-sm">
							<option value="0"><?php echo _('Select field'); ?></option>
							<?php
							foreach ($sort_fields as $key=>$val) {
								$selected = '';
								if (is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_3"]["order_by_field"] == $val["idfields"])
									$selected = "SELECTED";
								echo '<option value="'.$val["idfields"].'" '.$selected.'>'.$val["module_label"].'::'.$val["field_label"].'</option>';
							}
							?>
						</select>
					</div>
					<div class="col-xs-4">
						<select name="report_order_by_type_3" id="report_order_by_type_3" class="form-control input-sm">
							<option value="1" <?php if(is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_3"]["order_by_type"] == 1) echo "SELECTED" ;?>><?php echo _('Ascending');?></option>
							<option value="2" <?php if(is_array($selected_report_order_by) && count($selected_report_order_by) > 0 && $selected_report_order_by["order_by_3"]["order_by_type"] == 2) echo "SELECTED" ;?>><?php echo _('Descending');?></option>
						</select>
					</div>
				</div>
				<br />
				<hr class="form_hr">
				<?php
				if (isset($edit) && $edit == 1) {?>
					<a href="<?php echo NavigationControl::getNavigationLink($module,"edit?step=4&sqrecord=".$sqcrm_record_id);?>" class="btn btn-default active">
				<?php } else { ?>
					<a href="<?php echo NavigationControl::getNavigationLink($module,"add?step=4");?>" class="btn btn-default active">
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
	$('#Report__eventSetReportData').submit( function() { 
		if ($("#report_order_by_2").val() != '0' && $("#report_order_by_1").val() == '0') {
			display_js_error(REPORT_SELECT_PREVIOUS_ORDER_OPTION,'js_errors');
			$("#report_order_by_2").val('0');
			return false;
		}
		
		if ($("#report_order_by_3").val() != '0' && $("#report_order_by_2").val() == '0') {
			display_js_error(REPORT_SELECT_PREVIOUS_ORDER_OPTION,'js_errors');
			$("#report_order_by_3").val('0');
			return false;
		}
	});
});
</script>