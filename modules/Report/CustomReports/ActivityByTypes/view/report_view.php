<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report view
* @author Abhik Chakraborty
*/
?>
<link href="/js/plugins/DataTables/datatables.min.css" rel="stylesheet">
<link href="/js/plugins/DataTables/Buttons-1.2.1/css/buttons.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-1.2.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-1.2.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-1.2.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-1.2.1/js/buttons.print.min.js"></script>
<div class="container-fluid">
	<?php
		echo $breadcrumb ;
	?>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<form id="filter_run_time">
				<input type="hidden" name="runtime" value="1">
				<input type="hidden" name="path" value="<?php echo $_GET['path']?>">
				<input type="hidden" name="resource" value="<?php echo $_GET['resource']?>">
				<div class="left_250" style="margin-left:3px;">
				<?php echo _('Date Filter Type');?><br />
					<select name="report_date_filter_type_runtime" id="report_date_filter_type_runtime">
					<?php
					foreach ($date_filter_options as $key=>$val) {
						$selected = ($date_filter_type == $key ? 'SELECTED':'');
						echo '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
					}
					?>
					</select>
				</div>
				<div class="left_300" <?php echo $date_range_display;?> id="report_date_filter_start">
					<?php echo _('Date start');?><br />
					<?php 
					$report_date_start = (isset($_GET['report_date_start_runtime']) ? $_GET['report_date_start_runtime']:'');
					echo FieldType9::display_field('report_date_start_runtime',$report_date_start);
					?>
				</div>
				<div class="left_300" <?php echo $date_range_display;?> id="report_date_filter_end">
					<?php echo _('Date end');?><br />
					<?php 
					$report_date_end = (isset($_GET['report_date_start_runtime']) ? $_GET['report_date_start_runtime']:'');
					echo FieldType9::display_field('report_date_end_runtime',$report_date_end);
					?>
				</div>
				<div class="left_300" id="report_user_filter">
					<?php echo _('User');?><br />
					<select name="report_user_filter_runtime" id="report_user_filter_runtime">
						<option value="0"><?php echo _('All');?></option>
						<?php
						if (is_array($user_list) && count($user_list) >0) {
							foreach($user_list as $key=>$val) {
								$selected = ($selected_user == $key ? 'SELECTED':'');
								echo '<option value="'.$key.'" '.$selected.'>'.$val['firstname'].' '.$val['lastname'].' ('.$val['user_name'].')</option>';
							}
						}
						?>
					</select>
				</div>
				<div class="clear_float"></div>
				<div class="left_100" style="margin-left:3px;">
					<input type="submit" class="btn btn-primary" id="" value="<?php echo _('generate');?>"/>
				</div>
				<br />
			</form>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<div class="left_300"  id="">
					<p><strong><?php echo _('Summary report');?></strong></p>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist">
					<thead>
						<tr>
							<th width="10%"><?php echo _('Assigned To');?></th>
							<?php
							foreach ($activity_types as $key=>$val) {
								echo '<th width="10%">'.$val.'</th>' ;
							}
							?>
							<th width="10%"><?php echo _('Total');?></th>
						</tr>
					</thead>
					<?php
					if (count($activities_by_type) > 0) {
						foreach ($activities_by_type as $key=>$val) {
							$total = 0 ;
							echo '<tr>' ;
							echo '<td class="">'.$key.'</td>';
							foreach ($activity_types as $type) {
								if (array_key_exists($type,$val)) {
									$total += $val[$type] ;
									echo '<td class="">'.$val[$type].'</td>';
								} else {
									echo '<td class="">0</td>';
								}
							}
							echo '<td class="">'.$total.'</td>';
							echo '</tr>' ;
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
	<div class="clear_float"></div>
	<div class="row-fluid">
		<div class="span12">
			<div class="datadisplay-outer">
				<div class="left_300"  id="">
					<p><strong><?php echo _('Detailed report'); ?></strong></p>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist1">
					<thead>
						<tr>
						<?php
						foreach ($fields_info as $key=>$info) {
							echo '<th width="10%">'.$info["field_label"].'</th>';
						}
						?>
						</tr>
					</thead>
					<?php
					if ($activity->getNumRows() > 0) {
						while ($activity->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($activity->$fields,$info["field_type"],$fieldobject,$activity,2,false) ;
								echo '<td class="">'.$val.'</td>';
							}
							echo '</tr>' ;
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
	$("#report_date_filter_type_runtime").change(function() {
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
		
		if ($("#report_date_filter_type_runtime").val() == '1') {
			if ($("#report_date_start_runtime").val() == '' || $("#report_date_end_runtime").val() == '') {
				display_js_error(REPORT_SELECT_START_END_DATE,'js_errors');
				return false;
			}
		}
	});
	
	
	oTable = $('#sqcrmlist').dataTable({
		"paging":   false,
        "info":     false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});    
	
	oTable1 = $('#sqcrmlist1').dataTable({
		"paging":   false,
        "info":     false,
        "bFilter" : false,
        "aaSorting": [],
        dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});      
});
</script>