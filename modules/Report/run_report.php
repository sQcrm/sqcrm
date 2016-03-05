<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report run
* @author Abhik Chakraborty
*/

if ((int)$sqcrm_record_id > 0) {
	$do_report = new Report();
	$do_report_module_rel = new ReportModuleRel();
	$do_report->set_report_modules($do_report_module_rel->get_report_modules($sqcrm_record_id));
	$do_report_fields = new ReportFields();
	$do_report->set_report_fields($do_report_fields->get_report_fields($sqcrm_record_id));
	$do_report_sorting = new ReportSorting();
	$do_report->set_report_order_by($do_report_sorting->get_report_sorting_condition($sqcrm_record_id));
	$do_report_filter = new ReportFilter();
	$do_report->set_report_date_filter($do_report_filter->get_parsed_date_filter($sqcrm_record_id));
	$adv_filter = $do_report_filter->get_parsed_adv_filter($sqcrm_record_id);
	
	if (isset($_REQUEST["runtime"]) && (int)$_REQUEST["runtime"] == 1) {
		$data= array(
			"filter_type"=>$_REQUEST["report_date_field_type_runtime"],
			"idfield"=>$_REQUEST["report_date_field_runtime"],
			"start_date"=>$_REQUEST["report_date_start_runtime"],
			"end_date"=>$_REQUEST["report_date_end_runtime"],
		);
		$set_date_filter = true ;
		if ((int)$_REQUEST["report_date_field_runtime"] == 0) {
			$set_date_filter = false ;
		} else {
			if ((int)$_REQUEST["report_date_field_type_runtime"] == 1 && ($_REQUEST["report_date_start_runtime"] == '' || $_REQUEST["report_date_end_runtime"]== '')) {
				$set_date_filter = false ;
			}
		}
		if (true === $set_date_filter) {
			$do_report->set_report_date_filter($do_report_filter->get_parsed_date_filter($sqcrm_record_id,$data));
		}
	}
	
	if (false !== $adv_filter) {
		$do_report->set_report_adv_filter($adv_filter["where"]);
		$report_query = $do_report->execute_report($sqcrm_record_id); //echo $report_query;
		$do_report->query($report_query,$adv_filter["bind_params"]);
	} else {
		$report_query = $do_report->execute_report($sqcrm_record_id); //echo $report_query;
		$do_report->query($report_query);
	}
	//echo $report_query;exit;
	$report_modules = $do_report->get_report_modules();
	$date_filters = $do_report_filter->get_date_filter_fields($report_modules["primary"]["idmodule"],$report_modules["secondary"]["idmodule"]);
	$date_filter_options = $do_report_filter->get_date_filter_options();
	$saved_date_filter = array();
	$custom_date_filter_values = false ;
	if (isset($_REQUEST["runtime"]) && (int)$_REQUEST["runtime"] == 1) {
		if ($_REQUEST["report_date_field_type_runtime"] == 1 ) $custom_date_filter_values = true ;
		$saved_date_filter = array(
			"idfield"=>$_REQUEST["report_date_field_runtime"],
			"filter_type"=>$_REQUEST["report_date_field_type_runtime"],
			"start_date"=>FieldType9::convert_before_save($_REQUEST["report_date_start_runtime"]),
			"end_date"=>FieldType9::convert_before_save($_REQUEST["report_date_end_runtime"])
		);
	} else {
		$saved_date_filter_qry = $do_report_filter->get_saved_date_filter();
		$do_report_filter->query($saved_date_filter_qry,array($sqcrm_record_id));
		if ($do_report_filter->getNumRows() > 0) { 
			$do_report_filter->next();
			if ($do_report_filter->filter_type == 1) $custom_date_filter_values = true ;
			$saved_date_filter = array(
				"idfield"=>$do_report_filter->idfield,
				"filter_type"=>$do_report_filter->filter_type,
				"start_date"=>$do_report_filter->start_date,
				"end_date"=>$do_report_filter->end_date
			);
		} else{ $custom_date_filter_values = true ; }
	}
	
	$date_range_display = 'style="display:block;margin-left:3px;"';
	if (false === $custom_date_filter_values) $date_range_display = 'style="display:none;margin-left:3px;"';
}
?>
<link href="/js/plugins/DataTables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/DataTables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
     oTable = $('#sqcrmlist').dataTable({
				"paging":   false,
        "info":     false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "/js/plugins/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
        }
			});        
   });
</script>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
				<div class="datadisplay-outer">
				<form id="filter_run_time">
					<input type="hidden" name="sqrecord" value="<?php echo $sqcrm_record_id;?>">
					<input type="hidden" name="runtime" value="1">
					<div class="left_250">
						<?php echo _('Date Filter');?><br />
						<select name="report_date_field_runtime" id="report_date_field_runtime">
							<option value="0"><?php echo _('Select date filter');?></option>
							<?php
							foreach ($date_filters as $key=>$val) {
								$selected = '';
								if (count($saved_date_filter) > 0 && $saved_date_filter["idfield"] == $val["idfields"]) {
									$selected = "SELECTED";
								}
								echo '<option value="'.$val["idfields"].'" '.$selected.'>'.$val["field_label"].'</option>';
							}
							?>
					</select>
				</div>
				<div class="left_250" style="margin-left:3px;">
					<?php echo _('Date Filter Type');?><br />
					<select name="report_date_field_type_runtime" id="report_date_field_type_runtime">
						<?php
						foreach ($date_filter_options as $key=>$val) {
							$selected = '';
							if (count($saved_date_filter) > 0 && $saved_date_filter["filter_type"] == $key) {
								$selected = "SELECTED";
							}
							echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
						}
						?>
					</select>
				</div>
				<div class="left_300" <?php echo $date_range_display;?> id="report_date_filter_start">
					<?php echo _('Date start');?><br />
					<?php 
					$report_date_start = '';
					if (count($saved_date_filter) > 0) $report_date_start = $saved_date_filter["start_date"];
					echo FieldType9::display_field('report_date_start_runtime',$report_date_start);
					?>
				</div>
				<div class="left_300" <?php echo $date_range_display;?> id="report_date_filter_end">
					<?php echo _('Date end');?><br />
					<?php 
					$report_date_end = '';
					if (count($saved_date_filter) > 0) $report_date_end = $saved_date_filter["end_date"];
					echo FieldType9::display_field('report_date_end_runtime',$report_date_end);
					?>
				</div>
				<div class="clear_float"></div>
				<div class="left_100" style="margin-left:3px;">
					<input type="submit" class="btn btn-primary" id="" value="<?php echo _('generate');?>"/>
				</div>
			</form>
			</div>
		</div>
		<div class="span12" style="margin-left:3px;">
			<div class="datadisplay-outer">
				<div class="left_250">
				<?php echo $do_report->name ; ?> - <?php echo _('Total : ').$do_report->getNumRows().' '._('record(s)')?>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist">
				<?php
				$fields_info = $do_report->get_report_fields();
				$report_modules = $do_report->get_report_modules();
				echo '<thead><tr>';
				foreach ($fields_info as $key=>$info) {
					echo '<th width="10%">'.$info["field_label"].'</th>';
				}
				echo '</tr></thead>';
				if ($do_report->getNumRows() > 0) {
					$do_crmfields = new CRMFields();
					while ($do_report->next()) {
						echo '<tr>';
						foreach ($fields_info as $fields=>$info) {
							$fieldobject = 'FieldType'.$info["field_type"];
							$val = '';
							$field_name = '';
							if ($info["idmodule"] == $report_modules["primary"]["idmodule"]) {
								if ($info["field_type"] == 131) {
									if ($info["field_name"] == 'idorganization') {
										$val = $fieldobject::display_value($do_report->org_name,$do_report->org_name,false);
									} elseif ($info["field_name"] == 'member_of') {
										$val = $fieldobject::display_value($do_report->organization_member_of,$do_report->organization_member_of,false);
									}
								} elseif ($info["field_type"] == 130) {
									$val = $fieldobject::display_value($do_report->contact_report_to,$do_report->contact_report_to,false);
								} elseif ($info["field_type"] == 133) {
									$field_name = $report_modules["primary"]["module_name"]."_".'potential_name_133';
									$val = $fieldobject::display_value('',$do_report->$field_name,false);
								} elseif ($info["field_type"] == 150) {
									$val = $fieldobject::display_value($do_report->potentials_related_to_value,$do_report->potentials_related_to_idmodule,$do_report->potentials_related_to_value,false);
								} elseif ($info["field_type"] == 151) {
									$val = $fieldobject::display_value(1,$do_report->events_related_to_idmodule,$do_report->events_related_to_value,false);
								} elseif ($info["field_type"] == 165) {
									$val = $fieldobject::display_value($do_report->product_tax_values);
								} elseif ($info["field_type"] == 141) {
									$field_name = $report_modules["primary"]["module_name"]."_".'org_name_141';
									$val = $fieldobject::display_value('',$do_report->$field_name,false);
								} elseif ($info["field_type"] == 142) {
									$field_name = $report_modules["primary"]["module_name"]."_".'cnt_name_142';
									$val = $fieldobject::display_value('',$do_report->$field_name,false);
								} elseif ($info["field_type"] == 143) {
									$val = $fieldobject::display_value('',$do_report->contact_name,false);
								} elseif ($info["field_type"] == 160) {
									$val = $fieldobject::display_value('',$do_report->vendor_name,false);
								} else {
									$val = $do_crmfields->display_field_value($do_report->$info["field_name"],$info["field_type"],$fieldobject,$this,$info["idmodule"],false);
								}
							} else {
								if ($info["field_type"] == 131) {
									if ($info["field_name"] == 'idorganization') {
										$field_name = $report_modules["secondary"]["module_name"]."_".'org_name';
										$val = $fieldobject::display_value($do_report->$field_name,$do_report->$field_name,false);
									} elseif ($info["field_name"] == 'member_of') {
										$val = $fieldobject::display_value($do_report->organization_member_of,$do_report->organization_member_of,false);
									}
								} elseif ($info["field_type"] == 130) {
									$val = $fieldobject::display_value($do_report->contact_report_to,$do_report->contact_report_to,false);
								} elseif ($info["field_type"] == 133) {
									$field_name = $report_modules["secondary"]["module_name"]."_".'potential_name_133';
									$val = $fieldobject::display_value('',$do_report->$field_name,false);
								} elseif ($info["field_type"] == 150) {
									$val = $fieldobject::display_value($do_report->potentials_related_to_value,$do_report->potentials_related_to_idmodule,$do_report->potentials_related_to_value,false);
								} elseif ($info["field_type"] == 165) {
									$val = $fieldobject::display_value($do_report->product_tax_values);
								} elseif ($info["field_type"] == 141) {
									$field_name = $report_modules["secondary"]["module_name"]."_".'org_name_141';
									$val = $fieldobject::display_value('',$do_report->$field_name,false);
								} elseif ($info["field_type"] == 142) {
									$field_name = $report_modules["secondary"]["module_name"]."_".'cnt_name_142';
									$val = $fieldobject::display_value('',$do_report->$field_name,false);
								} elseif ($info["field_type"] == 143) {
									$val = $fieldobject::display_value('',$do_report->contact_name,false);
								} elseif ($info["field_type"] == 160) {
									if ($report_modules["primary"]["idmodule"] == 16 &&  $report_modules["secondary"]["idmodule"] == 12) {
										$val = $fieldobject::display_value('',$do_report->product_vendor_name,false);
									} else {
										$val = $fieldobject::display_value('',$do_report->vendor_name,false);
									}
								} else {
									$field_name = $report_modules["secondary"]["module_name"]."_".$info["field_name"];
									$val = $do_crmfields->display_field_value($do_report->$field_name,$info["field_type"],$fieldobject,$this,$info["idmodule"],false);
								}
							}
							echo '<td class="">'.$val.'</td>';
						}
						echo '</tr>';
					}
				}  
				?>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {  
	$("#report_date_field_type_runtime").change(function() {
		var date_filter_type = $(this).val();
		if (date_filter_type != 1) {
			$("#report_date_filter_start").hide();
			$("#report_date_filter_end").hide();
		} else {
			$("#report_date_filter_start").show();
			$("#report_date_filter_end").show();
		}
	});
	
	$("#filter_run_time").submit(function() {
		if ($("#report_date_field_runtime").val() == '0') {
			display_js_error(REPORT_SELECT_DATE_FILTER,'js_errors');
			return false;
		}
		
		if ($("#report_date_field_type_runtime").val() == '1') {
			if ($("#report_date_start_runtime").val() == '' || $("#report_date_end_runtime").val() == '') {
				display_js_error(REPORT_SELECT_START_END_DATE,'js_errors');
				return false;
			}
		}
	});
});
</script>