<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt
/**
* Create report step4 view 
* @author Abhik Chakraborty
*/ 
$do_report_fields = new ReportFields();
$primary_module_id = $_SESSION["primary_module"] ;
$secondary_module_id = $_SESSION["secondary_module"];
$primary_report_fields = $do_report_fields->get_module_fields_for_report($_SESSION["primary_module"]);
$secondary_report_fields = $do_report_fields->get_module_fields_for_report($_SESSION["secondary_module"]);

$selected_report_fields = array();
if (isset($edit) && $edit == 1) {
	$do_report_sorting = new ReportSorting();
	$saved_fields  = $do_report_fields->get_report_fields_ids($sqcrm_record_id);
	if (false !== $saved_fields && count($saved_fields) > 0)
		$selected_report_fields = $do_report_sorting->get_report_sorting_fields_on_create($saved_fields);
	else $selected_report_fields = array();
} elseif (isset($_SESSION["report_fields"])) {
	if (isset($_SESSION["report_fields_data"]) && count($_SESSION["report_fields_data"]) > 0) {
		$selected_report_fields = $_SESSION["report_fields_data"] ; 
	}
}

$e_set_report_data = new Event("Report->eventSetReportData");
$e_set_report_data->addParam("step","4");
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
	<div class="row-fluid">
		<div class="span12" style="margin-left:3px;">
			<div class="box_content">
				<h3><?php echo $edit_msg;?> > <?php echo _('Step 4');?></h3>
				<p><?php echo _('Select fields for the report')?></p> 
			</div>
			<div class="box_content">
				<div class="alert alert-info">
					<p><?php echo _('Primary Module : '). $_SESSION["do_module"]->modules_full_details[$primary_module_id]["label"]; ?></p>
					<p><?php echo _('Secondary Module : ').$_SESSION["do_module"]->modules_full_details[$secondary_module_id]["label"]; ?></p>
				</div>
				<div class="control-group">
					<div class="controls">
						<table align="center">
							<tr>
								<td>
									<label class="control-label" for=""><?php echo _('Available Fields')?></label><br />
									<select name="select_module_fields" id="select_module_fields" multiple size = "20" style = "width:300px;">
										<?php
										echo '<optgroup label="'.$_SESSION["do_module"]->modules_full_details[$_SESSION["primary_module"]]["label"].'"></optgroup>';
										foreach ($primary_report_fields as $idblock=>$blockdata) { 
											foreach ($blockdata as $blockname=>$fieldinfo) {
												echo '<optgroup label="'.$blockname.'" style="padding-left:15px">';
												foreach ($fieldinfo as $key=>$val) {
													if ($primary_module_id == 2 && $val["field_type"] == 151 
													&& ( $secondary_module_id == 3 || $secondary_module_id == 4 || $secondary_module_id == 5 || $secondary_module_id == 6)) continue;
													if ($primary_module_id == 4 && $secondary_module_id == 6 && $val["idfields"] == 78) continue;
													if ($primary_module_id == 5 && ( $secondary_module_id == 4 || $secondary_module_id == 6) 
													&& $val["field_type"] == 150) continue ;
													if ($primary_module_id == 12 && $secondary_module_id == 11 && $val["field_type"] == 160) continue;
													if ($primary_module_id == 16 && $secondary_module_id == 11 && $val["field_type"] == 160) continue;
													if ($primary_module_id == 13 && $secondary_module_id == 5 && $val["field_type"] == 133) continue;
													if ($primary_module_id == 13 && $secondary_module_id == 6 && $val["field_type"] == 141) continue;
													if ($primary_module_id == 14 && $secondary_module_id == 6 && $val["field_type"] == 141) continue;
													if ($primary_module_id == 14 && $secondary_module_id == 5 && $val["field_type"] == 133) continue;
													if ($primary_module_id == 14 && $secondary_module_id == 4 && $val["field_type"] == 142) continue;
													if ($primary_module_id == 14 && $secondary_module_id == 13 && $val["field_type"] == 170) continue;
													if ($primary_module_id == 15 && $secondary_module_id == 6 && $val["field_type"] == 141) continue;
													if ($primary_module_id == 15 && $secondary_module_id == 5 && $val["field_type"] == 133) continue;
													if ($primary_module_id == 15 && $secondary_module_id == 4 && $val["field_type"] == 142) continue;
													if ($primary_module_id == 15 && $secondary_module_id == 14 && $val["field_type"] == 180) continue;
													echo '<option value="'.$val["idfields"].'" style="padding-left:30px">'.$val["field_label"].'</option>';
												}
											}
										}
										if (count($secondary_report_fields) > 0) { 
											echo '<optgroup label="'.$_SESSION["do_module"]->modules_full_details[$secondary_module_id]["label"].'"></optgroup>';
											foreach ($secondary_report_fields as $idblock=>$blockdata) { 
												foreach ($blockdata as $blockname=>$fieldinfo) {
													echo '<optgroup label="'.$blockname.'" style="padding-left:15px">';
													foreach ($fieldinfo as $key=>$val) {
														if (($primary_module_id == 6 || $primary_module_id == 4) && $secondary_module_id == 5 && $val["field_type"] == 150) continue;
														if ($primary_module_id == 4 && $secondary_module_id == 6 && $val["idfields"] == 78) continue;
														if ($primary_module_id == 6 && $secondary_module_id == 4 && $val["idfields"] == 78) continue;
														if ($primary_module_id == 11 && $secondary_module_id == 12 && $val["field_type"] == 160) continue;
														if ($primary_module_id == 11 && $secondary_module_id == 16 && $val["field_type"] == 160) continue;
														if ($primary_module_id == 13 && $secondary_module_id == 5 && $val["field_type"] == 133) continue;
														if ($primary_module_id == 13 && $secondary_module_id == 6 && $val["field_type"] == 141) continue;
														if ($primary_module_id == 14 && $secondary_module_id == 6 && $val["field_type"] == 141) continue;
														if ($primary_module_id == 14 && $secondary_module_id == 5 && $val["field_type"] == 133) continue;
														if ($primary_module_id == 14 && $secondary_module_id == 4 && $val["field_type"] == 142) continue;
														if ($primary_module_id == 14 && $secondary_module_id == 13 && $val["field_type"] == 170) continue;
														if ($primary_module_id == 15 && $secondary_module_id == 6 && $val["field_type"] == 141) continue;
														if ($primary_module_id == 15 && $secondary_module_id == 5 && $val["field_type"] == 133) continue;
														if ($primary_module_id == 15 && $secondary_module_id == 4 && $val["field_type"] == 142) continue;
														if ($primary_module_id == 15 && $secondary_module_id == 14 && $val["field_type"] == 180) continue;
														echo '<option value="'.$val["idfields"].'" style="padding-left:30px">'.$val["field_label"].'</option>';
													}
												}
											}
										}
										?>
									</select>
								</td>
								<td width="50px;" align="center"><br />
									<a href="#" class="btn btn-success btn-mini-1" id="module_fields_add_select"><i class="icon-white icon-arrow-right"></i></a>
								</td>
								<td>
									<label class="control-label" for=""><?php echo _('Report Fields')?></label><br />
									<?php
									if (count($selected_report_fields) > 0) {
										echo '<select name="report_fields[]" id="report_fields" multiple size = "19">';
										foreach ($selected_report_fields as $key=>$val) {
											echo '<option value="'.$val["idfields"].'" SELECTED>'.$val["field_label"].'</option>';
										}
										echo '</select>';
									} else {
										echo '<select name="report_fields[]" id="report_fields" multiple size = "19"></option>';
									}
									?>
								</td>
								<td width="50px;" align="center"><br />
									<a href="#" class="btn btn-success btn-mini-1" id="report_fields_up"><i class="icon-white icon-arrow-up"></i></a>
									<br /><br />
									<a href="#" class="btn btn-success btn-mini-1" id="report_fields_down"><i class="icon-white icon-arrow-down"></i></a>
									<br /><br />
									<a href="#" class="btn btn-inverse btn-mini-1" id="report_fields_delete"><i class="icon-white icon-remove-sign"></i></a>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="form-actions">  
				<?php
				if (isset($edit) && $edit == 1) {
				?>
				<a href="<?php echo NavigationControl::getNavigationLink($module,"edit?step=3&sqrecord=".$sqcrm_record_id);?>" class="btn btn-inverse">
				<?php } else { ?>
				<a href="<?php echo NavigationControl::getNavigationLink($module,"add?step=3");?>" class="btn btn-inverse">
				<?php } ?>
				<i class="icon-white icon-remove-sign"></i> <?php echo _('Back');?></a>  
				<input type="submit" class="btn btn-primary" value="<?php echo _('Next');?>"/>
			</div>
			</form>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {  
	$("#module_fields_add_select").click(function(e) {
		e.preventDefault(); 
		$("#select_module_fields option:selected").each( function() {
			var transfer_val = $(this).val() ;
			var append_data = true ;
			$("#report_fields option").each(function() {
				if (transfer_val == this.value) {
					append_data = false ;
					return false;
				}
			});
			if (append_data == true) {
				$("#report_fields").append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
				// Done to make the item selected so that the validation knows an item is selected and validator is happy :)
				$("#report_fields option[value='"+transfer_val+"']").attr("selected", 1);
			}
		});
	});
	
	$("#report_fields_delete").click(function(e) {
		e.preventDefault(); 
		$("#report_fields option:selected").each( function() {
			$(this).remove();
		});
		$('#report_fields option').prop('selected', true);
	});
	
	$("#report_fields_up").click(function(e) {
		e.preventDefault(); 
		$("#report_fields option:selected").each( function() {
			var new_position = $("#report_fields option").index(this) - 1;
			if (new_position > -1) {
				$("#report_fields option").eq(new_position).before("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
				$(this).remove();
			}
		});
	});
	
	$("#report_fields_down").click(function(e) {
		e.preventDefault(); 
		var count_fields = $("#report_fields option").size();
		$("#report_fields option:selected").each( function() {
			var new_position = $("#report_fields option").index(this) + 1;
			if (new_position < count_fields) {
				$("#report_fields option").eq(new_position).after("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
				$(this).remove();
			}
		});
	});
	
	$('#Report__eventSetReportData').validate({
		rules: {
			'report_fields[]': {
				required: true
			}
		},
		highlight: function(label) {
			$(label).closest('.control-group').addClass('error');
		},
		success: function(label) {
			label
			.text('OK!').addClass('valid')
			.closest('.control-group').addClass('success');
		}
	});
	
	$('#Report__eventSetReportData').submit( function() {
		$('#report_fields option').prop('selected', true);
	});
});
</script>