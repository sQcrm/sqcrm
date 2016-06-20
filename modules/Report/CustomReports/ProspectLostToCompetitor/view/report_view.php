<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt 
/**
* Report view
* @author Abhik Chakraborty
*/
?>
<link rel="stylesheet" type="text/css" href="/js/plugins/jqplot/jquery.jqplot.min.css" />
<script src="/js/plugins/jqplot/jquery.jqplot.min.js"></script>
<script src="/js/plugins/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="/js/jquery/plugins/accounting.js"></script>
<link href="/js/plugins/DataTables/datatables.min.css" rel="stylesheet">
<link href="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/css/buttons.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="/js/plugins/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="/js/plugins/DataTables/Buttons-<?php echo DATATABLE_BUTTONS_VERSION;?>/js/buttons.print.min.js"></script>
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
				<p><strong><?php echo _('Prospect Lost to Competitor ');?> <?php echo ($prospect_lostto_competitor_by_name["grand_total"] > 0 ? _(' - Totalling ').FieldType30::display_value($prospect_lostto_competitor_by_name["grand_total"]):'');?></strong></p>
				<div id="c1">
				</div>
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
				<table cellpadding="0" cellspacing="0" border="0" class="datadisplay" id="sqcrmlist">
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
					if ($prospect_lostto_competitor->getNumRows() > 0) {
						while ($prospect_lostto_competitor->next()) {
							echo '<tr>' ;
							foreach ($fields_info as $fields=>$info) {
								$fieldobject = 'FieldType'.$info["field_type"];
								$val = $do_crm_fields->display_field_value($prospect_lostto_competitor->$fields,$info["field_type"],$fieldobject,$prospect_lostto_competitor,5,false) ;
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
	
	<?php
    if (sizeof($prospect_lostto_competitor_by_name['data']) > 0) {
	?>
		var data = [
			<?php
			$cnt = 0;
			foreach ($prospect_lostto_competitor_by_name['data'] as $key=>$val) {
				echo "['".$key."',".$val."]";
				if ($cnt != sizeof($prospect_lostto_competitor_by_name['data'])-1) { echo ","; }
				$cnt++;
			}
			?>
		];
		var plot1 = $.jqplot ('c1', [data], { 
			seriesDefaults: {
				// Make this a pie chart.
				renderer: $.jqplot.PieRenderer, 
				rendererOptions: {
					// Put data labels on the pie slices.
					// By default, labels show the percentage of the slice.
					showDataLabels: true
				}
			}, 
			legend: { show:true, location: 'e' }
		});
		
		window.onresize = function(event) {
			plot1.destroy();
			plot1.replot();
		};
		
		/*$(window).resize(function() {
			plot3.replot( { resetAxes: true } );
		});*/
	<?php 
	} else { ?>
		$("#c1").append('<p>'+NO_DATA_FOR_GRAPH+'</p>');
	<?php 
	} ?>
	
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
});
</script>